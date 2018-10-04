<?php

namespace App\Http\Controllers\Api;

use Auth;
use Config;
use DB;
use Excel;
use File;
use Response;
use App\Basis;
use App\Tracks;
use App\Checks;
use App\Realsun;
use App\Rawdatavpn;
use Illuminate\Http\Request;
use App\Transformers\TracksTransformer;
use App\Transformers\Tracks1Transformer;
// use App\Transformers\Tracks2Transformer;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TracksController extends ApiController
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
        return $this->respondWith(Tracks::all(), new TracksTransformer);
//        return $this->respondWith(DB::table('tracks')->leftJoin('basis', 'basis.id', '=', 'tracks.basis_id')->select('tracks.id', 'tracks.case_date', 'basis.name', 'tracks.pid', 'tracks.cure_stage')->where('tracks.hosp_id', $this->hospid)->orderBy('tracks.created_at', 'DESC')->paginate(20), new TracksTransformer);
//        return $tracks;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Response
     */
    public function show($id)
    {
        $tracks = Tracks::where('id', $id)->first();

        return $this->respondWith($tracks, new TracksTransformer);
    }

    public function showAll()
    {
//        $tracks = Tracks::where('hosp_id', $hid)->first();
        $tracks = DB::table('tracks')
            ->leftJoin('basis', 'basis.id', '=', 'tracks.basis_id')
            ->select('tracks.id', 'tracks.case_date', 'basis.name', 'tracks.pid', 'tracks.cure_stage')
            ->where('tracks.hosp_id', $this->hospid)
            ->orderBy('tracks.created_at', 'DESC')
            ->paginate(20);
//            ->get();

//        return $this->respondWith($tracks, new TracksTransformer);
        return $tracks;
    }

    public function showPage(Request $request, $page = 20)
    {
        // select mr_date from mresult where basis_id=173 order by mr_date desc limit 1; 血糖更新日
        // replace(substring(educator_user_id, locate(':', educator_user_id, 7) + 2, length(educator_user_id) - locate(":", educator_user_id, 7)), '"}', '')  as name
        $tracks = DB::table('tracks')
            ->leftJoin('basis', 'basis.id', '=', 'tracks.basis_id')
            ->select('tracks.id', 'basis.name', 'tracks.pid', DB::raw('replace(substring(educator_user_id, locate(\':\', educator_user_id, 7) + 2, length(educator_user_id) - locate(":", educator_user_id, 7)), \'"}\', \'\')  as ename'), DB::raw('(select left(mr_date,10) from mresult where pid=tracks.pid order by mr_date desc limit 1) as mrdate'), 'tracks.end_date', 'tracks.track_date', 'tracks.contact_period', 'tracks.bloodsugar_source')
            ->where('tracks.hosp_id', $this->hospid)
            ->orderBy('tracks.created_at', 'DESC')
            ->paginate($page);

        return $tracks;

//        return $this->respondWith(DB::table('tracks')->leftJoin('basis', 'basis.id', '=', 'tracks.basis_id')->select('tracks.case_date', 'basis.name', 'tracks.pid', 'tracks.cure_stage')->where('tracks.hosp_id', $this->hospid)->orderBy('tracks.created_at', 'DESC')->paginate($page), new Tracks2Transformer);
    }

    public function showId1($id)
    {
        $tracks = Tracks::where('id', $id)->first();

        return $this->respondWith($tracks, new Tracks1Transformer);
    }

    public function showPid($pid)
    {
        $tracks = Tracks::where('pid', $pid)->first();

        return $this->respondWith($tracks, new TracksTransformer);
    }

    public function showPid1($pid)
    {
        if ($tracks = Tracks::where('pid', $pid)->first()) {
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => $tracks
//        ]);
//
            // if ($tracks)
            return $this->respondWith($tracks, new Tracks1Transformer);
        }

        return ["result" => 404];
        // else
        //    return $tracks;

//        $tracks = DB::table('tracks')
//            ->select(DB::raw('id, basis_id, hosp_id, pid, educator_user_id, start_date	date, end_date, track_date'))
//            ->where('pid', $pid)
//            ->orderBy('created_at', 'DESC')
//            ->get();
//        return $tracks;
    }

    public function showPid2($pid)
    {
        $tracks = Tracks::where('pid', $pid)
            ->orderBy('track_date', 'DESC')
            ->take(1)
            ->get();

        return $this->respondWith($tracks, new TracksTransformer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tracks = Tracks::where('id', $id)->first();

        return $this->respondWith($tracks, new Tracks1Transformer);
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

        // 尋找 Tracks[pid]
        //$tracks = Tracks::where('pid', $request->pid)->first();
//        if ($tracks) {
//            $this->validate($request, [
//                'pid' => 'required|max:10',
//            ]);
//        }

        // 尋找 Basis[id]
        $basis = Basis::where('pid', $request->pid)->first();

        // 新增 個管
        $tracks = new Tracks;
        $tracks->basis_id = $basis->id;
        $tracks->hosp_id = $request->hosp_id;
        $tracks->pid = $request->pid;

        $tracks->start_date = date('Y-m-d');
        $tracks->end_date = date('Y-m-d');
        $tracks->track_date = date('Y-m-d');

        // $tracks->smoking = "無";
        // $tracks->educator_user_id = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $tracks->track_reason = '無';
        $tracks->dietplan = json_encode(["一般"], JSON_UNESCAPED_UNICODE);

        $tracks->save();

        LogsController::saveLog('tracks', 'store(保存)', $this->ip, $this->useragent);
        return $this->respondWith($tracks, new Tracks1Transformer);
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

        $tracks = Tracks::findOrFail($id);
//        $tracks->educator_user_id = $request->educator_user_id;
        $tracks->educator_user_id = json_encode($request->educator_user_id, JSON_UNESCAPED_UNICODE);
        $tracks->start_date = $request->start_date;
        $tracks->end_date = $request->end_date;
        $tracks->track_date = $request->track_date;
        $tracks->contact_period = $request->contact_period;
        $tracks->bloodsugar_source = $request->bloodsugar_source;
        $tracks->contact_tel = $request->contact_tel;
        $tracks->contact_mobile = $request->contact_mobile;
        $tracks->track1_contact = $request->track1_contact;
        $tracks->track1_tel = $request->track1_tel;
        $tracks->track1_mobile = $request->track1_mobile;
        $tracks->track2_contact = $request->track2_contact;
        $tracks->track2_tel = $request->track2_tel;
        $tracks->track2_mobile = $request->track2_mobile;
        $tracks->track3_contact = $request->track3_contact;
        $tracks->track3_tel = $request->track3_tel;
        $tracks->track3_mobile = $request->track3_mobile;
        $tracks->track_reason = $request->track_reason;
        $tracks->dietplan = json_encode($request->dietplan, JSON_UNESCAPED_UNICODE);
        $tracks->dietplan_meal = $request->dietplan_meal;
        $tracks->dietplan_dessert = $request->dietplan_dessert;

//        $tracks->foot_chk_left = json_encode($request->foot_chk_left, JSON_UNESCAPED_UNICODE);

        $tracks->dietplan_sugaramount = $request->dietplan_sugaramount;
        $tracks->monitor_mode = $request->monitor_mode;
        $tracks->monitor_other = $request->monitor_other;
        $tracks->bloodsugar_from_beforemeal = $request->bloodsugar_from_beforemeal;
        $tracks->bloodsugar_to_beforemeal = $request->bloodsugar_to_beforemeal;
        $tracks->bloodsugar_from_aftermeal = $request->bloodsugar_from_aftermeal;
        $tracks->bloodsugar_to_aftermeal = $request->bloodsugar_to_aftermeal;
        $tracks->bloodsugar_from_beforesleep = $request->bloodsugar_from_beforesleep;
        $tracks->bloodsugar_to_beforesleep = $request->bloodsugar_to_beforesleep;
        $tracks->adjustprinciple_unit = $request->adjustprinciple_unit;
        $tracks->adjustprinciple_isf = $request->adjustprinciple_isf;
//        if ($request->offdm == null) {
//            $tracks->close_date = null;
//        } else {
//            $tracks->close_date = $request->close_date;
//        }
        $tracks->adjustprinciple_u = $request->adjustprinciple_u;
        $tracks->adjustprinciple_ci_morning = $request->adjustprinciple_ci_morning;
        $tracks->adjustprinciple_ci_afternoon = $request->adjustprinciple_ci_afternoon;
        $tracks->adjustprinciple_ci_evening = $request->adjustprinciple_ci_evening;
        $tracks->medication = $request->medication;
        $tracks->remarks = $request->remarks;

        $tracks->save();

        LogsController::saveLog('tracks', 'update(更新)', $this->ip, $this->useragent);
        return $this->respondWith($tracks, new TracksTransformer);
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
        $tracks = Tracks::findOrFail($id);
        $tracks->delete();

        LogsController::saveLog('tracks', 'destroy(删除)', $this->ip, $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '刪除個管成功!!'
        ]);
    }

}
