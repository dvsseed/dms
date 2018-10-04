<?php

namespace App\Http\Controllers\Api;

use Auth;
use DB;
use App\Menu;
use App\UserMenu;
use App\Transformers\MenuTransformer;
use App\Transformers\UserMenuTransformer;
use Illuminate\Http\Request;

use App\Http\Requests;

class MenusController extends ApiController
{
    protected $user;
    protected $useragent;

    /**
     * CategoriesController constructor.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->middleware('timeout');
//        $this->user = $request->user();
        $this->user = Auth::user();
        $this->useragent = $request->header('User-Agent');
    }

    /**
     * Display the specified resource.
     * @return \Response
     */
    public function show()
    {
//        return $this->respondWith($this->user, new UserTransformer);
        return $this->respondWith(Menu::all(), new MenuTransformer);
    }

    /**
     * Display the specified resource.
     * @return \Response
     */
    public function showMenu(Request $request)
    {
        //return $this->respondWith(User::all()->menu, new MenuTransformer);
        if ($request->user()->name == 'admin' || $request->user()->role_level == 9) {
//            return $this->respondWith(User::where('role_level', '<', 9)->orderBy('created_at', 'DESC')->menu, new UserMenuTransformer);
            $usermenu = DB::table('user_menu')
                ->leftJoin('users', 'users.id', '=', 'user_menu.user_id')
                ->select('user_menu.id','user_id','email','username','menu1','menu2','menu3','menu4','menu5','menu6','menu7','menu8','role_level')
                ->where('role_level', '<', 9)
                ->orderBy('users.created_at', 'DESC')
                ->paginate(10);
//            $usermenu = UserMenu::all();
//            return $this->respondWith($usermenu, new UserMenuTransformer);
        } else {
            //依醫事機構代碼
//            return $this->respondWith(User::where('role_level', '<', 9)->where('hosp_id', $request->user()->hosp_id), new UserMenuTransformer);
            $usermenu = DB::table('user_menu')
                ->leftJoin('users', 'users.id', '=', 'user_menu.user_id')
                ->select('user_menu.id','user_id','email','username','menu1','menu2','menu3','menu4','menu5','menu6','menu7','menu8','role_level')
                ->where('role_level', '<', 9)
                ->where('hosp_id', $request->user()->hosp_id)
                ->orderBy('users.created_at', 'DESC')
                ->paginate(10);
        }
        return $usermenu;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request)
    {
        $usermenu = UserMenu::findOrFail($request->id);
        $usermenu->menu1 = $request->menu1;
        $usermenu->menu2 = $request->menu2;
        $usermenu->menu3 = $request->menu3;
        $usermenu->menu4 = $request->menu4;
        $usermenu->menu5 = $request->menu5;
        $usermenu->menu6 = $request->menu6;
        $usermenu->menu7 = $request->menu7;
        $usermenu->menu8 = $request->menu8;
        $usermenu->save();

        LogsController::saveLog('user_menu', 'update(更新)', $request->ip(), $this->useragent);
        return $this->respondWith($usermenu, new UserMenuTransformer);
    }

    /**
     * Fetch the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function fetch()
    {
        $usermenu = UserMenu::where("user_id", $this->user->id)->first();

        return $this->respondWith($usermenu, new UserMenuTransformer);
    }
}
