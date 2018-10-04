<?php

namespace App\Http\Controllers\Api;

use Auth;
use DB;
use Excel;
use App\Checks;
use Illuminate\Http\Request;
use App\Transformers\ChecksTransformer;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ChecksController extends ApiController
{
    protected $user;
    protected $ip;
    protected $uid;
    protected $useragent;
    protected $hospid;

    /**
     * BasisT2dmController constructor.
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->middleware('timeout');

//        Auth::user()->id;
//        $this->user = $request->user();
        $this->user = Auth::user();
        $this->ip = $request->ip();
//        $this->uid = $request->user()->id;
//        $this->hospid = $request->user()->hosp_id;
        $this->uid = Auth::user()->id;
        $this->hospid = Auth::user()->hosp_id;
        $this->useragent = $request->header('User-Agent');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->respondWith(Checks::all(), new CasesTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Response
     */
    public function showPage(Request $request, $page = 20)
    {
//        ->leftJoin('basis', 'basis.pid', '=', 'checks.pid')
        $checks = DB::table('checks')
            ->select('id', 'check_date', 'name', 'birthday', 'pid', 'items', 'values')
            ->where('hosp_id', $this->hospid)
            ->orderBy('created_at', 'DESC')
            ->paginate($page);

        return $checks;
    }

    public function allChecks(Request $request)
    {
        if ($request->user()->name == 'admin' || $request->user()->role_level == 9) {
            return $this->respondWith(Checks::all(), new ChecksTransformer);
        } else {
            return $this->respondWith(Checks::where('hosp_id', $request->user()->hosp_id)->get(), new ChecksTransformer);
        }
    }

}
