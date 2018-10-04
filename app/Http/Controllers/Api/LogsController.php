<?php

namespace App\Http\Controllers\Api;

use App\Log;
use Auth;
use DB;
// use BrowserDetect;
use App\Transformers\LogTransformer;
use Illuminate\Http\Request;

use App\Http\Requests;

class LogsController extends ApiController
{
//    protected $user;

    /**
     * UsersController constructor.
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->middleware('timeout');
//        $this->user = $request->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->name == 'admin' || $request->user()->role_level == 9) {
            $result = DB::table('logs')
                ->leftJoin('users', 'logs.user_id', '=', 'users.id')
                ->select('logs.id', 'logs.table', 'logs.action', 'logs.user_id', 'logs.ip', 'logs.useragent', 'users.name', 'users.email', 'logs.updated_at')
                ->orderBy('logs.updated_at', 'DESC')
                ->paginate(10);
//        return $this->respondWith($result, new LogTransformer);
            return $result;
        } else {
            //依醫事機構代碼
            $result = DB::table('logs')
                ->leftJoin('users', 'logs.user_id', '=', 'users.id')
                ->select('logs.id', 'logs.table', 'logs.action', 'logs.user_id', 'logs.ip', 'logs.useragent', 'users.name', 'users.email', 'logs.updated_at')
                ->where('users.hosp_id', $request->user()->hosp_id)
                ->orderBy('logs.updated_at', 'DESC')
                ->paginate(10);
            return $result;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Save the specified resource from storage.
     *
     * @param  string  $table
     * @param  string  $action
     * @param  string  $ip
     * @param  string  $useragent
     * @return \Illuminate\Http\Response
     */
    public static function saveLog($table, $action, $ip, $useragent)
    {
        $log = new Log;
        $log->table = $table;
        $log->action = $action;
        $log->user_id = Auth::user()->id;
        $log->ip = $ip;
        $log->useragent = $useragent;

        $log->save();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function allLogs(Request $request)
    {
        return $this->respondWith(Log::all(), new LogTransformer);
    }

}
