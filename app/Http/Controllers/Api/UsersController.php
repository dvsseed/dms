<?php

namespace App\Http\Controllers\Api;

use DB;
use App\User;
use App\UserMenu;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

use App\Http\Requests;
use Excel;
use Hashids;

use Anam\PhantomMagick\Converter;

class UsersController extends ApiController
{
//    protected $user;
    protected $useragent;

    /**
     * UsersController constructor.
     */
    public function __construct(Request $request)
    {
        // $this->middleware('authorized:manage-post,posts', ['except' => ['index', 'show']]);
        $this->middleware('auth');
        $this->middleware('timeout');

//        $this->user = $request->user();
        // admin = 9, 系統管理員
        // member = 1, 一般使用者
        // super-moderator = 7, 機構管理員
        // patient = 0, 病人
        $this->useragent = $request->header('User-Agent');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return $this->respondWith(User::all(), new UserTransformer);
        if ($request->user()->name == 'admin' || $request->user()->role_level == 9) {
//            if($request->input('page_length') == 10) {
                return $this->respondWith(User::where('role_level', '<', 9)
                    ->orderBy('created_at', 'DESC')
                    ->paginate(10)
                    ->appends(['include' => $request->input('include')]), new UserTransformer);
//            }
//            if($request->page_length == 'All') {
//                return $this->respondWith(User::where('role_level', '<', 9)->orderBy('created_at', 'DESC')->appends(['include' => $request->input('include')]), new UserTransformer);
//            }
        } else {
            return $this->respondWith(User::where('role_level', '<', 9)
                ->where('hosp_id', $request->user()->hosp_id)
                ->orderBy('created_at', 'DESC')
                ->paginate(10)
                ->appends(['include' => $request->input('include')]), new UserTransformer);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User;
//        $user->name = hash('adler32', time());
        $user->name = '請輸入使用者帳號';
//        $user->fill($request->all());
        $user->save();

        //同時新增該使用者的選單權限
        $uid = $user->id;
        $usermenu = UserMenu::firstOrNew(['user_id' => $uid]);
//        $usermenu = new UserMenu;
        $usermenu->user_id = $user->id;
        $usermenu->menu1 = 0;
        $usermenu->menu2 = 1; //血糖資料, 預設 開放給~[病患]使用
        $usermenu->menu3 = 0;
        $usermenu->menu4 = 0;
        $usermenu->menu5 = 0;
        $usermenu->menu6 = 0;
        $usermenu->menu7 = 0;
        $usermenu->menu8 = 0;
        $usermenu->save();

        LogsController::saveLog('users', 'store(保存)', $request->ip(), $this->useragent);
        return $this->respondWith($user, new UserTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //get id from hashid
        $userid = \Hashids::decode($id)[0];
//        $user = User::findOrFail($userid);
        $user = User::where('id', $userid)->first();

        return $this->respondWith($user, new UserTransformer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //get id from hashid
        $userid = \Hashids::decode($id)[0];
//        $user = User::findOrFail($userid);
        $user = User::where('id', $userid)->first();

        return $this->respondWith($user, new UserTransformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $userid = \Hashids::decode($id)[0];
        $user = User::findOrFail($userid);

//            $user->fill($request->only('name', 'hosp_id', 'bio'));
        $user->fill($request->all());
        $user->password = bcrypt($request->password);
        //是為[病患] => role_level = 0
//        if($request->patient == '0') {
//            $user->role_level = 0; //病患
//        } else if($request->patient == '1'){
//            $user->role_level = 1; //一般使用者
//        }
//        else {
//            $user->role_level = $request->role_level;
//        }

        //自動更改: 醫事機構名稱
        if(empty($user->institution)) {
            $hospbsc = DB::table('hospbsc')
                ->select('institution')
                ->where('hosp_id', '=', $request->hosp_id)
                ->first();
            $user->institution = $hospbsc->institution;
        }

        $user->save();

        LogsController::saveLog('users', 'update(更新)', $request->ip(), $this->useragent);
        return $this->respondWith($user, new UserTransformer);
//        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function destroy(User $user)
    public function destroy(Request $request, $id)
    {
        $userid = \Hashids::decode($id)[0];
        $user = User::findOrFail($userid);
        $user->delete();

        LogsController::saveLog('users', 'destroy(删除)', $request->ip(), $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '刪除使用者成功!!'
        ]);
    }

    /**
     * Export the specified resource to Excel format file.
     *
     * @throws \Exception
     *
     */
    public function xlsUsers()
    {
        $users = $this->getUsersData();

        Excel::create('使用者清單', function($excel) use($users) {
            $excel->sheet('users', function($sheet) use($users) {
                $sheet->fromArray($users, null, 'A1', false, false);
                $sheet->prependRow(1, array('ID', '使用者', '醫事機構代碼', '登入Email', '職務'));
                $sheet->setWidth([
                    'A' => 10,
                    'B' => 20,
                    'C' => 20,
                    'D' => 30,
                    'E' => 20,
                ]);
                $sheet->getDefaultStyle();
            });
        })->export('xls');
    }

    /**
     * Get the records of users.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function getUsersData()
    {
        return User::where('role_level', '<', 9)->select('id', 'name', 'hosp_id', 'email', 'bio')->orderBy('created_at', 'DESC')->get()->toArray();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function allUsers(Request $request)
    {
        if ($request->user()->name == 'admin' || $request->user()->role_level == 9) {
            return $this->respondWith(User::where('role_level', '<', 9)->get(), new UserTransformer);
        } else {
            return $this->respondWith(User::where('role_level', '<', 9)->where('hosp_id', $request->user()->hosp_id)->get(), new UserTransformer);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $page
     * @return \Illuminate\Http\Response
     */
    public function showPage(Request $request, $page = 10)
    {
        if ($request->user()->name == 'admin' || $request->user()->role_level == 9) {
            return $this->respondWith(User::where('role_level', '<', 9)
                ->orderBy('created_at', 'DESC')
                ->paginate($page)
                ->appends(['include' => $request->input('include')]), new UserTransformer);
        } else {
            //依醫事機構代碼
            return $this->respondWith(User::where('role_level', '<', 9)
                ->where('hosp_id', $request->user()->hosp_id)
                ->orderBy('created_at', 'DESC')
                ->paginate($page)
                ->appends(['include' => $request->input('include')]), new UserTransformer);
        }
    }

    /**
     * Image conversion.
     *
     * @throws \Exception
     */
    public function convertImage()
    {
        //測試指令
//        $path = '/usr/bin/';
//        $output = exec($path . "phantomjs -v" . ' 2>&1');
//        var_dump($output);
//        dd();

        //測試pdf
//        $conv = new \Anam\PhantomMagick\Converter();
//        // $conv->setBinary('/usr/lib/node_modules/phantomjs2/bin/phantomjs');
//        $conv->setBinary('phantomjs');
//        $options = [
//            'format' => 'A4',
//            'zoomfactor' => 1,
//            'orientation' => 'portrait',
//            'margin' => '1cm'
//        ];
//        $conv->source('http://google.com')
//            ->toPdf($options)
//            ->save('google.pdf');
//        ->addPage('https://dmclinicyu.com/privacy.html')

        //測試png
//        $conv = new \Anam\PhantomMagick\Converter();
//        $conv->source('http://google.com')
//            ->toPng()
//            ->save('google.png');

        //測試jpg
        $conv = new \Anam\PhantomMagick\Converter();
//        $conv->source('http://google.com')
//            ->toJpg()
//            ->save('google.jpg');
        $options = [
            'width' => 1280,
            'height' => 1280,
            'quality' => 100
        ];
        $conv->source('https://dmclinicyu.com')
            ->toJpg($options)
            ->download('dmclinicyu.jpg');
//            ->download('dmclinicyu.jpg', true);
//            ->save('dmclinicyu.jpg');

//        $conv = new Converter;
//        $conv->addPage('<html><body><h1>Welcome to PhantomMagick</h1></body></html>')
//            ->addPage('https://www.facebook.com/')
//            ->toJpg()
//            ->download('images.jpg', true);

        return redirect()->back();
    }

}
