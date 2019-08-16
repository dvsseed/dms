<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Basis;
// use App\BasisCache;
use App\BasisGdm;
use Illuminate\Http\Request;
use App\Transformers\BasisGdmTransformer;
use App\Transformers\BasisGdm1Transformer;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BasisGdmController extends ApiController
{
    protected $user;
    protected $ip;
    protected $uid;
    protected $useragent;

    /**
     * BasisGdmController constructor.
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
        return $this->respondWith(BasisGdm::all(), new BasisGdmTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Response
     */
    public function show($id)
    {
        $basisgdm = BasisGdm::where('id', $id)->first();

        return $this->respondWith($basisgdm, new BasisGdmTransformer);
    }

    public function showPid($pid)
    {
        $basisgdm = BasisGdm::where('pid', $pid)->first();

        return $this->respondWith($basisgdm, new BasisGdmTransformer);
    }

    public function showPid1($pid)
    {
        $basisgdm = BasisGdm::where('pid', $pid)->first();

        return $this->respondWith($basisgdm, new BasisGdm1Transformer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $basisgdm = BasisGdm::where('id', $id)->first();

        return $this->respondWith($basisgdm, new BasisGdmTransformer);
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

        // 尋找 BasisGdm[pid]
        $basisgdm = BasisGdm::where('pid', $request->pid)->first();
        if ($basisgdm) {
            $this->validate($request, [
                'pid' => 'required|max:18|unique:basis_gdm',
            ]);
        }

        // 尋找 Basis[id]
        $basis = Basis::where('pid', $request->pid)->first();
        // 新增 GDM
        $basisgdm = new BasisGdm;
        $basisgdm->basis_id = $basis->id;
        $basisgdm->pid = $request->pid;
        $basisgdm->log_date = date('Y-m-d');
        $basisgdm->fall_ill_year = "不詳";
        $basisgdm->fall_ill_month = "不詳";
        $basisgdm->currentweeks = "不詳";
        $basisgdm->maternitynumber = "不詳";
        $basisgdm->gestationaldiabetes = "不詳";
        $basisgdm->symptom = json_encode(["無症狀"], JSON_UNESCAPED_UNICODE);
        $basisgdm->treatment = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $basisgdm->medication = json_encode(["Metformin"], JSON_UNESCAPED_UNICODE);
        $basisgdm->medication_year = "不詳";
        $basisgdm->medication_month = "不詳";
        $basisgdm->injection_year = "不詳";
        $basisgdm->injection_month = "不詳";
        $basisgdm->glp1_year = "不詳";
        $basisgdm->glp1_month = "不詳";
        $basisgdm->glp1 = "無";
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => json_encode($ftfrom)
//        ]);
        $basisgdm->comorbidity = json_encode(["無共病"], JSON_UNESCAPED_UNICODE);
        $basisgdm->lung = "";
        $basisgdm->liver = "";
        $basisgdm->mental = "";
        $basisgdm->gestation = "不詳";
        $basisgdm->stopgestation = "不詳";
        $basisgdm->fami_medic_his = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $basisgdm->relatives = json_encode([], JSON_UNESCAPED_UNICODE);
        $basisgdm->hypertension = json_encode([], JSON_UNESCAPED_UNICODE);
        $basisgdm->cardiovascular = json_encode([], JSON_UNESCAPED_UNICODE);
        $basisgdm->stroke = json_encode([], JSON_UNESCAPED_UNICODE);
        $basisgdm->activity = "無";
        $basisgdm->education = "不詳";
        $basisgdm->worktime = "固定";
        $ftfrom = array("HH" => "08", "mm" => "00");
        $ftto = array("HH" => "17", "mm" => "00");
        $ft = array("HH" => "", "mm" => "");
        $basisgdm->fixedtime_from = json_encode($ftfrom, JSON_UNESCAPED_UNICODE);
        $basisgdm->fixedtime_to = json_encode($ftto, JSON_UNESCAPED_UNICODE);
        $basisgdm->dayshift_from = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basisgdm->dayshift_to = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basisgdm->middleshift_from = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basisgdm->middleshift_to = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basisgdm->nightshift_from = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basisgdm->nightshift_to = json_encode($ft, JSON_UNESCAPED_UNICODE);
        $basisgdm->affectlearning = json_encode([], JSON_UNESCAPED_UNICODE);
        $basisgdm->sport = "無";
        $basisgdm->glucometer = "無";
        $basisgdm->g6pd = "無";
        $basisgdm->g6pd_year = "不詳";
        $basisgdm->g6pd_month = "不詳";
        $basisgdm->closed = "無";
        $basisgdm->closed_year = "不詳";
        $basisgdm->closed_month = "不詳";
        $basisgdm->closed_do = "";
        $basisgdm->closedcause = "";
        $basisgdm->save();

        LogsController::saveLog('basis_gdm', 'store(保存)', $this->ip, $this->useragent);
        return $this->respondWith($basisgdm, new BasisGdm1Transformer);
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

        $basisgdm = BasisGdm::findOrFail($id);
        $basisgdm->log_date = $request->log_date;
        $basisgdm->fall_ill_year = $request->fall_ill_year;
        $basisgdm->fall_ill_month = $request->fall_ill_month;
        $basisgdm->currentweeks = $request->currentweeks;
        $basisgdm->maternitynumber = $request->maternitynumber;
        $basisgdm->gestationaldiabetes = $request->gestationaldiabetes;
        $basisgdm->symptom = json_encode($request->symptom, JSON_UNESCAPED_UNICODE);
        $basisgdm->treatment = json_encode($request->treatment, JSON_UNESCAPED_UNICODE);
        $basisgdm->medication = json_encode($request->medication, JSON_UNESCAPED_UNICODE);
        $basisgdm->medication_year = $request->medication_year;
        $basisgdm->medication_month = $request->medication_month;
        $basisgdm->injection_year = $request->injection_year;
        $basisgdm->injection_month = $request->injection_month;
        $basisgdm->insulin = $request->insulin;
        $basisgdm->longterm = $request->longterm;
        $basisgdm->ineffect = $request->ineffect;
        $basisgdm->shortacting = $request->shortacting;
        $basisgdm->quickeffect = $request->quickeffect;
        $basisgdm->glp1_year = $request->glp1_year;
        $basisgdm->glp1_month = $request->glp1_month;
        $basisgdm->glp1 = $request->glp1;
        $basisgdm->comorbidity = json_encode($request->comorbidity, JSON_UNESCAPED_UNICODE);
        $basisgdm->lung = $request->lung;
        $basisgdm->liver = $request->liver;
        $basisgdm->mental = $request->mental;
        $basisgdm->cancer = $request->cancer;
        $basisgdm->gestation = $request->gestation;
        $basisgdm->stopgestation = $request->stopgestation;
        $basisgdm->fami_medic_his = json_encode($request->fami_medic_his, JSON_UNESCAPED_UNICODE);
        $basisgdm->relatives = json_encode($request->relatives, JSON_UNESCAPED_UNICODE);
        $basisgdm->hypertension = json_encode($request->hypertension, JSON_UNESCAPED_UNICODE);
        $basisgdm->cardiovascular = json_encode($request->cardiovascular, JSON_UNESCAPED_UNICODE);
        $basisgdm->stroke = json_encode($request->stroke, JSON_UNESCAPED_UNICODE);
        $basisgdm->activity = $request->activity;
        $basisgdm->education = $request->education;
        $basisgdm->occupation = $request->occupation;
        $basisgdm->worktime = $request->worktime;
        $basisgdm->fixedtime_from = json_encode($request->fixedtime_from, JSON_UNESCAPED_UNICODE);
        $basisgdm->fixedtime_to = json_encode($request->fixedtime_to, JSON_UNESCAPED_UNICODE);
        $basisgdm->dayshift_from = json_encode($request->dayshift_from, JSON_UNESCAPED_UNICODE);
        $basisgdm->dayshift_to = json_encode($request->dayshift_to, JSON_UNESCAPED_UNICODE);
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => json_encode($request->dayshift_from)
//        ]);
        $basisgdm->middleshift_from = json_encode($request->middleshift_from, JSON_UNESCAPED_UNICODE);
        $basisgdm->middleshift_to = json_encode($request->middleshift_to, JSON_UNESCAPED_UNICODE);
        $basisgdm->nightshift_from = json_encode($request->nightshift_from, JSON_UNESCAPED_UNICODE);
        $basisgdm->nightshift_to = json_encode($request->nightshift_to, JSON_UNESCAPED_UNICODE);
        $basisgdm->affectlearning = json_encode($request->affectlearning, JSON_UNESCAPED_UNICODE);
        $basisgdm->sport = $request->sport;
        $basisgdm->sporta1 = $request->sporta1;
        $basisgdm->sporta2 = $request->sporta2;
        $basisgdm->sportb1 = $request->sportb1;
        $basisgdm->sportb2 = $request->sportb2;
        $basisgdm->sportc1 = $request->sportc1;
        $basisgdm->sportc2 = $request->sportc2;
        $basisgdm->sportsum = $request->sportsum;
        $basisgdm->glucometer = $request->glucometer;
        $basisgdm->glucometerfrequency = $request->glucometerfrequency;
        $basisgdm->g6pd = $request->g6pd;
        $basisgdm->g6pd_year = $request->g6pd_year;
        $basisgdm->g6pd_month = $request->g6pd_month;
        $basisgdm->closed = $request->closed;
        //需圈點 有, 才記錄 結案日期
        if($request->closed == "有") {
            $basisgdm->close_date = $request->close_date;
        } else {
            $basisgdm->close_date = null;
        }
        $basisgdm->closed_year = $request->closed_year;
        $basisgdm->closed_month = $request->closed_month;
        $basisgdm->closed_do = $request->closed_do;
        $basisgdm->closedcause = $request->closedcause;
        $basisgdm->closedreason = $request->closedreason;
        $basisgdm->save();

        LogsController::saveLog('basis_gdm', 'update(更新)', $this->ip, $this->useragent);
        return $this->respondWith($basisgdm, new BasisGdmTransformer);
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
        $basisgdm = BasisGdm::findOrFail($id);
        $basisgdm->delete();

        LogsController::saveLog('basis_gdm', 'destroy(删除)', $this->ip, $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '刪除使用者的基本資料(GDM)成功!!'
        ]);
    }

}
