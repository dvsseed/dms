<?php

namespace App\Http\Controllers\Api;

use Auth;
use Config;
use DB;
use Excel;
use File;
use Response;
use App\Basis;
use App\Mrecords;
use App\Checks;
use App\Realsun;
use App\Rawdatavpn;
use Illuminate\Http\Request;
use App\Transformers\MrecordsTransformer;
use App\Transformers\Mrecords1Transformer;
use App\Transformers\Medicalrecords1Transformer;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MrecordsController extends ApiController
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
        return $this->respondWith(Mrecords::all(), new MrecordsTransformer);
//        return $this->respondWith(DB::table('mrecords')->leftJoin('basis', 'basis.id', '=', 'mrecords.basis_id')->select('mrecords.id', 'mrecords.case_date', 'basis.name', 'mrecords.pid', 'mrecords.cure_stage')->where('mrecords.hosp_id', $this->hospid)->orderBy('mrecords.created_at', 'DESC')->paginate(20), new MrecordsTransformer);
//        return $mrecords;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Response
     */
    public function show($id)
    {
        $mrecords = Mrecords::where('id', $id)->first();

        return $this->respondWith($mrecords, new MrecordsTransformer);
    }

    public function showAll()
    {
//        $mrecords = Mrecords::where('hosp_id', $hid)->first();
        $mrecords = DB::table('mrecords')
            ->leftJoin('basis', 'basis.id', '=', 'mrecords.basis_id')
            ->select('mrecords.id', 'mrecords.case_date', 'basis.name', 'mrecords.pid', 'mrecords.cure_stage')
            ->where('mrecords.hosp_id', $this->hospid)
            ->orderBy('mrecords.created_at', 'DESC')
            ->paginate(20);
//            ->get();

//        return $this->respondWith($mrecords, new MrecordsTransformer);
        return $mrecords;
    }

    public function showPage(Request $request, $page = 20)
    {
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => $this->uid
//        ]);
        $mrecords = DB::table('mrecords')
            ->leftJoin('basis', 'basis.id', '=', 'mrecords.basis_id')
            ->select('mrecords.id', 'basis.name', 'mrecords.pid', DB::raw('replace(substring(mrecords.educator_user_id, locate(\':\', mrecords.educator_user_id, 7) + 2, length(mrecords.educator_user_id) - locate(":", mrecords.educator_user_id, 7)), \'"}\', \'\') as ename'), 'mrecords.soap_date', 'mrecords.close_date', DB::raw("(select `soap_date` from `soaps` where `mrecords_id`=`mrecords`.`id`) as soapdate"))
            ->where('mrecords.hosp_id', $this->hospid)
            ->where('mrecords.add_user_id', $this->uid)
            ->whereNull('close_date')
            ->orderBy('mrecords.created_at', 'DESC')
            ->paginate($page);

        return $mrecords;
    }

    public function showPage1(Request $request, $page = 20)
    {
        // select mr_date from mresult where basis_id=173 order by mr_date desc limit 1; 血糖更新日
        // replace(substring(educator_user_id, locate(':', educator_user_id, 7) + 2, length(educator_user_id) - locate(":", educator_user_id, 7)), '"}', '')  as name
        $mrecords = DB::table('basis')
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
                $mrecords = DB::table('mrecords')
                    ->leftJoin('basis', 'basis.id', '=', 'mrecords.basis_id')
                    ->select('mrecords.id', 'basis.name', 'mrecords.pid', DB::raw('replace(substring(educator_user_id, locate(\':\', educator_user_id, 7) + 2, length(educator_user_id) - locate(":", educator_user_id, 7)), \'"}\', \'\')  as ename'), 'mrecords.soap_date', 'mrecords.close_date')
                    ->where('mrecords.hosp_id', $this->hospid)
                    ->orderBy('mrecords.created_at', 'DESC')
                    ->paginate($page);
        */
        return $mrecords;
    }

    public function listData($pid)
    {
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => $pid
//        ]);
//        $mrecords = DB::table('basis')
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

        $mrecords = DB::table('basis')
            ->select('id', 'hosp_id', 'name', 'pid', DB::raw("(select `mr_date` from `mresult` where `pid` like '%" . $pid . "%' and `hosp_id`='" . $this->hospid . "' order by `mr_date` desc limit 1) as mrdate"), DB::raw("(select `educator_user_id` from `tracks` where `pid` like '%" . $pid . "%' and `hosp_id`='" . $this->hospid . "' order by `start_date` desc limit 1) as educator_user_id"), DB::raw("(select replace(substring(`educator_user_id`, locate(':', `educator_user_id`, 7) + 2, length(`educator_user_id`) - locate(':', `educator_user_id`, 7)), '\"}', '') from `tracks` where `pid` like '%" . $pid . "%' and `hosp_id`='" . $this->hospid . "' order by `start_date` desc limit 1) as ename"), DB::raw("(select replace(substring(`educator_user_id`, locate(':', `educator_user_id`, 7) + 2, length(`educator_user_id`) - locate(':', `educator_user_id`, 7)), '\"}', '') from `soaps` where `pid` like '%" . $pid . "%' and `hosp_id`='" . $this->hospid . "' order by `soap_date` desc limit 1) as sname"))
            ->where('basis.hosp_id', $this->hospid)
            ->where('basis.pid', 'like', '%' . $pid . '%')
            ->orderBy('basis.pid', 'asc')
            ->get();
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => $mrecords
//        ]);
        return $mrecords;
        // return $this->respondWith($mrecords, new Medicalrecords1Transformer);
    }

    public function showId1($id)
    {
        $mrecords = Mrecords::where('id', $id)->first();

        return $this->respondWith($mrecords, new Mrecords1Transformer);
    }

    public function showPid($pid)
    {
        $mrecords = Mrecords::where('pid', $pid)->first();

        return $this->respondWith($mrecords, new MrecordsTransformer);
    }

    public function showPid1($pid)
    {
        if ($mrecords = Mrecords::where('pid', $pid)->first()) {
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => $mrecords
//        ]);
//
            // if ($mrecords)
            return $this->respondWith($mrecords, new Mrecords1Transformer);
        }

        return ["result" => 404];
        // else
        //    return $mrecords;

//        $mrecords = DB::table('mrecords')
//            ->select(DB::raw('id, basis_id, hosp_id, pid, educator_user_id, start_date	date, end_date, track_date'))
//            ->where('pid', $pid)
//            ->orderBy('created_at', 'DESC')
//            ->get();
//        return $mrecords;
    }

    public function showPid2($pid)
    {
        $mrecords = Mrecords::where('pid', $pid)
            ->orderBy('track_date', 'DESC')
            ->take(1)
            ->get();

        return $this->respondWith($mrecords, new MrecordsTransformer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mrecords = Mrecords::where('id', $id)->first();

        return $this->respondWith($mrecords, new Mrecords1Transformer);
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

        // 尋找 Mrecords[pid]
        //$mrecords = Mrecords::where('pid', $request->pid)->first();
//        if ($mrecords) {
//            $this->validate($request, [
//                'pid' => 'required|max:10',
//            ]);
//        }

        // 新增 工作清單
        $mrecords = new Mrecords;
        $mrecords->basis_id = $request->id;
        $mrecords->hosp_id = $request->hosp_id;
        $mrecords->pid = $request->pid;
        $mrecords->soap_date = date('Y-m-d');
        $mrecords->add_user_id = $this->uid;
//        $mrecords->educator_user_id = $request->educator_user_id;
        $mrecords->educator_user_id = json_encode($request->educator_user_id, JSON_UNESCAPED_UNICODE);
        // $mrecords->dietplan = json_encode(["一般"], JSON_UNESCAPED_UNICODE);

        $mrecords->save();

        LogsController::saveLog('mrecords', 'store(保存)', $this->ip, $this->useragent);
        return $this->respondWith($mrecords, new Mrecords1Transformer);
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

        $mrecords = Mrecords::findOrFail($id);
//        $mrecords->educator_user_id = $request->educator_user_id;
        $mrecords->educator_user_id = json_encode($request->educator_user_id, JSON_UNESCAPED_UNICODE);
        $mrecords->start_date = $request->start_date;
        $mrecords->end_date = $request->end_date;
        $mrecords->track_date = $request->track_date;
        $mrecords->contact_period = $request->contact_period;
        $mrecords->bloodsugar_source = $request->bloodsugar_source;
        $mrecords->contact_tel = $request->contact_tel;
        $mrecords->contact_mobile = $request->contact_mobile;
        $mrecords->track1_contact = $request->track1_contact;
        $mrecords->track1_tel = $request->track1_tel;
        $mrecords->track1_mobile = $request->track1_mobile;
        $mrecords->track2_contact = $request->track2_contact;
        $mrecords->track2_tel = $request->track2_tel;
        $mrecords->track2_mobile = $request->track2_mobile;
        $mrecords->track3_contact = $request->track3_contact;
        $mrecords->track3_tel = $request->track3_tel;
        $mrecords->track3_mobile = $request->track3_mobile;
        $mrecords->track_reason = $request->track_reason;
        $mrecords->dietplan = json_encode($request->dietplan, JSON_UNESCAPED_UNICODE);
        $mrecords->dietplan_meal = $request->dietplan_meal;
        $mrecords->dietplan_dessert = $request->dietplan_dessert;

//        $mrecords->foot_chk_left = json_encode($request->foot_chk_left, JSON_UNESCAPED_UNICODE);

        $mrecords->dietplan_sugaramount = $request->dietplan_sugaramount;
        $mrecords->monitor_mode = $request->monitor_mode;
        $mrecords->monitor_other = $request->monitor_other;
        $mrecords->bloodsugar_from_beforemeal = $request->bloodsugar_from_beforemeal;
        $mrecords->bloodsugar_to_beforemeal = $request->bloodsugar_to_beforemeal;
        $mrecords->bloodsugar_from_aftermeal = $request->bloodsugar_from_aftermeal;
        $mrecords->bloodsugar_to_aftermeal = $request->bloodsugar_to_aftermeal;
        $mrecords->bloodsugar_from_beforesleep = $request->bloodsugar_from_beforesleep;
        $mrecords->bloodsugar_to_beforesleep = $request->bloodsugar_to_beforesleep;
        $mrecords->adjustprinciple_unit = $request->adjustprinciple_unit;
        $mrecords->adjustprinciple_isf = $request->adjustprinciple_isf;
//        if ($request->offdm == null) {
//            $mrecords->close_date = null;
//        } else {
//            $mrecords->close_date = $request->close_date;
//        }
        $mrecords->adjustprinciple_u = $request->adjustprinciple_u;
        $mrecords->adjustprinciple_ci_morning = $request->adjustprinciple_ci_morning;
        $mrecords->adjustprinciple_ci_afternoon = $request->adjustprinciple_ci_afternoon;
        $mrecords->adjustprinciple_ci_evening = $request->adjustprinciple_ci_evening;
        $mrecords->medication = $request->medication;
        $mrecords->remarks = $request->remarks;

        $mrecords->save();

        LogsController::saveLog('mrecords', 'update(更新)', $this->ip, $this->useragent);
        return $this->respondWith($mrecords, new MrecordsTransformer);
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
        $mrecords = Mrecords::findOrFail($id);
        $mrecords->close_date = date('Y-m-d');
        $mrecords->save();
//        $mrecords->delete();  // 不是真的要刪除, 僅做軟刪除

        LogsController::saveLog('mrecords', 'destroy(删除至歷史)', $this->ip, $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '刪除個管成功!!'
        ]);
    }

    public function deleteAll(Request $request)
    {
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => $request->ids
//        ]);
        try {
            DB::update(DB::raw("UPDATE `mrecords` SET `close_date` = CURRENT_DATE() WHERE id in (" . $request->ids . ")"));
        } catch(\Exception $e) {
            $errormsg = 'Database error::' . $e->getCode();
            return $this->respondWithArray([
                'status' => 'error',
                'message' => $errormsg
            ]);
        }
    }

}
