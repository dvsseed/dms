<?php

namespace App\Http\Controllers\Api;

use Auth;
use DB;
use Excel;
use App\Vpnhistory;
use App\Rawdatavpn;
use Illuminate\Http\Request;
use App\Transformers\VpnhistoryTransformer;
use App\Transformers\RawdatavpnTransformer;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class VpnhistoryController extends ApiController
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
        return $this->respondWith(Vpnhistory::all(), new CasesTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $pid
     * @return \Response
     */
    public function showPage(Request $request, $page = 20)
    {
        $vpnhistory = DB::table('vpnhistory')
            ->select('aid', 'segment', 'branch_code', 'hosp_id', 'pid', 'birthday', 'prsn_id', 'cure_stage', 'case_date', 'iopd_type', 'fami_medic_his', 'f_medic_his', 'm_medic_his', 'c_medic_his', 'stage2_yn', 'diabetes_type', 'fall_ill_year', 'base_chk_date', 'base_tall', 'urine_chk_date', 'base_sbp', 'base_ebp', 'blood_chk_date', 'blood_hba1c', 'blood_ldl', 'blood_ac', 'blood_pc', 'blood_tg', 'urine_micro', 'base_weight', 'urine_routine', 'year_mark', 'foot_chk_left', 'foot_chk_right', 'blood_creat', 'eye_chk8', 'egfr')
            ->where('hosp_id', $this->hospid)
            ->orderBy('created_at', 'DESC')
            ->paginate($page);

        return $vpnhistory;
    }

    public function allVpnhistory(Request $request)
    {
        if ($request->user()->name == 'admin' || $request->user()->role_level == 9) {
            return $this->respondWith(Vpnhistory::all(), new VpnhistoryTransformer);
        } else {
            return $this->respondWith(Vpnhistory::where('hosp_id', $request->user()->hosp_id)->get(), new VpnhistoryTransformer);
        }
    }

    public function allRawdatavpn(Request $request)
    {
        if ($request->user()->name == 'admin' || $request->user()->role_level == 9) {
            return $this->respondWith(Rawdatavpn::all(), new RawdatavpnTransformer);
        } else {
            return $this->respondWith(Rawdatavpn::where('hosp_id', $request->user()->hosp_id)->get(), new RawdatavpnTransformer);
        }
    }

}
