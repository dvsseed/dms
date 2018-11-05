<?php

namespace App\Http\Controllers\Api;

use Auth;
use Config;
use DB;
use Excel;
use File;
use Response;
use App\Basis;
use App\Soaps;
use App\Checks;
use App\Realsun;
use App\Rawdatavpn;
use Illuminate\Http\Request;
use App\Transformers\SoapsTransformer;
use App\Transformers\Soaps1Transformer;
use App\Transformers\Medicalrecords1Transformer;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SoapsController extends ApiController
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
        return $this->respondWith(Soaps::all(), new SoapsTransformer);
//        return $this->respondWith(DB::table('soaps')->leftJoin('basis', 'basis.id', '=', 'soaps.basis_id')->select('soaps.id', 'soaps.case_date', 'basis.name', 'soaps.pid', 'soaps.cure_stage')->where('soaps.hosp_id', $this->hospid)->orderBy('soaps.created_at', 'DESC')->paginate(20), new SoapsTransformer);
//        return $soaps;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Response
     */
    public function show($id)
    {
        $soaps = Soaps::where('id', $id)->first();

        return $this->respondWith($soaps, new SoapsTransformer);
    }

    public function showAll()
    {
//        $soaps = Soaps::where('hosp_id', $hid)->first();
        $soaps = DB::table('soaps')
            ->leftJoin('basis', 'basis.id', '=', 'soaps.basis_id')
            ->select('soaps.id', 'soaps.case_date', 'basis.name', 'soaps.pid', 'soaps.cure_stage')
            ->where('soaps.hosp_id', $this->hospid)
            ->orderBy('soaps.created_at', 'DESC')
            ->paginate(20);
//            ->get();

//        return $this->respondWith($soaps, new SoapsTransformer);
        return $soaps;
    }

    public function showPage(Request $request, $page = 20)
    {
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => $this->uid
//        ]);
        $soaps = DB::table('soaps')
            ->leftJoin('basis', 'basis.id', '=', 'soaps.basis_id')
            ->select('soaps.id', 'basis.name', 'soaps.pid', DB::raw('replace(substring(soaps.educator_user_id, locate(\':\', soaps.educator_user_id, 7) + 2, length(soaps.educator_user_id) - locate(":", soaps.educator_user_id, 7)), \'"}\', \'\') as ename'), 'soaps.soap_date', 'soaps.close_date')
            ->where('soaps.hosp_id', $this->hospid)
            ->where('soaps.add_user_id', $this->uid)
            ->orderBy('soaps.created_at', 'DESC')
            ->paginate($page);

        return $soaps;
    }

    public function showPage1(Request $request, $page = 20)
    {
        // select mr_date from mresult where basis_id=173 order by mr_date desc limit 1; 血糖更新日
        // replace(substring(educator_user_id, locate(':', educator_user_id, 7) + 2, length(educator_user_id) - locate(":", educator_user_id, 7)), '"}', '')  as name
        $soaps = DB::table('basis')
            ->leftJoin('mresult', 'basis.hosp_id', '=', 'mresult.hosp_id')
            ->leftJoin('mresult', 'basis.pid', '=', 'mresult.pid')
            ->leftJoin('tracks', 'basis.hosp_id', '=', 'tracks.hosp_id')
            ->leftJoin('tracks', 'basis.pid', '=', 'tracks.pid')
            ->leftJoin('soaps', 'basis.hosp_id', '=', 'soaps.hosp_id')
            ->leftJoin('soaps', 'basis.pid', '=', 'soaps.pid')
            ->select('basis.id', 'basis.name', 'basis.pid', DB::raw('left(mresult.mr_date,10) as mrdate'), DB::raw('replace(substring(tracks.educator_user_id, locate(\':\', tracks.educator_user_id, 7) + 2, length(tracks.educator_user_id) - locate(":", tracks.educator_user_id, 7)), \'"}\', \'\') as ename'), DB::raw('replace(substring(soaps.educator_user_id, locate(\':\', soaps.educator_user_id, 7) + 2, length(soaps.educator_user_id) - locate(":", soaps.educator_user_id, 7)), \'"}\', \'\') as sname'))
            ->where('tracks.hosp_id', $this->hospid)
            ->orderBy('basis.pid', 'asc')
            ->orderBy('mresult.mr_date', 'desc')
            ->orderBy('tracks.start_date', 'desc')
            ->orderBy('soaps.soap_date', 'desc')
            ->paginate($page);
        /*
                $soaps = DB::table('soaps')
                    ->leftJoin('basis', 'basis.id', '=', 'soaps.basis_id')
                    ->select('soaps.id', 'basis.name', 'soaps.pid', DB::raw('replace(substring(educator_user_id, locate(\':\', educator_user_id, 7) + 2, length(educator_user_id) - locate(":", educator_user_id, 7)), \'"}\', \'\')  as ename'), 'soaps.soap_date', 'soaps.close_date')
                    ->where('soaps.hosp_id', $this->hospid)
                    ->orderBy('soaps.created_at', 'DESC')
                    ->paginate($page);
        */
        return $soaps;
    }

    public function listDate($pid)
    {
        // $trcks = DB::select(DB::raw("SELECT track_reason FROM tracks LEFT JOIN checks ON checks.pid=cases.pid WHERE (cases.cure_stage='DM年度' OR cases.cure_stage='CKD+DM複診' OR cases.cure_stage='CKD複診+DM年度') AND CONCAT(LEFT(checks.check_date,4)+1911,'-',SUBSTRING(checks.check_date,6,2),'-',RIGHT(checks.check_date,2))=cases.case_date AND checks.pid IS NOT NULL AND (cases.blood_hba1c IS NULL OR cases.blood_ldl IS NULL OR cases.blood_tg IS NULL OR cases.egfr IS NULL OR cases.blood_creat IS NULL OR cases.blood_hba1c = 0 OR cases.blood_ldl = 0 OR cases.blood_tg = 0 OR cases.egfr = 0 OR cases.blood_creat = 0 OR (cases.urine_micro IS NULL AND cases.upcr IS NULL) OR (cases.urine_micro = 0 AND cases.upcr = 0)) AND cases.hosp_id=? ORDER BY cases.pid DESC"), ["$this->hospid"]);
        $soaps = DB::table('soaps')
            ->select('id', 'soap_date')
            ->where('pid', '=', $pid)
            ->orderBy('soap_date', 'DESC')
            ->get();

        return $soaps;
    }

    public function listData($pid)
    {
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => $pid
//        ]);
//        $soaps = DB::table('basis')
//            ->leftJoin('mresult', 'basis.id', '=', 'mresult.basis_id')
//            ->leftJoin('tracks', 'basis.id', '=', 'tracks.basis_id')
//            ->leftJoin('soaps', 'basis.id', '=', 'soaps.basis_id')
//            ->select('basis.id', 'basis.name', 'basis.pid', DB::raw('mresult.mr_date as mrdate'), 'tracks.start_date', 'soaps.soap_date', DB::raw('tracks.educator_user_id as ename'), DB::raw('soaps.educator_user_id as sname'))
//            ->where('basis.hosp_id', $this->hospid)
//            ->where('basis.pid', 'like', '%' . $pid . '%')
//            ->orderBy('basis.pid', 'asc')
//            ->orderBy('mresult.mr_date', 'desc')
//            ->orderBy('tracks.start_date', 'desc')
//            ->orderBy('soaps.soap_date', 'desc')
//            ->get();

        $soaps = DB::table('basis')
            ->select('id', 'hosp_id', 'name', 'pid', DB::raw("(select `mr_date` from `mresult` where `pid` like '%" . $pid . "%' and `hosp_id`='" . $this->hospid . "' order by `mr_date` desc limit 1) as mrdate"), DB::raw("(select `educator_user_id` from `tracks` where `pid` like '%" . $pid . "%' and `hosp_id`='" . $this->hospid . "' order by `start_date` desc limit 1) as educator_user_id"), DB::raw("(select replace(substring(`educator_user_id`, locate(':', `educator_user_id`, 7) + 2, length(`educator_user_id`) - locate(':', `educator_user_id`, 7)), '\"}', '') from `tracks` where `pid` like '%" . $pid . "%' and `hosp_id`='" . $this->hospid . "' order by `start_date` desc limit 1) as ename"), DB::raw("(select replace(substring(`educator_user_id`, locate(':', `educator_user_id`, 7) + 2, length(`educator_user_id`) - locate(':', `educator_user_id`, 7)), '\"}', '') from `soaps` where `pid` like '%" . $pid . "%' and `hosp_id`='" . $this->hospid . "' order by `soap_date` desc limit 1) as sname"))
            ->where('basis.hosp_id', $this->hospid)
            ->where('basis.pid', 'like', '%' . $pid . '%')
            ->orderBy('basis.pid', 'asc')
            ->get();
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => $soaps
//        ]);
        return $soaps;
        // return $this->respondWith($soaps, new Medicalrecords1Transformer);
    }

    public function showId1($id)
    {
        $soaps = Soaps::where('id', $id)->first();

        return $this->respondWith($soaps, new Soaps1Transformer);
    }

    public function showPid($pid)
    {
        $soaps = Soaps::where('pid', $pid)->first();

        return $this->respondWith($soaps, new SoapsTransformer);
    }

    public function showPid1($pid)
    {
        if ($soaps = Soaps::where('pid', $pid)->first()) {
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => $soaps
//        ]);
//
            // if ($soaps)
            return $this->respondWith($soaps, new Soaps1Transformer);
        }

        return ["result" => 404];
        // else
        //    return $soaps;

//        $soaps = DB::table('soaps')
//            ->select(DB::raw('id, basis_id, hosp_id, pid, educator_user_id, start_date	date, end_date, track_date'))
//            ->where('pid', $pid)
//            ->orderBy('created_at', 'DESC')
//            ->get();
//        return $soaps;
    }

    public function showPid2($pid)
    {
        $soaps = Soaps::where('pid', $pid)
            ->orderBy('track_date', 'DESC')
            ->take(1)
            ->get();

        return $this->respondWith($soaps, new SoapsTransformer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $soaps = Soaps::where('id', $id)->first();

        return $this->respondWith($soaps, new Soaps1Transformer);
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

        // 尋找 Soaps[pid]
        //$soaps = Soaps::where('pid', $request->pid)->first();
//        if ($soaps) {
//            $this->validate($request, [
//                'pid' => 'required|max:10',
//            ]);
//        }

        // 新增 工作清單
        $soaps = new Soaps;
        $soaps->basis_id = $request->id;
        $soaps->hosp_id = $request->hosp_id;
        $soaps->pid = $request->pid;
        $soaps->soap_date = date('Y-m-d');
        $soaps->add_user_id = $this->uid;
        $soaps->educator_user_id = $request->educator_user_id;
        // $soaps->dietplan = json_encode(["一般"], JSON_UNESCAPED_UNICODE);

        $soaps->save();

        LogsController::saveLog('soaps', 'store(保存)', $this->ip, $this->useragent);
        return $this->respondWith($soaps, new Soaps1Transformer);
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
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => $request->educators
//        ]);
//        $this->validate($request, [
//            'email' => 'required',
//            'password' => 'required',
//        ]);

        $soaps = Soaps::findOrFail($id);
//        $soaps->educator_user_id = $request->educator_user_id;
        $soaps->educator_user_id = json_encode($request->educator_user_id, JSON_UNESCAPED_UNICODE);
        $soaps->start_date = $request->start_date;
        $soaps->end_date = $request->end_date;
        $soaps->track_date = $request->track_date;
        $soaps->contact_period = $request->contact_period;
        $soaps->bloodsugar_source = $request->bloodsugar_source;
        $soaps->contact_tel = $request->contact_tel;
        $soaps->contact_mobile = $request->contact_mobile;
        $soaps->track1_contact = $request->track1_contact;
        $soaps->track1_tel = $request->track1_tel;
        $soaps->track1_mobile = $request->track1_mobile;
        $soaps->track2_contact = $request->track2_contact;
        $soaps->track2_tel = $request->track2_tel;
        $soaps->track2_mobile = $request->track2_mobile;
        $soaps->track3_contact = $request->track3_contact;
        $soaps->track3_tel = $request->track3_tel;
        $soaps->track3_mobile = $request->track3_mobile;
        $soaps->track_reason = $request->track_reason;
        $soaps->dietplan = json_encode($request->dietplan, JSON_UNESCAPED_UNICODE);
        $soaps->dietplan_meal = $request->dietplan_meal;
        $soaps->dietplan_dessert = $request->dietplan_dessert;

//        $soaps->foot_chk_left = json_encode($request->foot_chk_left, JSON_UNESCAPED_UNICODE);

        $soaps->dietplan_sugaramount = $request->dietplan_sugaramount;
        $soaps->monitor_mode = $request->monitor_mode;
        $soaps->monitor_other = $request->monitor_other;
        $soaps->bloodsugar_from_beforemeal = $request->bloodsugar_from_beforemeal;
        $soaps->bloodsugar_to_beforemeal = $request->bloodsugar_to_beforemeal;
        $soaps->bloodsugar_from_aftermeal = $request->bloodsugar_from_aftermeal;
        $soaps->bloodsugar_to_aftermeal = $request->bloodsugar_to_aftermeal;
        $soaps->bloodsugar_from_beforesleep = $request->bloodsugar_from_beforesleep;
        $soaps->bloodsugar_to_beforesleep = $request->bloodsugar_to_beforesleep;
        $soaps->adjustprinciple_unit = $request->adjustprinciple_unit;
        $soaps->adjustprinciple_isf = $request->adjustprinciple_isf;
//        if ($request->offdm == null) {
//            $soaps->close_date = null;
//        } else {
//            $soaps->close_date = $request->close_date;
//        }
        $soaps->adjustprinciple_u = $request->adjustprinciple_u;
        $soaps->adjustprinciple_ci_morning = $request->adjustprinciple_ci_morning;
        $soaps->adjustprinciple_ci_afternoon = $request->adjustprinciple_ci_afternoon;
        $soaps->adjustprinciple_ci_evening = $request->adjustprinciple_ci_evening;
        $soaps->medication = $request->medication;
        $soaps->remarks = $request->remarks;

        $soaps->save();

        LogsController::saveLog('soaps', 'update(更新)', $this->ip, $this->useragent);
        return $this->respondWith($soaps, new SoapsTransformer);
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
        $soaps = Soaps::findOrFail($id);
        $soaps->delete();

        LogsController::saveLog('soaps', 'destroy(删除)', $this->ip, $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '刪除個管成功!!'
        ]);
    }

}
