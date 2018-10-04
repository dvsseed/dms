<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Basis;
use App\BasisCache;
use App\BasisT2dm;
use Illuminate\Http\Request;
use App\Transformers\BasisT2dmTransformer;
use App\Transformers\BasisT2dm1Transformer;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BasisT2dmController extends ApiController
{
    protected $user;
    protected $ip;
    protected $uid;
    protected $useragent;

    /**
     * BasisT2dmController constructor.
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
        return $this->respondWith(BasisT2dm::all(), new BasisT2dmTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Response
     */
    public function show($id)
    {
        $basist2dm = BasisT2dm::where('id', $id)->first();

        return $this->respondWith($basist2dm, new BasisT2dmTransformer);
    }

    public function showPid($pid)
    {
        $basist2dm = BasisT2dm::where('pid', $pid)->first();

        return $this->respondWith($basist2dm, new BasisT2dmTransformer);
    }

    public function showPid1($pid)
    {
        $basist2dm = BasisT2dm::where('pid', $pid)->first();

        return $this->respondWith($basist2dm, new BasisT2dm1Transformer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $basist2dm = BasisT2dm::where('id', $id)->first();

        return $this->respondWith($basist2dm, new BasisT2dmTransformer);
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

        // 尋找 BasisT2dm[pid]
        $basist2dm = BasisT2dm::where('pid', $request->pid)->first();
        if ($basist2dm) {
            $this->validate($request, [
                'pid' => 'required|max:10|unique:basis_t2dm',
            ]);
        }

        // 尋找 Basis[id]
        $basis = Basis::where('pid', $request->pid)->first();
        // 新增 T2DM
        $basist2dm = new BasisT2dm;
        $basist2dm->basis_id = $basis->id;
        $basist2dm->pid = $request->pid;
        $basist2dm->log_date = date('Y-m-d');
        $basist2dm->fall_ill_year = "不詳";
        $basist2dm->fall_ill_month = "不詳";
        $basist2dm->symptom = json_encode(["無症狀"], JSON_UNESCAPED_UNICODE);
        $basist2dm->treatment = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $basist2dm->medication = json_encode(["Metformin"], JSON_UNESCAPED_UNICODE);
        $basist2dm->medication_year = "不詳";
        $basist2dm->medication_month = "不詳";
        $basist2dm->injection_year = "不詳";
        $basist2dm->injection_month = "不詳";
        $basist2dm->glp1_year = "不詳";
        $basist2dm->glp1_month = "不詳";
        $basist2dm->glp1 = "無";
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => json_encode($ftfrom)
//        ]);
        $basist2dm->continuous = "無";
        $basist2dm->comorbidity = json_encode(["無共病"], JSON_UNESCAPED_UNICODE);
        $basist2dm->lung = "";
        $basist2dm->liver = "";
        $basist2dm->mental = "";
        $basist2dm->gestation = "不詳";
        $basist2dm->stopgestation = "不詳";
        $basist2dm->complication = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $basist2dm->heartdisease = json_encode([], JSON_UNESCAPED_UNICODE);
        $basist2dm->blindness = "";
        $basist2dm->dialysis = "";
        $basist2dm->amputation = "";
        $basist2dm->fami_medic_his = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $basist2dm->relatives = json_encode([], JSON_UNESCAPED_UNICODE);
        $basist2dm->hypertension = json_encode([], JSON_UNESCAPED_UNICODE);
        $basist2dm->cardiovascular = json_encode([], JSON_UNESCAPED_UNICODE);
        $basist2dm->stroke = json_encode([], JSON_UNESCAPED_UNICODE);
        $basist2dm->activity = "無";
        $basist2dm->education = "不詳";
        $basist2dm->worktime = "固定";
        $ftfrom = array("HH" => "08", "mm" => "00");
        $ftto = array("HH" => "17", "mm" => "00");
        $ft = array("HH" => "", "mm" => "");
        $basist2dm->fixedtime_from = json_encode($ftfrom, JSON_UNESCAPED_UNICODE);
        $basist2dm->fixedtime_to = json_encode($ftto, JSON_UNESCAPED_UNICODE);
        $basist2dm->dayshift_from = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basist2dm->dayshift_to = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basist2dm->middleshift_from = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basist2dm->middleshift_to = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basist2dm->nightshift_from = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basist2dm->nightshift_to = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basist2dm->affectlearning = json_encode([], JSON_UNESCAPED_UNICODE);
        $basist2dm->livingcondition = "獨居";
        $basist2dm->nursinghome = "";
        $basist2dm->selfcare = "完全獨立";
        $basist2dm->sport = "無";
        $basist2dm->glucometer = "無";
        $basist2dm->g6pd = "無";
        $basist2dm->g6pd_year = "不詳";
        $basist2dm->g6pd_month = "不詳";
        $basist2dm->closed = "無";
        $basist2dm->closed_year = "不詳";
        $basist2dm->closed_month = "不詳";
        $basist2dm->closedcause = "";
        $basist2dm->save();

        LogsController::saveLog('basis_t2dm', 'store(保存)', $this->ip, $this->useragent);
        return $this->respondWith($basist2dm, new BasisT2dm1Transformer);
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

        $basist2dm = BasisT2dm::findOrFail($id);
        $basist2dm->log_date = $request->log_date;
        $basist2dm->fall_ill_year = $request->fall_ill_year;
        $basist2dm->fall_ill_month = $request->fall_ill_month;
        $basist2dm->symptom = json_encode($request->symptom, JSON_UNESCAPED_UNICODE);
        $basist2dm->treatment = json_encode($request->treatment, JSON_UNESCAPED_UNICODE);
        $basist2dm->medication = json_encode($request->medication, JSON_UNESCAPED_UNICODE);
        $basist2dm->medication_year = $request->medication_year;
        $basist2dm->medication_month = $request->medication_month;
        $basist2dm->injection_year = $request->injection_year;
        $basist2dm->injection_month = $request->injection_month;
        $basist2dm->insulin = $request->insulin;
        $basist2dm->longterm = $request->longterm;
        $basist2dm->ineffect = $request->ineffect;
        $basist2dm->shortacting = $request->shortacting;
        $basist2dm->quickeffect = $request->quickeffect;
        $basist2dm->glp1_year = $request->glp1_year;
        $basist2dm->glp1_month = $request->glp1_month;
        $basist2dm->glp1 = $request->glp1;
        $basist2dm->continuous = $request->continuous;
        $basist2dm->comorbidity = json_encode($request->comorbidity, JSON_UNESCAPED_UNICODE);
        $basist2dm->lung = $request->lung;
        $basist2dm->liver = $request->liver;
        $basist2dm->mental = $request->mental;
        $basist2dm->cancer = $request->cancer;
        $basist2dm->gestation = $request->gestation;
        $basist2dm->stopgestation = $request->stopgestation;
        $basist2dm->complication = json_encode($request->complication, JSON_UNESCAPED_UNICODE);
        $basist2dm->heartdisease = json_encode($request->heartdisease, JSON_UNESCAPED_UNICODE);
        $basist2dm->blindness = $request->blindness;
        $basist2dm->dialysis = $request->dialysis;
        $basist2dm->amputation = $request->amputation;
        $basist2dm->fami_medic_his = json_encode($request->fami_medic_his, JSON_UNESCAPED_UNICODE);
        $basist2dm->relatives = json_encode($request->relatives, JSON_UNESCAPED_UNICODE);
        $basist2dm->hypertension = json_encode($request->hypertension, JSON_UNESCAPED_UNICODE);
        $basist2dm->cardiovascular = json_encode($request->cardiovascular, JSON_UNESCAPED_UNICODE);
        $basist2dm->stroke = json_encode($request->stroke, JSON_UNESCAPED_UNICODE);
        $basist2dm->activity = $request->activity;
        $basist2dm->education = $request->education;
        $basist2dm->occupation = $request->occupation;
        $basist2dm->worktime = $request->worktime;
        $basist2dm->fixedtime_from = json_encode($request->fixedtime_from, JSON_UNESCAPED_UNICODE);
        $basist2dm->fixedtime_to = json_encode($request->fixedtime_to, JSON_UNESCAPED_UNICODE);
        $basist2dm->dayshift_from = json_encode($request->dayshift_from, JSON_UNESCAPED_UNICODE);
        $basist2dm->dayshift_to = json_encode($request->dayshift_to, JSON_UNESCAPED_UNICODE);
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => json_encode($request->dayshift_from)
//        ]);
        $basist2dm->middleshift_from = json_encode($request->middleshift_from, JSON_UNESCAPED_UNICODE);
        $basist2dm->middleshift_to = json_encode($request->middleshift_to, JSON_UNESCAPED_UNICODE);
        $basist2dm->nightshift_from = json_encode($request->nightshift_from, JSON_UNESCAPED_UNICODE);
        $basist2dm->nightshift_to = json_encode($request->nightshift_to, JSON_UNESCAPED_UNICODE);
        $basist2dm->affectlearning = json_encode($request->affectlearning, JSON_UNESCAPED_UNICODE);
        $basist2dm->livingcondition = $request->livingcondition;
        $basist2dm->nursinghome = $request->nursinghome;
        $basist2dm->careunit =  $request->careunit;
        $basist2dm->livingtel =  $request->livingtel;
        $basist2dm->selfcare = $request->selfcare;
        $basist2dm->caregiver = $request->caregiver;
        $basist2dm->caretel = $request->caretel;
        $basist2dm->sport = $request->sport;
        $basist2dm->sporta1 = $request->sporta1;
        $basist2dm->sporta2 = $request->sporta2;
        $basist2dm->sportb1 = $request->sportb1;
        $basist2dm->sportb2 = $request->sportb2;
        $basist2dm->sportc1 = $request->sportc1;
        $basist2dm->sportc2 = $request->sportc2;
        $basist2dm->sportsum = $request->sportsum;
        $basist2dm->glucometer = $request->glucometer;
        $basist2dm->glucometerfrequency = $request->glucometerfrequency;
        $basist2dm->g6pd = $request->g6pd;
        $basist2dm->g6pd_year = $request->g6pd_year;
        $basist2dm->g6pd_month = $request->g6pd_month;
        $basist2dm->closed = $request->closed;
        //需圈點 有, 才記錄 結案日期
        if($request->closed == "有") {
            $basist2dm->close_date = $request->close_date;
        } else {
            $basist2dm->close_date = null;
        }
        $basist2dm->closed_year = $request->closed_year;
        $basist2dm->closed_month = $request->closed_month;
        $basist2dm->closedcause = $request->closedcause;
        $basist2dm->closedreason = $request->closedreason;
        $basist2dm->save();

        LogsController::saveLog('basis_t2dm', 'update(更新)', $this->ip, $this->useragent);
        return $this->respondWith($basist2dm, new BasisT2dmTransformer);
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
        $basist2dm = BasisT2dm::findOrFail($id);
        $basist2dm->delete();

        LogsController::saveLog('basis_t2dm', 'destroy(删除)', $this->ip, $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '刪除使用者的基本資料(T2DM)成功!!'
        ]);
    }

}
