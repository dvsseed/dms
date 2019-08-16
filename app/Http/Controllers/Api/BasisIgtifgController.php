<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Basis;
// use App\BasisCache;
use App\BasisIgtifg;
use Illuminate\Http\Request;
use App\Transformers\BasisIgtifgTransformer;
use App\Transformers\BasisIgtifg1Transformer;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BasisIgtifgController extends ApiController
{
    protected $user;
    protected $ip;
    protected $uid;
    protected $useragent;

    /**
     * BasisIgtifgController constructor.
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->middleware('timeout');

//        $this->user = $request->user();
        $this->user = Auth::user();
        $this->ip = $request->ip();
//        $this->uid = $request->user()->id;
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
        return $this->respondWith(BasisIgtifg::all(), new BasisIgtifgTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Response
     */
    public function show($id)
    {
        $basisigtifg = BasisIgtifg::where('id', $id)->first();

        return $this->respondWith($basisigtifg, new BasisIgtifgTransformer);
    }

    public function showPid($pid)
    {
        $basisigtifg = BasisIgtifg::where('pid', $pid)->first();

        return $this->respondWith($basisigtifg, new BasisIgtifgTransformer);
    }

    public function showPid1($pid)
    {
        $basisigtifg = BasisIgtifg::where('pid', $pid)->first();

        return $this->respondWith($basisigtifg, new BasisIgtifg1Transformer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $basisigtifg = BasisIgtifg::where('id', $id)->first();

        return $this->respondWith($basisigtifg, new BasisIgtifgTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => '測試中!!'
//        ]);

        // 更新使用者輸入資料
//        $basiscache = BasisCache::findOrFail(1);
//        $basiscache = BasisCache::find(1);
//        $basiscache = BasisCache::where('user_id', $this->uid)->first();
//        if ($basiscache) {
//            $basiscache->delete();
//        }
//        $basiscache = new BasisCache;
//        $basiscache->id = 1;
//        $basiscache->user_id = $this->uid;
//        $basiscache->pid = $request->pid;
//        $basiscache->birthday = $request->birthday;
//        $basiscache->save();

        // 尋找 BasisIgtifg[pid]
        $basisigtifg = BasisIgtifg::where('pid', $request->pid)->first();
        if ($basisigtifg) {
            $this->validate($request, [
                'pid' => 'required|max:18|unique:basis_igtifg',
            ]);
        }

        // 尋找 Basis[id]
        $basis = Basis::where('pid', $request->pid)->first();
        // 新增 IGTIFG
        $basisigtifg = new BasisIgtifg;
        $basisigtifg->basis_id = $basis->id;
        $basisigtifg->pid = $request->pid;
        $basisigtifg->log_date = date('Y-m-d');
        $basisigtifg->fall_ill_year = "不詳";
        $basisigtifg->fall_ill_month = "不詳";
        $basisigtifg->fall_ill_ii = json_encode(["無"], JSON_UNESCAPED_UNICODE);
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => json_encode($ftfrom)
//        ]);
        $basisigtifg->comorbidity = json_encode(["無共病"], JSON_UNESCAPED_UNICODE);
        $basisigtifg->lung = "";
        $basisigtifg->liver = "";
        $basisigtifg->mental = "";
        $basisigtifg->gestation = "不詳";
        $basisigtifg->stopgestation = "不詳";
        $basisigtifg->fami_medic_his = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $basisigtifg->relatives = json_encode([], JSON_UNESCAPED_UNICODE);
        $basisigtifg->hypertension = json_encode([], JSON_UNESCAPED_UNICODE);
        $basisigtifg->cardiovascular = json_encode([], JSON_UNESCAPED_UNICODE);
        $basisigtifg->stroke = json_encode([], JSON_UNESCAPED_UNICODE);
        $basisigtifg->activity = "無";
        $basisigtifg->education = "不詳";
        $basisigtifg->worktime = "固定";
        $ftfrom = array("HH" => "08", "mm" => "00");
        $ftto = array("HH" => "17", "mm" => "00");
        $ft = array("HH" => "", "mm" => "");
        $basisigtifg->fixedtime_from = json_encode($ftfrom, JSON_UNESCAPED_UNICODE);
        $basisigtifg->fixedtime_to = json_encode($ftto, JSON_UNESCAPED_UNICODE);
        $basisigtifg->dayshift_from = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basisigtifg->dayshift_to = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basisigtifg->middleshift_from = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basisigtifg->middleshift_to = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basisigtifg->nightshift_from = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basisigtifg->nightshift_to = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basisigtifg->affectlearning = json_encode([], JSON_UNESCAPED_UNICODE);
        $basisigtifg->livingcondition = "獨居";
        $basisigtifg->nursinghome = "";
        $basisigtifg->selfcare = "完全獨立";
        $basisigtifg->sport = "無";
        $basisigtifg->closed = "無";
        $basisigtifg->closed_do = "";
        $basisigtifg->closed_year = "不詳";
        $basisigtifg->closed_month = "不詳";
        $basisigtifg->closedcause = "";
        $basisigtifg->save();

        LogsController::saveLog('basis_igtifg', 'store(保存)', $this->ip, $this->useragent);
        return $this->respondWith($basisigtifg, new BasisIgtifg1Transformer);
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
//        $this->validate($request, [
//            'email' => 'required',
//            'password' => 'required',
//        ]);

        $basisigtifg = BasisIgtifg::findOrFail($id);
        $basisigtifg->log_date = $request->log_date;
        $basisigtifg->fall_ill_year = $request->fall_ill_year;
        $basisigtifg->fall_ill_month = $request->fall_ill_month;
        $basisigtifg->fall_ill_ii = json_encode($request->fall_ill_ii, JSON_UNESCAPED_UNICODE);
        $basisigtifg->comorbidity = json_encode($request->comorbidity, JSON_UNESCAPED_UNICODE);
        $basisigtifg->lung = $request->lung;
        $basisigtifg->liver = $request->liver;
        $basisigtifg->mental = $request->mental;
        $basisigtifg->cancer = $request->cancer;
        $basisigtifg->gestation = $request->gestation;
        $basisigtifg->stopgestation = $request->stopgestation;
        $basisigtifg->fami_medic_his = json_encode($request->fami_medic_his, JSON_UNESCAPED_UNICODE);
        $basisigtifg->relatives = json_encode($request->relatives, JSON_UNESCAPED_UNICODE);
        $basisigtifg->hypertension = json_encode($request->hypertension, JSON_UNESCAPED_UNICODE);
        $basisigtifg->cardiovascular = json_encode($request->cardiovascular, JSON_UNESCAPED_UNICODE);
        $basisigtifg->stroke = json_encode($request->stroke, JSON_UNESCAPED_UNICODE);
        $basisigtifg->activity = $request->activity;
        $basisigtifg->education = $request->education;
        $basisigtifg->occupation = $request->occupation;
        $basisigtifg->worktime = $request->worktime;
        $basisigtifg->fixedtime_from = json_encode($request->fixedtime_from, JSON_UNESCAPED_UNICODE);
        $basisigtifg->fixedtime_to = json_encode($request->fixedtime_to, JSON_UNESCAPED_UNICODE);
        $basisigtifg->dayshift_from = json_encode($request->dayshift_from, JSON_UNESCAPED_UNICODE);
        $basisigtifg->dayshift_to = json_encode($request->dayshift_to, JSON_UNESCAPED_UNICODE);
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => json_encode($request->dayshift_from)
//        ]);
        $basisigtifg->middleshift_from = json_encode($request->middleshift_from, JSON_UNESCAPED_UNICODE);
        $basisigtifg->middleshift_to = json_encode($request->middleshift_to, JSON_UNESCAPED_UNICODE);
        $basisigtifg->nightshift_from = json_encode($request->nightshift_from, JSON_UNESCAPED_UNICODE);
        $basisigtifg->nightshift_to = json_encode($request->nightshift_to, JSON_UNESCAPED_UNICODE);
        $basisigtifg->affectlearning = json_encode($request->affectlearning, JSON_UNESCAPED_UNICODE);
        $basisigtifg->livingcondition = $request->livingcondition;
        $basisigtifg->nursinghome = $request->nursinghome;
        $basisigtifg->careunit =  $request->careunit;
        $basisigtifg->livingtel =  $request->livingtel;
        $basisigtifg->selfcare = $request->selfcare;
        $basisigtifg->caregiver = $request->caregiver;
        $basisigtifg->caretel = $request->caretel;
        $basisigtifg->sport = $request->sport;
        $basisigtifg->sporta1 = $request->sporta1;
        $basisigtifg->sporta2 = $request->sporta2;
        $basisigtifg->sportb1 = $request->sportb1;
        $basisigtifg->sportb2 = $request->sportb2;
        $basisigtifg->sportc1 = $request->sportc1;
        $basisigtifg->sportc2 = $request->sportc2;
        $basisigtifg->sportsum = $request->sportsum;
        $basisigtifg->closed = $request->closed;
        //需圈點 有, 才記錄 結案日期
        if($request->closed == "有") {
            $basisigtifg->close_date = $request->close_date;
        } else {
            $basisigtifg->close_date = null;
        }
        $basisigtifg->closed_do = $request->closed_do;
        $basisigtifg->closed_year = $request->closed_year;
        $basisigtifg->closed_month = $request->closed_month;
        $basisigtifg->closedcause = $request->closedcause;
        $basisigtifg->closedreason = $request->closedreason;
        $basisigtifg->save();

        LogsController::saveLog('basis_igtifg', 'update(更新)', $this->ip, $this->useragent);
        return $this->respondWith($basisigtifg, new BasisIgtifgTransformer);
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
        $basisigtifg = BasisIgtifg::findOrFail($id);
        $basisigtifg->delete();

        LogsController::saveLog('basis_igtifg', 'destroy(删除)', $this->ip, $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '刪除使用者的基本資料(IGTIFG)成功!!'
        ]);
    }

}
