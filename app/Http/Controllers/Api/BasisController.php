<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Basis;
use App\BasisCache;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;
use App\Transformers\BasisTransformer;
use App\Transformers\Basis1Transformer;
use Excel;
use DB;
use Alert;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BasisController extends ApiController
{
    protected $user;
    protected $ip;
    protected $hospid;
    protected $uid;
    protected $useragent;

    /**
     * BasisController constructor.
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->middleware('timeout');

//        $this->user = $request->user();
        $this->user = Auth::user();
        $this->ip = $request->ip();
//        $this->hospid = $request->user()->hosp_id;
//        $this->uid = $request->user()->id;
        $this->hospid = Auth::user()->hosp_id;
        $this->uid = Auth::user()->id;
        $this->useragent = $request->header('User-Agent');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->respondWith(Basis::all(), new BasisTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Response
     */
    public function show($id)
    {
        $basis = Basis::where('id', $id)->first();

        return $this->respondWith($basis, new BasisTransformer);
    }

    public function showPid($pid)
    {
        $basis = Basis::where('pid', $pid)->first();

        return $this->respondWith($basis, new BasisTransformer);
    }

    public function showPid1($pid)
    {
        $basis = Basis::where('pid', $pid)->first();

        Alert::success('You have successfully logged in')->flash();
        return $this->respondWith($basis, new Basis1Transformer);
    }

    public function showPid2($pid)
    {
        $basis = DB::table('basis')
            ->leftJoin('tracks', 'tracks.pid', '=', 'basis.pid')
            ->select('basis.pid', 'basis.name', 'basis.birthday', 'tracks.bloodsugar_source', 'tracks.start_date', 'tracks.end_date')
            ->where('basis.pid', '=', $pid)
            ->orderBy('tracks.start_date', 'DESC')
            ->take(1)
            ->get();
            //->paginate($page);

        return $basis;
    }

    public function getTrack($pid, $sdate)
    {
        // list info
        // $trcks = DB::select(DB::raw("SELECT track_reason FROM tracks LEFT JOIN checks ON checks.pid=cases.pid WHERE (cases.cure_stage='DM年度' OR cases.cure_stage='CKD+DM複診' OR cases.cure_stage='CKD複診+DM年度') AND CONCAT(LEFT(checks.check_date,4)+1911,'-',SUBSTRING(checks.check_date,6,2),'-',RIGHT(checks.check_date,2))=cases.case_date AND checks.pid IS NOT NULL AND (cases.blood_hba1c IS NULL OR cases.blood_ldl IS NULL OR cases.blood_tg IS NULL OR cases.egfr IS NULL OR cases.blood_creat IS NULL OR cases.blood_hba1c = 0 OR cases.blood_ldl = 0 OR cases.blood_tg = 0 OR cases.egfr = 0 OR cases.blood_creat = 0 OR (cases.urine_micro IS NULL AND cases.upcr IS NULL) OR (cases.urine_micro = 0 AND cases.upcr = 0)) AND cases.hosp_id=? ORDER BY cases.pid DESC"), ["$this->hospid"]);
        $trcks = DB::table('tracks')
            ->select('track_reason', 'monitor_mode', 'medication', 'adjustprinciple_unit', 'adjustprinciple_isf', 'adjustprinciple_u', 'adjustprinciple_ci_morning', 'adjustprinciple_ci_afternoon', 'adjustprinciple_ci_evening', 'dietplan')
            ->where('pid', '=', $pid)
            ->where('start_date', '=', $sdate)
            ->get();

        return $trcks;
    }

    public function getContact($pid, $sdate)
    {
        $cntcts = DB::table('tracks')
            ->select('contact_tel', 'contact_mobile', 'track1_contact', 'track1_tel', 'track1_mobile', 'track2_contact', 'track2_tel', 'track2_mobile', 'track3_contact', 'track3_tel', 'track3_mobile')
            ->where('pid', '=', $pid)
            ->where('start_date', '=', $sdate)
            ->get();

        return $cntcts;
    }

    public function showBy(Request $request, $page = 20)
    {
//        return $this->respondWithArray([
//            'error' => true,
//            'message' => ($name == null)
//        ]);
        $pid = $request->pid;
        $name = $request->name;
        $birthday = $request->birthday;

        if($pid != null && $name != null && $birthday != null) {
            $basis = DB::table('basis')
                ->select('id', 'hosp_id', 'name', 'birthday', 'pid')
                ->where('hosp_id', $this->hospid)
                ->where('pid', 'like', '%' . $pid . '%')
                ->where('name', 'like', '%' .  $name . '%')
                ->where('birthday', 'like', '%' .  $birthday . '%')
                ->orderBy('basis.created_at', 'DESC')
                ->paginate($page);
        } else if ($pid != null && $name != null) {
            $basis = DB::table('basis')
                ->select('id', 'hosp_id', 'name', 'birthday', 'pid')
                ->where('hosp_id', $this->hospid)
                ->where('pid', 'like', '%' .  $pid . '%')
                ->where('name', 'like', '%' .  $name . '%')
                ->orderBy('basis.created_at', 'DESC')
                ->paginate($page);
        } else if ($pid != null && $birthday != null) {
            $basis = DB::table('basis')
                ->select('id', 'hosp_id', 'name', 'birthday', 'pid')
                ->where('hosp_id', $this->hospid)
                ->where('pid', 'like', '%' .  $pid . '%')
                ->where('birthday', 'like', '%' .  $birthday . '%')
                ->orderBy('basis.created_at', 'DESC')
                ->paginate($page);
        } else if ($name != null && $birthday != null) {
            $basis = DB::table('basis')
                ->select('id', 'hosp_id', 'name', 'birthday', 'pid')
                ->where('hosp_id', $this->hospid)
                ->where('name', 'like', '%' .  $name . '%')
                ->where('birthday', 'like', '%' .  $birthday . '%')
                ->orderBy('basis.created_at', 'DESC')
                ->paginate($page);
        } else if ($pid != null) {
            $basis = DB::table('basis')
                ->select('id', 'hosp_id', 'name', 'birthday', 'pid')
                ->where('hosp_id', $this->hospid)
                ->where('pid', 'like', '%' .  $pid . '%')
                ->orderBy('basis.created_at', 'DESC')
                ->paginate($page);
        } else if ($name != null) {
            $basis = DB::table('basis')
                ->select('id', 'hosp_id', 'name', 'birthday', 'pid')
                ->where('hosp_id', $this->hospid)
                ->where('name', 'like', '%' .  $name . '%')
                ->orderBy('basis.created_at', 'DESC')
                ->paginate($page);
        } else if ($birthday != null) {
            $basis = DB::table('basis')
                ->select('id', 'hosp_id', 'name', 'birthday', 'pid')
                ->where('hosp_id', $this->hospid)
                ->where('birthday', 'like', '%' .  $birthday . '%')
                ->orderBy('basis.created_at', 'DESC')
                ->paginate($page);
        }
//        else {
//            $basis = DB::table('basis')
//                ->select('id', 'hosp_id', 'name', 'birthday', 'pid')
//                ->where('hosp_id', $this->hospid)
//                ->orderBy('basis.created_at', 'DESC')
//                ->paginate($page);
//        }

        return $basis;
    }

    public function showPage(Request $request, $page = 20)
    {
        $basis = DB::table('basis')
            ->select('id', 'hosp_id', 'name', 'birthday', 'pid')
            ->where('hosp_id', $this->hospid)
            ->orderBy('basis.created_at', 'DESC')
            ->paginate($page);

        return $basis;
    }

    public function listCase($pid)
    {
//              ->select(DB::raw('"T2DM" as type, log_date as createdate, CASE closed WHEN "歿" THEN DATE_FORMAT(updated_at, "%Y-%m-%d") ELSE "" END as close_date'))
        $t2dm = DB::table('basis_t2dm')
                ->select(DB::raw('"T2DM" as type, log_date as createdate, close_date'))
                ->where('pid', $pid)
                ->orderBy('log_date', 'DESC')
                ->get();
        // $users = DB::table('users')->whereNull('last_name')->union($first)->get();

        return $t2dm;
    }

    public function listData($pid)
    {
        $t2dm = DB::table('basis')
            ->select(DB::raw('pid, name'))
            ->where('pid', $pid)
            ->orderBy('created_at', 'DESC')
            ->get();

        return $t2dm;
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $basis = Basis::where('id', $id)->first();

        return $this->respondWith($basis, new BasisTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 暫存使用者輸入資料
//        $basiscache = BasisCache::findOrFail(1);
//        $basiscache = BasisCache::find(1);
        $basiscache = BasisCache::where('user_id', $this->uid)->first();
        if ($basiscache) {
            $basiscache->delete();
        }
        $basiscache = new BasisCache;
//        $basiscache->id = 1;
        $basiscache->user_id = $this->uid;
        $basiscache->pid = $request->pid;
        $basiscache->birthday = $request->birthday;
        $basiscache->save();

        $this->validate($request, [
            'hosp_id' => 'required',
            'pid' => 'required|max:18|unique:basis',
            'birthday' => 'required'
        ]);

        $basis = new Basis;
        // $basis->fill($request->all());
        $basis->hosp_id = $request->hosp_id;
        $basis->birthday = $request->birthday;
        $pid = $request->pid;
        $basis->pid = $pid;
        $basis->uuid = Uuid::generate()->string;
        //自動判定 性別
        if (substr($pid, 1, 1) == "1") {
            $basis->sex = "男";
        } else {
            $basis->sex = "女";
        }
        $basis->emergency_relationship = "夫妻";
        $basis->language = json_encode(["國語"], JSON_UNESCAPED_UNICODE);
        $basis->drug_allergy = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $basis->save();

//        LogsController::saveLog('basis', 'store(保存)', $request->ip());
        LogsController::saveLog('basis', 'store(保存)', $this->ip, $this->useragent);
        return $this->respondWith($basis, new BasisTransformer);
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
        // for debug
//        return $this->respondWithArray([
//            'success' => false,
//            'message1' => 'language: ' . implode(', ', $request->language),
//            'message2' => 'drug_allergy: ' . join(', ', $request->drug_allergy)
//            'message1' => $request->language,
//            'message2' => $request->drug_allergy,
//            'message3' => serialize($request->language),
//            'message4' => json_encode($request->language, JSON_UNESCAPED_UNICODE),
//            'message5' => json_encode($request->language, true),
//            'message6' => json_decode(json_encode($request->language, JSON_UNESCAPED_UNICODE))
//        ]);

//        $this->validate($request, [
//            'email' => 'required',
//            'password' => 'required',
//        ]);

        $basis = Basis::findOrFail($id);
//        $basis->fill($request->all());
        $basis->medical_number = $request->medical_number;
        $basis->name = $request->name;
        $basis->sex = $request->sex;
        $basis->tel1 = $request->tel1;
        $basis->tel2 = $request->tel2;
        $basis->company_tel = $request->company_tel;
        $basis->mobile1 = $request->mobile1;
        $basis->mobile2 = $request->mobile2;
        $basis->inform_addr = $request->inform_addr;
        $basis->emergency_contact = $request->emergency_contact;
        $basis->emergency_relationship = $request->emergency_relationship;
        $basis->emergency_relationship_other = $request->emergency_relationship_other;
        $basis->emergency_tel = $request->emergency_tel;
        $basis->emergency_mobile = $request->emergency_mobile;
        $language = $request->language;
//        if (strpos($language, ',') !== false) {
            $basis->language = json_encode($language, JSON_UNESCAPED_UNICODE);
//        } else {
//            $basis->language = implode(', ', $language);
//        }
        $basis->language_other = $request->language_other;
        $drug_allergy = $request->drug_allergy;
//        if (strpos($drug_allergy, ',') !== false) {
            $basis->drug_allergy = json_encode($drug_allergy, JSON_UNESCAPED_UNICODE);
//        } else {
//            $basis->drug_allergy = implode(', ', $drug_allergy);
//        }
        $basis->drug_allergy_insulin = $request->drug_allergy_insulin;
        $basis->drug_allergy_other = $request->drug_allergy_other;

        $basis->save();

//        LogsController::saveLog('basis', 'update(更新)', $request->ip());
        LogsController::saveLog('basis', 'update(更新)', $this->ip, $this->useragent);
        return $this->respondWith($basis, new BasisTransformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $basis = Basis::findOrFail($id);
        $basis->delete();

//        LogsController::saveLog('basis', 'destroy(删除)', $request->ip());
        LogsController::saveLog('basis', 'destroy(删除)', $this->ip, $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '刪除使用者的基本資料成功!!'
        ]);
    }

    /**
     * Return View file
     *
     * @var array
     */
    public function importExport()
    {
        return view('importExport');
    }

    /**
     * File Export Code
     *
     * @var array
     */
    public function downloadExcel(Request $request, $type)
    {
        // $data = Basis::get()->toArray(); // 全部的基本資料
        $data = Basis::where('hosp_id', $this->hospid)->get()->toArray(); // 只下載該診所的資料

        LogsController::saveLog('basis', 'download(下載)', $this->ip, $this->useragent);
        return Excel::create('basis_data', function($excel) use ($data) {
            $excel->sheet('basis', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download($type);
//        })->export($type);
    }

    /**
     * Import file into database Code
     *
     * @var array
     */
    public function importExcel(Request $request)
    {
        if($request->hasFile('importfile')) {
            $path = $request->file('importfile')->getRealPath();
            $data = Excel::load($path, function($reader) {
                // Getting all results
                // $results = $reader->get();
                // ->all() is a wrapper for ->get() and will work the same
                // $results = $reader->all();
            }, 'UTF-8')->get();

            if(!empty($data) && $data->count()) {
                foreach ($data->toArray() as $key => $value) {
                    try {
                        if (!empty($value)) {
                            $insert[] = [
                                'hosp_id' => $this->hospid,
                                'uuid' => Uuid::generate()->string,
                                'name' => $value['name'],
                                'birthday' => $value['birthday'],
                                'pid' => $value['pid'],
                                'sex' => (substr($value['pid'], 1, 1) == "1" ? "男" : "女"),
                                'tel1' => $value['tel1'],
                                'inform_addr' => $value['inform_addr'],
                                'emergency_relationship' => "夫妻",
                                'language' => json_encode(["國語"], JSON_UNESCAPED_UNICODE),
                                'drug_allergy' => json_encode(["無"], JSON_UNESCAPED_UNICODE),
                                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                                'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                            ];
                        }
                    } catch (\Illuminate\Database\QueryException $e) {
                        return $this->respondWithArray([
                            'status' => 'error',
                            'message' => '資料庫端發生錯誤!!'
                        ]);
                    } catch (\Exception $e) {
                        return $this->respondWithArray([
                            'status' => 'error',
                            'message' => '欄位名稱不相符!!'
                        ]);
                    }
                }

                if(!empty($insert)) {
                    try {
                        Basis::insert($insert);
                    } catch(\Exception $e) {
                        $errormsg = 'Database error::' . $e->getCode();  // . ': ' . $e->getMessage();
//                        return back()->with('error', $errormsg);
//                        return redirect('/')->with('error', $errormsg);
//                        return back()->with('error', $errormsg);
                        // return Response::json(['status'=>'error', 'message'=>$request->messages();
                        return $this->respondWithArray([
                            'status' => 'error',
                            'message' => $errormsg
                        ]);
                    }
                    // 防止重複資料匯入
//                    Basis::updateorcreate($insert);
//                    $list = Basis::where(array('name' => 'php'))->get()->toArray();
//                    if(empty($list)) {
//                        Basis::create(array('name' => 'php', 'email' => 'php@qq.com'));
//                    } else {
//                        $list->update(['email' => 'php@qq.com']);
//                    }
                    LogsController::saveLog('basis', 'import(匯入)', $this->ip, $this->useragent);
//                    return back()->with('success', '匯入資料成功!!');
                    // return Response::json(['status'=>'success', 'message'=>'Save successful'], 200);
                    return $this->respondWithArray([
                        'status' => 'success',
                        'message' => '匯入資料成功!!'
                    ]);
                }
            }
        }

//        LogsController::saveLog('basis', 'import(匯入)', $this->ip);
//        return back()->with('error', '匯入資料失敗, 請檢查...資料是否有誤!!');
//        return $this->respondWithArray([
//            'error' => true,
//            'message' => '匯入資料失敗, 請檢查...資料是否有誤!!'
//        ]);
        return $this->respondWithArray([
            'status' => 'error',
            'message' => '匯入資料失敗, 請檢查...資料是否有誤!!'
        ]);
//        return redirect('/')->withInput()->withErrors($validator);
//        return redirect()
//            ->back()
//            ->withErrors([$error]);
    }

}
