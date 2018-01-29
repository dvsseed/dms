<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Mrnote;
use App\Mresult;
use App\Basis;
use App\Bsrange;
use Illuminate\Http\Request;
use App\Transformers\MrnoteTransformer;
use App\Transformers\MresultTransformer;
use App\Transformers\BsrangeTransformer;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MresultsController extends ApiController
{
    protected $user;
    protected $ip;
    protected $hospid;
    protected $uid;
    protected $useragent;

    /**
     * MrnoteController constructor.
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->middleware('timeout');

//        $this->user = $request->user();
        $this->user = Auth::user();
        $this->ip = $request->ip();
//        $this->hospid = $request->user()->hosp_id;
//        $this->uid = $request->user()->id;
        $this->hospid = Auth::user()->hosp_id;
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Response
     */
    public function show($id)
    {
    }

    public function showPid($pid, $mrdate)
    {
        $mrnote = Mrnote::where('hosp_id', $this->hospid)
            ->where('pid', $pid)
            ->where('mr_date', $mrdate)
            ->first();

        return $this->respondWith($mrnote, new MrnoteTransformer);
    }

    public function showBlood($pid, $mrdate, $mid)
    {
//        if ( strtolower($mrtime) == 'null' ) {
//            $mresult = Mresult::where('hosp_id', $this->hospid)
//                ->where('pid', $pid)
//                ->where('mr_date', $mrdate)
//                // ->whereNull('mr_time')
//                ->first();
//        } else {
        $mresult = Mresult::where('hosp_id', $this->hospid)
            ->where('pid', $pid)
            ->where('mr_date', $mrdate)
            ->where('id', $mid)
            ->first();
//        }

        return $this->respondWith($mresult, new MresultTransformer);
    }

    public function showBGbyDate($pid, $sdate, $edate)
    {
        $mresult = DB::table('mresult')
            ->leftJoin('basis', 'basis.id', '=', 'mresult.basis_id')
            ->select('mresult.id', 'basis.name as bname', 'mresult.mr_date', 'mresult.mr_time', 'mresult.value1', 'mresult.slot', 'mresult.slotname', 'mresult.addnewdate')
            ->where('mresult.hosp_id', $this->hospid)
            ->where('mresult.pid', $pid)
            ->where(DB::raw('LEFT(`mr_date`, 10)'), ">=", $sdate)
            ->where(DB::raw('LEFT(`mr_date`, 10)'), "<=", $edate)
            ->orderBy('mresult.mr_date', 'ASC')
            ->get();

        return $mresult;
    }

    public function showBGbyAdd($pid, $sdate, $edate)
    {
        $mresult = DB::table('mresult')
            ->leftJoin('basis', 'basis.id', '=', 'mresult.basis_id')
            ->select('mresult.id', 'basis.name as bname', 'mresult.mr_date', 'mresult.mr_time', 'mresult.value1', 'mresult.slot', 'mresult.slotname', 'mresult.addnewdate')
            ->where('mresult.hosp_id', $this->hospid)
            ->where('mresult.pid', $pid)
            ->where(DB::raw('LEFT(`addnewdate`, 10)'), ">=", $sdate)
            ->where(DB::raw('LEFT(`addnewdate`, 10)'), "<=", $edate)
            ->orderBy('mresult.addnewdate', 'ASC')
            ->get();

        return $mresult;
    }

    public function showNote($pid, $fdate, $tdate)
    {
        $mrnote = DB::table('mrnote')
            ->select('mr_date', 'note')
            ->where('hosp_id', $this->hospid)
            ->where('pid', $pid)
            ->where('mr_date', '<=', $fdate)
            ->where('mr_date', '>=', $tdate)
            ->orderBy('mr_date', 'DESC')
            ->get();

        return $mrnote;
    }

    public function showBsrange($pid)
    {
        $bsrange = Bsrange::where('hosp_id', $this->hospid)
            ->where('pid', $pid)
            ->first();

        //return $this->respondWith($bsrange, new BsrangeTransformer);
        return $bsrange;
    }

    public function showMR($pid, $fdate, $tdate)
    {
//        $hid = $this->hospid;
//        $mresult = Mresult::where('hosp_id', $hid)->where('pid', $pid)->where('mr_date', $mrdate)->where('slot', $mrslot)->first();
//        return $this->respondWith($mresult, new MresultTransformer);

        $mresult = DB::table('mresult')
            ->select('id', 'mr_date', 'mr_time', 'slot', 'value1', 'note')
//        $mresult = Mresult::where('hosp_id', $this->hospid)
            ->where('pid', $pid)
            ->where(DB::raw('LEFT(`mr_date`, 10)'), '<=', $fdate)
            ->where(DB::raw('LEFT(`mr_date`, 10)'), '>=', $tdate)
            ->orderBy('mr_date', 'DESC')
            ->orderBy('mr_time', 'ASC')
            ->orderBy('slot', 'ASC')
            ->get();

        return $mresult;
//        return $this->respondWith($mresult, new MresultTransformer);
    }

    public function showPage(Request $request, $page = 20)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
    }

    public function saveNote(Request $request, $pid)
    {
//return $this->respondWithArray([
//    'status' => 'error',
//    'message' => $request->note
//]);
        $hid = $this->hospid;
        $mrnotes = Mrnote::where('hosp_id', $hid)->where('pid', $pid)->where('mr_date', $request->mr_date)->first();
        if ($mrnotes) {
            $mrnotes->note = $request->note;
            $mrnotes->educator = $this->uid;
            $mrnotes->save();
            LogsController::saveLog('mrnote', 'update(更新)', $this->ip, $this->useragent);
            return $this->respondWith($mrnotes, new MrnoteTransformer);
        }

        // 沒有建過備註
        $mrnote = new Mrnote;
        $mrnote->hosp_id = $hid;
        $mrnote->pid = $pid;
        $mrnote->mr_date = $request->mr_date;
        $mrnote->note = $request->note;
        $mrnote->educator = $this->uid;
        $mrnote->save();
        LogsController::saveLog('mrnote', 'store(保存)', $this->ip, $this->useragent);
        return $this->respondWith($mrnote, new MrnoteTransformer);
    }

    public function saveBlood(Request $request, $pid)
    {
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => $request->autonote
//        ]);

        $data = $request->mr_time;
        if ($data['HH'])
            $HH = $data['HH'];
        else
            $HH = '00';
        if ($data['mm'])
            $mm = $data['mm'];
        else
            $mm = '00';
        if ($data['ss'])
            $ss = $data['ss'];
        else
            $ss = '00';
        $hid = $this->hospid;
        $mresults = Mresult::where('hosp_id', $hid)->where('pid', $pid)->where('mr_date', $request->mr_date)->where('id', $request->id)->first();
        if ($mresults) {
            $d = strtotime(substr($request->mr_date, 0, 10) . ' ' . $HH . ':' . $mm . ':' . $ss);
            $mresults->mr_date = date("Y-m-d H:i:s", $d);
            $mresults->mr_time = json_encode($request->mr_time, JSON_UNESCAPED_UNICODE);
            $mresults->slotname = $request->slotname;
            $mresults->value1 = $request->value1;
            $mresults->value2 = $request->value2;
            $mresults->note = $request->note;
            $mresults->autonote = json_encode($request->autonote, JSON_UNESCAPED_UNICODE);
            $mresults->save();
            LogsController::saveLog('mresult', 'update(更新)', $this->ip, $this->useragent);
            return $this->respondWith($mresults, new MresultTransformer);
        }

        // 沒有建過血糖
        $mresult = new Mresult;

        // 尋找 Basis[id]
        $basis = Basis::where('pid', $pid)->first();
        $mresult->basis_id = $basis->id;

        $mresult->hosp_id = $hid;
        $mresult->pid = $pid;
        //$mresult->mr_date = $request->mr_date;

        $rngarray = array("", "晨起", "早餐前", "早餐後", "中餐前", "中餐後", "晚餐前", "晚餐後", "睡前", "凌晨");
        $key = array_search($request->slotname, $rngarray);
        $mresult->slot = $key;

        switch ($key) {
            case 0:
                if ($request->mr_time == ["HH" => "00", "mm" => "00", "ss" => "00"]) {
                    $mresult->mr_time = json_encode(["HH" => "00", "mm" => "00", "ss" => "00"], JSON_UNESCAPED_UNICODE);
                } else {
                    $mresult->mr_time = json_encode($request->mr_time, JSON_UNESCAPED_UNICODE);
                }
                $mresult->mr_date = $request->mr_date . " 00:00:00";
                break;
            case 1:
                if ($request->mr_time == ["HH" => "00", "mm" => "00", "ss" => "00"]) {
                    $mresult->mr_time = json_encode(["HH" => "06", "mm" => "01", "ss" => "00"], JSON_UNESCAPED_UNICODE);
                } else {
                    $mresult->mr_time = json_encode($request->mr_time, JSON_UNESCAPED_UNICODE);
                }
                $mresult->mr_date = $request->mr_date . " 06:01:00";
                break;
            case 2:
                if ($request->mr_time == ["HH" => "00", "mm" => "00", "ss" => "00"]) {
                    $mresult->mr_time = json_encode(["HH" => "07", "mm" => "01", "ss" => "00"], JSON_UNESCAPED_UNICODE);
                } else {
                    $mresult->mr_time = json_encode($request->mr_time, JSON_UNESCAPED_UNICODE);
                }
                $mresult->mr_date = $request->mr_date . " 07:01:00";
                break;
            case 3:
                if ($request->mr_time == ["HH" => "00", "mm" => "00", "ss" => "00"]) {
                    $mresult->mr_time = json_encode(["HH" => "09", "mm" => "01", "ss" => "00"], JSON_UNESCAPED_UNICODE);
                } else {
                    $mresult->mr_time = json_encode($request->mr_time, JSON_UNESCAPED_UNICODE);
                }
                $mresult->mr_date = $request->mr_date . " 09:01:00";
                break;
            case 4:
                if ($request->mr_time == ["HH" => "00", "mm" => "00", "ss" => "00"]) {
                    $mresult->mr_time = json_encode(["HH" => "11", "mm" => "01", "ss" => "00"], JSON_UNESCAPED_UNICODE);
                } else {
                    $mresult->mr_time = json_encode($request->mr_time, JSON_UNESCAPED_UNICODE);
                }
                $mresult->mr_date = $request->mr_date . " 11:01:00";
                break;
            case 5:
                if ($request->mr_time == ["HH" => "00", "mm" => "00", "ss" => "00"]) {
                    $mresult->mr_time = json_encode(["HH" => "13", "mm" => "01", "ss" => "00"], JSON_UNESCAPED_UNICODE);
                } else {
                    $mresult->mr_time = json_encode($request->mr_time, JSON_UNESCAPED_UNICODE);
                }
                $mresult->mr_date = $request->mr_date . " 13:01:00";
                break;
            case 6:
                if ($request->mr_time == ["HH" => "00", "mm" => "00", "ss" => "00"]) {
                    $mresult->mr_time = json_encode(["HH" => "17", "mm" => "01", "ss" => "00"], JSON_UNESCAPED_UNICODE);
                } else {
                    $mresult->mr_time = json_encode($request->mr_time, JSON_UNESCAPED_UNICODE);
                }
                $mresult->mr_date = $request->mr_date . " 17:01:00";
                break;
            case 7:
                if ($request->mr_time == ["HH" => "00", "mm" => "00", "ss" => "00"]) {
                    $mresult->mr_time = json_encode(["HH" => "19", "mm" => "01", "ss" => "00"], JSON_UNESCAPED_UNICODE);
                } else {
                    $mresult->mr_time = json_encode($request->mr_time, JSON_UNESCAPED_UNICODE);
                }
                $mresult->mr_date = $request->mr_date . " 19:01:00";
                break;
            case 8:
                if ($request->mr_time == ["HH" => "00", "mm" => "00", "ss" => "00"]) {
                    $mresult->mr_time = json_encode(["HH" => "20", "mm" => "31", "ss" => "00"], JSON_UNESCAPED_UNICODE);
                } else {
                    $mresult->mr_time = json_encode($request->mr_time, JSON_UNESCAPED_UNICODE);
                }
                $mresult->mr_date = $request->mr_date . " 20:31:00";
                break;
            case 9:
                if ($request->mr_time == ["HH" => "00", "mm" => "00", "ss" => "00"]) {
                    $mresult->mr_time = json_encode(["HH" => "00", "mm" => "01", "ss" => "00"], JSON_UNESCAPED_UNICODE);
                } else {
                    $mresult->mr_time = json_encode($request->mr_time, JSON_UNESCAPED_UNICODE);
                }
                $mresult->mr_date = $request->mr_date . " 00:01:00";
                break;
        }

        $mresult->slotname = $request->slotname;
        $mresult->value1 = $request->value1;
        $mresult->value2 = $request->value2;
        $mresult->note = $request->note;
        $mresult->save();
        LogsController::saveLog('mresult', 'store(保存)', $this->ip, $this->useragent);
        return $this->respondWith($mresult, new MresultTransformer);
    }

    public function saveBsrange(Request $request, $pid)
    {
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => $request->all()
//        ]);

        $hid = $this->hospid;
        $bsranges = Bsrange::where('hosp_id', $hid)->where('pid', $pid)->where('id', $request->id)->first();
        if ($bsranges) {
            $bsranges->deepnightlow = $request->deepnightlow;
            $bsranges->deepnightlow = $request->deepnightlow;
            $bsranges->deepnighthigh = $request->deepnighthigh;
            $bsranges->weekuplow = $request->weekuplow;
            $bsranges->weekuphigh = $request->weekuphigh;
            $bsranges->beforebreaklow = $request->beforebreaklow;
            $bsranges->beforebreakhigh = $request->beforebreakhigh;
            $bsranges->afterbreaklow = $request->afterbreaklow;
            $bsranges->afterbreakhigh = $request->afterbreakhigh;
            $bsranges->beforenoonlow = $request->beforenoonlow;
            $bsranges->beforenoonhigh = $request->beforenoonhigh;
            $bsranges->afternoonlow = $request->afternoonlow;
            $bsranges->afternoonhigh = $request->afternoonhigh;
            $bsranges->beforedinnerlow = $request->beforedinnerlow;
            $bsranges->beforedinnerhigh = $request->beforedinnerhigh;
            $bsranges->afterdinnerlow = $request->afterdinnerlow;
            $bsranges->afterdinnerhigh = $request->afterdinnerhigh;
            $bsranges->sleeplow = $request->sleeplow;
            $bsranges->sleephigh = $request->sleephigh;
            $bsranges->deepnightftime = $request->deepnightftime;
            $bsranges->deepnightttime = $request->deepnightttime;
            $bsranges->weekupftime = $request->weekupftime;
            $bsranges->weekupttime = $request->weekupttime;
            $bsranges->beforebreakftime = $request->beforebreakftime;
            $bsranges->beforebreakttime = $request->beforebreakttime;
            $bsranges->afterbreakftime = $request->afterbreakftime;
            $bsranges->afterbreakttime = $request->afterbreakttime;
            $bsranges->beforenoonftime = $request->beforenoonftime;
            $bsranges->beforenoonttime = $request->beforenoonttime;
            $bsranges->afternoonftime = $request->afternoonftime;
            $bsranges->afternoonttime = $request->afternoonttime;
            $bsranges->beforedinnerftime = $request->beforedinnerftime;
            $bsranges->beforedinnerttime = $request->beforedinnerttime;
            $bsranges->afterdinnerftime = $request->afterdinnerftime;
            $bsranges->afterdinnerttime = $request->afterdinnerttime;
            $bsranges->sleepftime = $request->sleepftime;
            $bsranges->sleepttime = $request->sleepttime;
            $bsranges->save();

            LogsController::saveLog('bsrange', 'update(更新)', $this->ip, $this->useragent);
            return $this->respondWith($bsranges, new BsrangeTransformer);
        }

        // 沒有建過血糖區間值
        $bsrange = new Bsrange;

        // 尋找 Basis[id]
        $basis = Basis::where('pid', $pid)->first();
        $bsrange->basis_id = $basis->id;

        $bsrange->hosp_id = $hid;
        $bsrange->pid = $pid;

        $bsrange->deepnightlow = $request->deepnightlow;
        $bsrange->deepnightlow = $request->deepnightlow;
        $bsrange->deepnighthigh = $request->deepnighthigh;
        $bsrange->weekuplow = $request->weekuplow;
        $bsrange->weekuphigh = $request->weekuphigh;
        $bsrange->beforebreaklow = $request->beforebreaklow;
        $bsrange->beforebreakhigh = $request->beforebreakhigh;
        $bsrange->afterbreaklow = $request->afterbreaklow;
        $bsrange->afterbreakhigh = $request->afterbreakhigh;
        $bsrange->beforenoonlow = $request->beforenoonlow;
        $bsrange->beforenoonhigh = $request->beforenoonhigh;
        $bsrange->afternoonlow = $request->afternoonlow;
        $bsrange->afternoonhigh = $request->afternoonhigh;
        $bsrange->beforedinnerlow = $request->beforedinnerlow;
        $bsrange->beforedinnerhigh = $request->beforedinnerhigh;
        $bsrange->afterdinnerlow = $request->afterdinnerlow;
        $bsrange->afterdinnerhigh = $request->afterdinnerhigh;
        $bsrange->sleeplow = $request->sleeplow;
        $bsrange->sleephigh = $request->sleephigh;
        $bsrange->deepnightftime = $request->deepnightftime;
        $bsrange->deepnightttime = $request->deepnightttime;
        $bsrange->weekupftime = $request->weekupftime;
        $bsrange->weekupttime = $request->weekupttime;
        $bsrange->beforebreakftime = $request->beforebreakftime;
        $bsrange->beforebreakttime = $request->beforebreakttime;
        $bsrange->afterbreakftime = $request->afterbreakftime;
        $bsrange->afterbreakttime = $request->afterbreakttime;
        $bsrange->beforenoonftime = $request->beforenoonftime;
        $bsrange->beforenoonttime = $request->beforenoonttime;
        $bsrange->afternoonftime = $request->afternoonftime;
        $bsrange->afternoonttime = $request->afternoonttime;
        $bsrange->beforedinnerftime = $request->beforedinnerftime;
        $bsrange->beforedinnerttime = $request->beforedinnerttime;
        $bsrange->afterdinnerftime = $request->afterdinnerftime;
        $bsrange->afterdinnerttime = $request->afterdinnerttime;
        $bsrange->sleepftime = $request->sleepftime;
        $bsrange->sleepttime = $request->sleepttime;

        $bsrange->save();

        LogsController::saveLog('bsrange', 'store(保存)', $this->ip, $this->useragent);
        return $this->respondWith($bsrange, new BsrangeTransformer);
    }

    public function saveBatch(Request $request, $pid)
    {
//        $test = $request->all();
//        $test = $request->input('mresult');
//        $test = $request->input('mresult.0.mdate');
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => $test
//        ]);

        // 尋找 Basis[id]
        $basis = Basis::where('pid', $pid)->first();
        $bid = $basis->id;
        $hid = $this->hospid;
        $mlog = false;

        // 沒有建過血糖
        foreach($request->input('mresult') as $mrslt) {
            $object = (object) $mrslt;

            if ($object->d1 != "") {
                $mresult = $this->save2mresult($bid, $hid, $pid, $object->mdate, 9, $object->d1);
                $mlog = true;
            }
            if ($object->d2 != "") {
                $mresult = $this->save2mresult($bid, $hid, $pid, $object->mdate, 1, $object->d2);
                $mlog = true;
            }
            if ($object->d3 != "") {
                $mresult = $this->save2mresult($bid, $hid, $pid, $object->mdate, 2, $object->d3);
                $mlog = true;
            }
            if ($object->d4 != "") {
                $mresult = $this->save2mresult($bid, $hid, $pid, $object->mdate, 3, $object->d4);
                $mlog = true;
            }
            if ($object->d5 != "") {
                $mresult = $this->save2mresult($bid, $hid, $pid, $object->mdate, 4, $object->d5);
                $mlog = true;
            }
            if ($object->d6 != "") {
                $mresult = $this->save2mresult($bid, $hid, $pid, $object->mdate, 5, $object->d6);
                $mlog = true;
            }
            if ($object->d7 != "") {
                $mresult = $this->save2mresult($bid, $hid, $pid, $object->mdate, 6, $object->d7);
                $mlog = true;
            }
            if ($object->d8 != "") {
                $mresult = $this->save2mresult($bid, $hid, $pid, $object->mdate, 7, $object->d8);
                $mlog = true;
            }
            if ($object->d9 != "") {
                $mresult = $this->save2mresult($bid, $hid, $pid, $object->mdate, 8, $object->d9);
                $mlog = true;
            }
            if ($object->d10 != "") {
                $mrnotes = Mrnote::where('hosp_id', $hid)->where('pid', $pid)->where('mr_date', $object->mdate)->first();
                if ($mrnotes) {
                    $mrnotes->note = $mrnotes->note . "\n" . $object->d10; // 插入new line
                    $mrnotes->educator = $this->uid;
                    $mrnotes->save();
                    LogsController::saveLog('mrnote', 'update(更新)', $this->ip, $this->useragent);
                } else {
                    // 沒有建過備註
                    $mrnote = new Mrnote;
                    $mrnote->hosp_id = $hid;
                    $mrnote->pid = $pid;
                    $mrnote->mr_date = $object->mdate;
                    $mrnote->note = $object->d10;
                    $mrnote->educator = $this->uid;
                    $mrnote->save();
                    LogsController::saveLog('mrnote', 'store(保存)', $this->ip, $this->useragent);
                }
            }
        }
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => $mrnote
//        ]);
        if ($mlog == true) {
            LogsController::saveLog('mresult', 'store(保存)', $this->ip, $this->useragent);
            return $this->respondWith($mresult, new MresultTransformer);
        } else {
            return $this->respondWithArray([
                'status' => 'success',
                'message' => 0
            ]);
        }
    }

    private function save2mresult($bid, $hid, $pid, $mdate, $slot, $d)
    {
        $slotarray = array(
            9 => "凌晨",
            1 => "晨起",
            2 => "早餐前",
            3 => "早餐後",
            4 => "中餐前",
            5 => "中餐後",
            6 => "晚餐前",
            7 => "晚餐後",
            8 => "睡前"
        );
        $timearray = array(
            9 => "00:01:00",
            1 => "06:01:00",
            2 => "07:01:00",
            3 => "09:01:00",
            4 => "11:01:00",
            5 => "13:01:00",
            6 => "17:01:00",
            7 => "19:01:00",
            8 => "20:31:00"
        );
        $timearr = array(
            9 => array("HH" => "00", "mm" => "01", "ss" => "00"),
            1 => array("HH" => "06", "mm" => "01", "ss" => "00"),
            2 => array("HH" => "07", "mm" => "01", "ss" => "00"),
            3 => array("HH" => "09", "mm" => "01", "ss" => "00"),
            4 => array("HH" => "11", "mm" => "01", "ss" => "00"),
            5 => array("HH" => "13", "mm" => "01", "ss" => "00"),
            6 => array("HH" => "17", "mm" => "01", "ss" => "00"),
            7 => array("HH" => "19", "mm" => "01", "ss" => "00"),
            8 => array("HH" => "20", "mm" => "31", "ss" => "00")
        );

        $mresult = new Mresult;
        $mresult->basis_id = $bid;
        $mresult->hosp_id = $hid;
        $mresult->pid = $pid;
        $mresult->mr_date = substr($mdate, 0, 10) . " " . $timearray[$slot];
        $mresult->mr_time = json_encode($timearr[$slot], JSON_UNESCAPED_UNICODE);
        $mresult->slot = $slot;
        $mresult->slotname = $slotarray[$slot];
        $mresult->value1 = $d;
        $mresult->save();
        return $mresult;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyBatch(Request $request, $id)
    {
        if($id != 1) {
            return $this->respondWithArray([
                'success' => false,
                'message' => '批次刪除血糖值失敗!!'
            ]);
        }

        // 批次
        foreach($request->input('mresult') as $mrslt) {
            $object = (object)$mrslt;
            $mresults = Mresult::findOrFail($object->id);
            $mresults->delete();
        }

        LogsController::saveLog('mresult', 'destroy(删除)', $this->ip, $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '批次刪除血糖值成功!!'
        ]);
    }

}
