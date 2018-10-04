<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Basis;
// use App\BasisCache;
use App\BasisT1dm;
use Illuminate\Http\Request;
use App\Transformers\BasisT1dmTransformer;
use App\Transformers\BasisT1dm1Transformer;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BasisT1dmController extends ApiController
{
    protected $user;
    protected $ip;
    protected $uid;
    protected $useragent;

    /**
     * BasisT1dmController constructor.
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
        return $this->respondWith(BasisT1dm::all(), new BasisT1dmTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Response
     */
    public function show($id)
    {
        $basist1dm = BasisT1dm::where('id', $id)->first();

        return $this->respondWith($basist1dm, new BasisT1dmTransformer);
    }

    public function showPid($pid)
    {
        $basist1dm = BasisT1dm::where('pid', $pid)->first();

        return $this->respondWith($basist1dm, new BasisT1dmTransformer);
    }

    public function showPid1($pid)
    {
        $basist1dm = BasisT1dm::where('pid', $pid)->first();

        return $this->respondWith($basist1dm, new BasisT1dm1Transformer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $basist1dm = BasisT1dm::where('id', $id)->first();

        return $this->respondWith($basist1dm, new BasisT1dmTransformer);
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

        // 尋找 BasisT1dm[pid]
        $basist1dm = BasisT1dm::where('pid', $request->pid)->first();
        if ($basist1dm) {
            $this->validate($request, [
                'pid' => 'required|max:10|unique:basis_t1dm',
            ]);
        }

        // 尋找 Basis[id]
        $basis = Basis::where('pid', $request->pid)->first();
        // 新增 T1DM
        $basist1dm = new BasisT1dm;
        $basist1dm->basis_id = $basis->id;
        $basist1dm->pid = $request->pid;
        $basist1dm->log_date = date('Y-m-d');
        $basist1dm->fall_ill_year = "不詳";
        $basist1dm->fall_ill_month = "不詳";
        $basist1dm->symptom = json_encode(["無症狀"], JSON_UNESCAPED_UNICODE);
        $basist1dm->treatment = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $basist1dm->medication = json_encode(["Metformin"], JSON_UNESCAPED_UNICODE);
        $basist1dm->medication_year = "不詳";
        $basist1dm->medication_month = "不詳";
        $basist1dm->injection_year = "不詳";
        $basist1dm->injection_month = "不詳";
        $basist1dm->glp1_year = "不詳";
        $basist1dm->glp1_month = "不詳";
        $basist1dm->glp1 = "無";
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => json_encode($ftfrom)
//        ]);
        $basist1dm->continuous = "無";
        $basist1dm->comorbidity = json_encode(["無共病"], JSON_UNESCAPED_UNICODE);
        $basist1dm->lung = "";
        $basist1dm->liver = "";
        $basist1dm->mental = "";
        $basist1dm->gestation = "不詳";
        $basist1dm->stopgestation = "不詳";
        $basist1dm->complication = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $basist1dm->heartdisease = json_encode([], JSON_UNESCAPED_UNICODE);
        $basist1dm->blindness = "";
        $basist1dm->dialysis = "";
        $basist1dm->amputation = "";
        $basist1dm->fami_medic_his = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $basist1dm->relatives = json_encode([], JSON_UNESCAPED_UNICODE);
        $basist1dm->hypertension = json_encode([], JSON_UNESCAPED_UNICODE);
        $basist1dm->cardiovascular = json_encode([], JSON_UNESCAPED_UNICODE);
        $basist1dm->stroke = json_encode([], JSON_UNESCAPED_UNICODE);
        $basist1dm->activity = "無";
        $basist1dm->education = "不詳";
        $basist1dm->worktime = "固定";
        $ftfrom = array("HH" => "08", "mm" => "00");
        $ftto = array("HH" => "17", "mm" => "00");
        $ft = array("HH" => "", "mm" => "");
        $basist1dm->fixedtime_from = json_encode($ftfrom, JSON_UNESCAPED_UNICODE);
        $basist1dm->fixedtime_to = json_encode($ftto, JSON_UNESCAPED_UNICODE);
        $basist1dm->dayshift_from = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basist1dm->dayshift_to = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basist1dm->middleshift_from = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basist1dm->middleshift_to = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basist1dm->nightshift_from = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basist1dm->nightshift_to = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basist1dm->affectlearning = json_encode([], JSON_UNESCAPED_UNICODE);
        $basist1dm->livingcondition = "獨居";
        $basist1dm->nursinghome = "";
        $basist1dm->selfcare = "完全獨立";
        $basist1dm->sport = "無";
        $basist1dm->glucometer = "無";
        $basist1dm->g6pd = "無";
        $basist1dm->g6pd_year = "不詳";
        $basist1dm->g6pd_month = "不詳";
        $basist1dm->closed = "無";
        $basist1dm->closed_year = "不詳";
        $basist1dm->closed_month = "不詳";
        $basist1dm->closedcause = "";
        $basist1dm->save();

        LogsController::saveLog('basis_t1dm', 'store(保存)', $this->ip, $this->useragent);
        return $this->respondWith($basist1dm, new BasisT1dm1Transformer);
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

        $basist1dm = BasisT1dm::findOrFail($id);
        $basist1dm->log_date = $request->log_date;
        $basist1dm->fall_ill_year = $request->fall_ill_year;
        $basist1dm->fall_ill_month = $request->fall_ill_month;
        $basist1dm->symptom = json_encode($request->symptom, JSON_UNESCAPED_UNICODE);
        $basist1dm->treatment = json_encode($request->treatment, JSON_UNESCAPED_UNICODE);
        $basist1dm->medication = json_encode($request->medication, JSON_UNESCAPED_UNICODE);
        $basist1dm->medication_year = $request->medication_year;
        $basist1dm->medication_month = $request->medication_month;
        $basist1dm->injection_year = $request->injection_year;
        $basist1dm->injection_month = $request->injection_month;
        $basist1dm->insulin = $request->insulin;
        $basist1dm->longterm = $request->longterm;
        $basist1dm->ineffect = $request->ineffect;
        $basist1dm->shortacting = $request->shortacting;
        $basist1dm->quickeffect = $request->quickeffect;
        $basist1dm->glp1_year = $request->glp1_year;
        $basist1dm->glp1_month = $request->glp1_month;
        $basist1dm->glp1 = $request->glp1;
        $basist1dm->continuous = $request->continuous;
        $basist1dm->comorbidity = json_encode($request->comorbidity, JSON_UNESCAPED_UNICODE);
        $basist1dm->lung = $request->lung;
        $basist1dm->liver = $request->liver;
        $basist1dm->mental = $request->mental;
        $basist1dm->cancer = $request->cancer;
        $basist1dm->gestation = $request->gestation;
        $basist1dm->stopgestation = $request->stopgestation;
        $basist1dm->complication = json_encode($request->complication, JSON_UNESCAPED_UNICODE);
        $basist1dm->heartdisease = json_encode($request->heartdisease, JSON_UNESCAPED_UNICODE);
        $basist1dm->blindness = $request->blindness;
        $basist1dm->dialysis = $request->dialysis;
        $basist1dm->amputation = $request->amputation;
        $basist1dm->fami_medic_his = json_encode($request->fami_medic_his, JSON_UNESCAPED_UNICODE);
        $basist1dm->relatives = json_encode($request->relatives, JSON_UNESCAPED_UNICODE);
        $basist1dm->hypertension = json_encode($request->hypertension, JSON_UNESCAPED_UNICODE);
        $basist1dm->cardiovascular = json_encode($request->cardiovascular, JSON_UNESCAPED_UNICODE);
        $basist1dm->stroke = json_encode($request->stroke, JSON_UNESCAPED_UNICODE);
        $basist1dm->activity = $request->activity;
        $basist1dm->education = $request->education;
        $basist1dm->occupation = $request->occupation;
        $basist1dm->worktime = $request->worktime;
        $basist1dm->fixedtime_from = json_encode($request->fixedtime_from, JSON_UNESCAPED_UNICODE);
        $basist1dm->fixedtime_to = json_encode($request->fixedtime_to, JSON_UNESCAPED_UNICODE);
        $basist1dm->dayshift_from = json_encode($request->dayshift_from, JSON_UNESCAPED_UNICODE);
        $basist1dm->dayshift_to = json_encode($request->dayshift_to, JSON_UNESCAPED_UNICODE);
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => json_encode($request->dayshift_from)
//        ]);
        $basist1dm->middleshift_from = json_encode($request->middleshift_from, JSON_UNESCAPED_UNICODE);
        $basist1dm->middleshift_to = json_encode($request->middleshift_to, JSON_UNESCAPED_UNICODE);
        $basist1dm->nightshift_from = json_encode($request->nightshift_from, JSON_UNESCAPED_UNICODE);
        $basist1dm->nightshift_to = json_encode($request->nightshift_to, JSON_UNESCAPED_UNICODE);
        $basist1dm->affectlearning = json_encode($request->affectlearning, JSON_UNESCAPED_UNICODE);
        $basist1dm->livingcondition = $request->livingcondition;
        $basist1dm->nursinghome = $request->nursinghome;
        $basist1dm->careunit =  $request->careunit;
        $basist1dm->livingtel =  $request->livingtel;
        $basist1dm->selfcare = $request->selfcare;
        $basist1dm->caregiver = $request->caregiver;
        $basist1dm->caretel = $request->caretel;
        $basist1dm->sport = $request->sport;
        $basist1dm->sporta1 = $request->sporta1;
        $basist1dm->sporta2 = $request->sporta2;
        $basist1dm->sportb1 = $request->sportb1;
        $basist1dm->sportb2 = $request->sportb2;
        $basist1dm->sportc1 = $request->sportc1;
        $basist1dm->sportc2 = $request->sportc2;
        $basist1dm->sportsum = $request->sportsum;
        $basist1dm->glucometer = $request->glucometer;
        $basist1dm->glucometerfrequency = $request->glucometerfrequency;
        $basist1dm->g6pd = $request->g6pd;
        $basist1dm->g6pd_year = $request->g6pd_year;
        $basist1dm->g6pd_month = $request->g6pd_month;
        $basist1dm->closed = $request->closed;
        //需圈點 有, 才記錄 結案日期
        if($request->closed == "有") {
            $basist1dm->close_date = $request->close_date;
        } else {
            $basist1dm->close_date = null;
        }
        $basist1dm->closed_year = $request->closed_year;
        $basist1dm->closed_month = $request->closed_month;
        $basist1dm->closedcause = $request->closedcause;
        $basist1dm->closedreason = $request->closedreason;
        $basist1dm->save();

        LogsController::saveLog('basis_t1dm', 'update(更新)', $this->ip, $this->useragent);
        return $this->respondWith($basist1dm, new BasisT1dmTransformer);
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
        $basist1dm = BasisT1dm::findOrFail($id);
        $basist1dm->delete();

        LogsController::saveLog('basis_t1dm', 'destroy(删除)', $this->ip, $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '刪除使用者的基本資料(T1DM)成功!!'
        ]);
    }

}
