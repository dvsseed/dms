<?php

namespace App\Http\Controllers\Api;

use Auth;
use Config;
use DB;
use Excel;
use File;
use Response;
use App\Basis;
use App\Cases;
use App\Checks;
use App\Realsun;
use App\Rawdatavpn;
use Illuminate\Http\Request;
use App\Transformers\CasesTransformer;
use App\Transformers\Cases1Transformer;
// use App\Transformers\Cases2Transformer;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CasesController extends ApiController
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
        return $this->respondWith(Cases::all(), new CasesTransformer);
//        return $this->respondWith(DB::table('cases')->leftJoin('basis', 'basis.id', '=', 'cases.basis_id')->select('cases.id', 'cases.case_date', 'basis.name', 'cases.pid', 'cases.cure_stage')->where('cases.hosp_id', $this->hospid)->orderBy('cases.created_at', 'DESC')->paginate(20), new CasesTransformer);

//        return $cases;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Response
     */
    public function show($id)
    {
        $cases = Cases::where('id', $id)->first();

        return $this->respondWith($cases, new CasesTransformer);
    }

    public function showAll()
    {
//        $cases = Cases::where('hosp_id', $hid)->first();
        $cases = DB::table('cases')
            ->leftJoin('basis', 'basis.id', '=', 'cases.basis_id')
            ->select('cases.id', 'cases.case_date', 'basis.name', 'cases.pid', 'cases.cure_stage')
            ->where('cases.hosp_id', $this->hospid)
            ->orderBy('cases.created_at', 'DESC')
            ->paginate(20);
//            ->get();

//        return $this->respondWith($cases, new CasesTransformer);
        return $cases;
    }

    public function showPage(Request $request, $page = 20)
    {
        $cases = DB::table('cases')
            ->leftJoin('basis', 'basis.id', '=', 'cases.basis_id')
            ->select('cases.id', 'cases.case_date', 'basis.name', 'cases.pid', 'cases.cure_stage', 'cases.prsn_id')
            ->where('cases.hosp_id', $this->hospid)
            ->orderBy('cases.created_at', 'DESC')
            ->paginate($page);

        return $cases;

//        return $this->respondWith(DB::table('cases')->leftJoin('basis', 'basis.id', '=', 'cases.basis_id')->select('cases.case_date', 'basis.name', 'cases.pid', 'cases.cure_stage')->where('cases.hosp_id', $this->hospid)->orderBy('cases.created_at', 'DESC')->paginate($page), new Cases2Transformer);
    }

    public function showId1($id)
    {
        $cases = Cases::where('id', $id)->first();

        return $this->respondWith($cases, new Cases1Transformer);
    }

    public function checkPid(Request $request, $pid)
    {
//        $dd = floor( ( time() - strtotime('2016-12-19') ) / 60 / 60 / 24 );
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => 'test'
//        ]);

        $msg = null;
        $hid = $this->hospid;
        $cases = Cases::where('hosp_id', $hid)->where('pid', $pid)->orderBy('case_date', 'DESC')->first();
        if ($cases) {
            $laststage = $cases->cure_stage;
            if ($laststage == 'DM結案' || $laststage == 'CKD結案') {
                $lastdate = $cases->close_date;
            } else {
                $lastdate = $cases->case_date;
            }
        } else {
            // 沒有建過方案
            return $this->respondWithArray([
                'status' => 'error',
                'message' => $cases
            ]);
        }
        if ($request->dm) {
            $thisstage = $request->dm;
        } else if ($request->undm) {
            $thisstage = $request->undm;
        } else if ($request->offdm) {
            $thisstage = $request->offdm;
        }
//        $today = date('Y-m-d');

        // check DM方案
//        dm: 'DM初診','DM複診','DM年度'
//        offdm: 'DM結案'
        // 1.初診-複診: 間隔7週(49天之後), > 49 days)
        $d = 60 * 60 * 24;
        $datediff = floor( ( time() - strtotime($lastdate) ) / $d );
        if ( $laststage == 'DM初診' && $thisstage == 'DM複診' && $datediff < 49 ) {
            $msg .= "初診-複診 < 49 天 \r\n";
        }
        // 2.複診-複診: 間隔10週(70天之後), 複診一年365天(複診+複診+複診)只能申請最多3次, > 70 days and (<= 3 at current year)
        $countcases = Cases::where('hosp_id', $hid)->where('pid', $pid)->where('cure_stage', 'DM複診')->orderBy('case_date', 'DESC')->count();
        if ( $laststage == 'DM複診' && $thisstage == 'DM複診' && $datediff < 70 ) {
            $msg .= "複診-複診 < 70 天 \r\n";
        }
        if ( $laststage == 'DM複診' && $thisstage == 'DM複診' && $countcases > 3 ) {
            $msg .= "複診+複診+複診: 只能申請3次 \r\n";
        }
        // 3.複診-年度: 間隔10週(70天之後), (初診+複診+複診)或(初診+複診+複診+複診)或(複診+複診+複診)才能申請, 一年度([年度]不能跨年)只能申請1次, 禁止: (初診+複診+年度)或(複診+複診+年度), > 70 days and conditions and (<= 1 at current year)
        if ( $laststage == 'DM複診' && $thisstage == 'DM年度' && $datediff < 70 ) {
            $msg .= "複診-年度: < 70天 \r\n";
        }
        if ( $laststage == 'DM初診' && $thisstage == 'DM年度' && $countcases == 1 ) {
            $msg .= "禁止: 初診+複診+年度 \r\n";
        }
        if ( $laststage == 'DM複診' && $thisstage == 'DM年度' && $countcases == 2 ) {
            $msg .= "禁止: 複診+複診+年度 \r\n";
        }
        if ( $laststage == 'DM複診' && $thisstage == 'DM年度' && $countcases > 3 ) {
            $msg .= "複診+複診+複診+年度: 複診只能申請3次 \r\n";
        }
        // 4.無結案者, 可循環 2.3~2.4
        // 5.結案者, 要過 365天之後才可循環 2.1~2.4
        if ( $laststage == 'DM結案' && $datediff < 365 ) {
            $msg .= "已結案者, 要過 365天 後才可申請 \r\n";
        }
        // 6.兩個診別之間不可大於等於365天(大於1年)
        if ( $laststage == 'DM初診' && $thisstage == 'DM複診' && $datediff >= 365 ) {
            $msg .= "兩個診別間不可大於等於365天 \r\n";
        }
        if ( $laststage == 'DM複診' && $thisstage == 'DM複診' && $datediff >= 365 ) {
            $msg .= "兩個診別間不可大於等於365天 \r\n";
        }
        if ( $laststage == 'DM複診' && $thisstage == 'DM年度' && $datediff >= 365 ) {
            $msg .= "兩個診別間不可大於等於365天 \r\n";
        }
        // 7.一天內不可重複申請方案(不可重複新增第二筆)
        if ( $laststage == 'DM初診' && $thisstage == 'DM初診' && $datediff == 0 ) {
            $msg .= "一天內不可重複申請方案 \r\n";
        }
        if ( $laststage == 'DM複診' && $thisstage == 'DM複診' && $datediff == 0 ) {
            $msg .= "一天內不可重複申請方案 \r\n";
        }
        if ( $laststage == 'DM年度' && $thisstage == 'DM年度' && $datediff == 0 ) {
            $msg .= "一天內不可重複申請方案 \r\n";
        }

        return $this->respondWithArray([
            'status' => 'error',
            'message' => $msg
        ]);

//        dm: 'CKD初診','CKD初診+DM複診','CKD+DM複診','CKD複診+DM年度'
//        undm: '非方案DM初診','非方案DM複診','非方案DM年度','一般'
//        offdm: 'CKD結案'
        // 尚未完成

//        return $this->respondWith($cases, new Cases1Transformer);
    }

    public function closeCase(Request $request)
    {
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => '測試中!!'
//        ]);
        // 尋找 Basis[id]
        $basis = Basis::where('pid', $request->pid)->first();

        // 新增 健保方案
        $cases = new Cases;
        $cases->basis_id = $basis->id;
        $cases->hosp_id = $request->hosp_id;
        $cases->pid = $request->pid;
        if ($request->offdm != null) {
            $cases->cure_stage = $request->offdm;
            $cases->close_date = $request->close_date;
        }
        $cases->case_date = date('Y-m-d');
        $cases->educator = $this->uid;
        $cases->blood_mne = "早";
        $cases->blood_ap = "前";
        $cases->smoking = "無";
        $cases->foot_chk_left = json_encode(["正常"], JSON_UNESCAPED_UNICODE);
        $cases->foot_chk_right = json_encode(["正常"], JSON_UNESCAPED_UNICODE);
        $cases->ulcers = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->eye_chk8 = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->abi = json_encode(["正常"], JSON_UNESCAPED_UNICODE);
        $cases->cavi = json_encode(["正常"], JSON_UNESCAPED_UNICODE);
        $cases->kidneydisease = "無";
        $cases->kidneydisease_stage = "";
        $cases->neuropathy = "無";
        $cases->vascularlesion = "無";
        $cases->intermittentpain = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->cataract = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->ecg = json_encode(["正常"], JSON_UNESCAPED_UNICODE);
        $cases->coronaryheart = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->chh_year = "不詳";
        $cases->chh_month = "不詳";
        $cases->stroke = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->sh_year = "不詳";
        $cases->sh_month = "不詳";
        $cases->blindness = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->bh_year = "不詳";
        $cases->bh_month = "不詳";
        $cases->dialysis = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->dh_year = "不詳";
        $cases->dh_month = "不詳";
        $cases->amputation = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->ah_year = "不詳";
        $cases->ah_month = "不詳";
        $cases->drinking = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->medicaltreatment = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->followdisease = json_encode(["無"], JSON_UNESCAPED_UNICODE);

        $cases->save();

        LogsController::saveLog('cases', 'close(結案)', $this->ip, $this->useragent);
        return $this->respondWith($cases, new Cases1Transformer);
//        LogsController::saveLog('cases', 'update(更新-結案)', $this->ip, $this->useragent);
//        return $this->respondWith($cases, new CasesTransformer);
    }

    public function showPid($pid)
    {
        $cases = Cases::where('pid', $pid)->first();

        return $this->respondWith($cases, new CasesTransformer);
    }

    public function showPid1($pid)
    {
        $cases = Cases::where('pid', $pid)->first();

        return $this->respondWith($cases, new Cases1Transformer);
    }

    public function listDMCase($pid)
    {
//      ->select(DB::raw('"T2DM" as type, log_date as createdate, CASE closed WHEN "歿" THEN DATE_FORMAT(updated_at, "%Y-%m-%d") ELSE "" END as close_date'))
//        $t2dm = DB::table('cases')
//            ->select(DB::raw('case_date, cure_stage, close_date'))
//            ->where('pid', $pid)
//            ->orderBy('case_date', 'DESC')
//            ->get();
        // $users = DB::table('users')->whereNull('last_name')->union($first)->get();
        $hid = $this->hospid;

        // 先插入temp資料表
        DB::delete("delete from temp_cases");
        DB::statement("INSERT INTO temp_cases (hosp_id,pid,cdate,citem,cday) SELECT '$hid' AS hosp_id,'$pid' AS pid,CURDATE() AS cdate,'今日' AS citem,DATEDIFF(CURDATE(),(SELECT (CASE WHEN cure_stage='DM結案' THEN close_date WHEN cure_stage='CKD結案' THEN close_date ELSE case_date END) AS cdate FROM cases WHERE hosp_id='$hid' AND pid='$pid' AND (CASE WHEN cure_stage='DM結案' THEN close_date WHEN cure_stage='CKD結案' THEN close_date ELSE case_date END)<CURDATE() ORDER BY cdate DESC LIMIT 1)) AS cday");
        DB::statement("INSERT INTO temp_cases (hosp_id,pid,cdate,citem,cday) SELECT hosp_id,pid,CASE WHEN cure_stage='DM結案' THEN close_date WHEN cure_stage='CKD結案' THEN close_date ELSE case_date END AS cdate,cure_stage,'' FROM cases WHERE hosp_id='$hid' AND pid='$pid' ORDER BY cdate DESC");

        // 再list清單
//        $dmcase = DB::select(DB::raw("SELECT cdate,citem,IFNULL(DATEDIFF(cdate,(SELECT cdate FROM temp_cases WHERE hosp_id=ta.hosp_id AND pid=ta.pid AND cdate<ta.cdate AND (citem='今日' OR citem='DM初診' OR citem='DM複診' OR citem='DM年度' OR citem='DM結案') ORDER BY cdate DESC LIMIT 1)),'') AS cday FROM temp_cases ta WHERE hosp_id='$hid' AND pid='$pid' AND (citem='今日' OR citem='DM初診' OR citem='DM複診' OR citem='DM年度' OR citem='DM結案') ORDER BY cdate DESC"));
        $dmcase = DB::select(DB::raw("SELECT cdate,citem,DATEDIFF(cdate,(SELECT cdate FROM temp_cases WHERE hosp_id=ta.hosp_id AND pid=ta.pid AND cdate<=ta.cdate AND (citem='DM初診' OR citem='DM複診' OR citem='DM年度' OR citem='DM結案') AND id>ta.id ORDER BY cdate DESC,citem DESC LIMIT 1)) AS cday FROM temp_cases ta WHERE hosp_id='$hid' AND pid='$pid' AND (citem='今日' OR citem='DM初診' OR citem='DM複診' OR citem='DM年度' OR citem='DM結案') ORDER BY cdate DESC"));

        return $dmcase;
    }

    public function listCKDCase($pid)
    {
        $hid = $this->hospid;

        // 先插入temp資料表
        DB::delete("delete from temp_cases");
        DB::statement("INSERT INTO temp_cases (hosp_id,pid,cdate,citem,cday) SELECT '$hid' AS hosp_id,'$pid' AS pid,CURDATE() AS cdate,'今日' AS citem,DATEDIFF(CURDATE(),(SELECT (CASE WHEN cure_stage='DM結案' THEN close_date WHEN cure_stage='CKD結案' THEN close_date ELSE case_date END) AS cdate FROM cases WHERE hosp_id='$hid' AND pid='$pid' AND (CASE WHEN cure_stage='DM結案' THEN close_date WHEN cure_stage='CKD結案' THEN close_date ELSE case_date END)<CURDATE() ORDER BY cdate DESC LIMIT 1)) AS cday");
        DB::statement("INSERT INTO temp_cases (hosp_id,pid,cdate,citem,cday) SELECT hosp_id,pid,CASE WHEN cure_stage='DM結案' THEN close_date WHEN cure_stage='CKD結案' THEN close_date ELSE case_date END AS cdate,cure_stage,'' FROM cases WHERE hosp_id='$hid' AND pid='$pid' ORDER BY cdate DESC");

        // 再list清單
//        $ckdcase = DB::select(DB::raw("SELECT cdate,citem,IFNULL(DATEDIFF(cdate,(SELECT cdate FROM temp_cases WHERE hosp_id=ta.hosp_id AND pid=ta.pid AND cdate<ta.cdate AND (citem='今日' OR citem='CKD初診' OR citem='CKD初診+DM複診' OR citem='CKD+DM複診' OR citem='CKD複診+DM年度' OR citem='CKD結案') ORDER BY cdate DESC LIMIT 1)),'') AS cday FROM temp_cases ta WHERE hosp_id='$hid' AND pid='$pid' AND citem in ('今日','CKD初診','CKD初診+DM複診','CKD+DM複診','CKD複診+DM年度','CKD結案') ORDER BY cdate DESC"));
        $ckdcase = DB::select(DB::raw("SELECT cdate,citem,DATEDIFF(cdate,(SELECT cdate FROM temp_cases WHERE hosp_id=ta.hosp_id AND pid=ta.pid AND cdate<=ta.cdate AND (citem='CKD初診' OR citem='CKD初診+DM複診' OR citem='CKD+DM複診' OR citem='CKD複診+DM年度' OR citem='CKD結案') AND id>ta.id ORDER BY cdate DESC,citem DESC LIMIT 1)) AS cday FROM temp_cases ta WHERE hosp_id='$hid' AND pid='$pid' AND (citem='今日' OR citem='CKD初診' OR citem='CKD初診+DM複診' OR citem='CKD+DM複診' OR citem='CKD複診+DM年度' OR citem='CKD結案') ORDER BY cdate DESC"));

        return $ckdcase;
    }

    public function listNONDMCase($pid)
    {
        $hid = $this->hospid;

        // 先插入temp資料表
        DB::delete("delete from temp_cases");
        DB::statement("INSERT INTO temp_cases (hosp_id,pid,cdate,citem,cday) SELECT '$hid' AS hosp_id,'$pid' AS pid,CURDATE() AS cdate,'今日' AS citem,DATEDIFF(CURDATE(),(SELECT (CASE WHEN cure_stage='DM結案' THEN close_date WHEN cure_stage='CKD結案' THEN close_date ELSE case_date END) AS cdate FROM cases WHERE hosp_id='$hid' AND pid='$pid' AND (CASE WHEN cure_stage='DM結案' THEN close_date WHEN cure_stage='CKD結案' THEN close_date ELSE case_date END)<CURDATE() ORDER BY cdate DESC LIMIT 1)) AS cday");
        DB::statement("INSERT INTO temp_cases (hosp_id,pid,cdate,citem,cday) SELECT hosp_id,pid,CASE WHEN cure_stage='DM結案' THEN close_date WHEN cure_stage='CKD結案' THEN close_date ELSE case_date END AS cdate,cure_stage,'' FROM cases WHERE hosp_id='$hid' AND pid='$pid' ORDER BY cdate DESC");

        // 再list清單
//        $nondmcase = DB::select(DB::raw("SELECT cdate,citem,IFNULL(DATEDIFF(cdate,(SELECT cdate FROM temp_cases WHERE hosp_id=ta.hosp_id AND pid=ta.pid AND cdate<ta.cdate AND (citem='今日' OR citem='非方案DM初診' OR citem='非方案DM複診' OR citem='非方案DM年度' OR citem='一般') ORDER BY cdate DESC LIMIT 1)),'') AS cday FROM temp_cases ta WHERE hosp_id='$hid' AND pid='$pid' AND (citem='今日' OR citem='非方案DM初診' OR citem='非方案DM複診' OR citem='非方案DM年度' OR citem='一般') ORDER BY cdate DESC"));
        $nondmcase = DB::select(DB::raw("SELECT cdate,citem,DATEDIFF(cdate,(SELECT cdate FROM temp_cases WHERE hosp_id=ta.hosp_id AND pid=ta.pid AND cdate<=ta.cdate AND (citem='非方案DM初診' OR citem='非方案DM複診' OR citem='非方案DM年度' OR citem='一般') AND id>ta.id ORDER BY cdate DESC,citem DESC LIMIT 1)) AS cday FROM temp_cases ta WHERE hosp_id='$hid' AND pid='$pid' AND (citem='今日' OR citem='非方案DM初診' OR citem='非方案DM複診' OR citem='非方案DM年度' OR citem='一般') ORDER BY cdate DESC"));

        return $nondmcase;
    }

    public function checkCSV()
    {
        // list CSV
        $csvs = DB::select(DB::raw("SELECT DISTINCT cases.pid FROM cases LEFT JOIN checks ON checks.pid=cases.pid WHERE (cases.cure_stage='DM年度' OR cases.cure_stage='CKD+DM複診' OR cases.cure_stage='CKD複診+DM年度') AND CONCAT(LEFT(checks.check_date,4)+1911,'-',SUBSTRING(checks.check_date,6,2),'-',RIGHT(checks.check_date,2))=cases.case_date AND checks.pid IS NOT NULL AND (cases.blood_hba1c IS NULL OR cases.blood_ldl IS NULL OR cases.blood_tg IS NULL OR cases.egfr IS NULL OR cases.blood_creat IS NULL OR cases.blood_hba1c = 0 OR cases.blood_ldl = 0 OR cases.blood_tg = 0 OR cases.egfr = 0 OR cases.blood_creat = 0 OR (cases.urine_micro IS NULL AND cases.upcr IS NULL) OR (cases.urine_micro = 0 AND cases.upcr = 0)) AND cases.hosp_id=? ORDER BY cases.pid DESC"), ["$this->hospid"]);

        return $csvs;
    }

    public function checkCSV1()
    {
        // list CSV
        $csvs = DB::select(DB::raw("SELECT DISTINCT cases.pid FROM cases LEFT JOIN checks ON checks.pid=cases.pid WHERE cases.cure_stage='DM複診' AND CONCAT(LEFT(checks.check_date,4)+1911,'-',SUBSTRING(checks.check_date,6,2),'-',RIGHT(checks.check_date,2))=cases.case_date AND checks.pid IS NOT NULL AND (cases.blood_hba1c IS NULL OR cases.blood_hba1c = 0) AND cases.hosp_id=? ORDER BY cases.pid DESC"), ["$this->hospid"]);

        return $csvs;
    }

    public function compareCases()
    {
        // list Cases
        // $hiscase = DB::select(DB::raw("SELECT realsun.drugcode AS rcode,realsun.check_date AS rdate,realsun.pid AS rpid,cases.cure_stage AS cstage,cases.case_date AS cdate,cases.pid AS cpid FROM realsun LEFT JOIN cases ON realsun.pid = cases.pid WHERE CONCAT(LEFT(realsun.check_date, 3) + 1911, '-', SUBSTRING(realsun.check_date, 5, 2), '-', RIGHT(realsun.check_date, 2)) = cases.case_date AND cases.hosp_id=? ORDER BY realsun.check_date DESC"), ["$this->hospid"]);
        $hiscase = DB::select(DB::raw("SELECT realsun.drugcode AS rcode,realsun.check_date AS rdate,realsun.pid AS rpid,(SELECT cure_stage FROM cases WHERE realsun.pid=cases.pid AND CONCAT(LEFT(realsun.check_date,3)+1911,'-',SUBSTRING(realsun.check_date,5,2),'-',RIGHT(realsun.check_date,2))=cases.case_date AND cases.hosp_id=?) AS cstage,(SELECT case_date FROM cases WHERE realsun.pid=cases.pid AND CONCAT(LEFT(realsun.check_date,3)+1911,'-',SUBSTRING(realsun.check_date,5,2),'-',RIGHT(realsun.check_date,2))=cases.case_date AND cases.hosp_id=?) AS cdate,(SELECT pid FROM cases WHERE realsun.pid=cases.pid AND CONCAT(LEFT(realsun.check_date,3)+1911,'-',SUBSTRING(realsun.check_date,5,2),'-',RIGHT(realsun.check_date,2))=cases.case_date AND cases.hosp_id=?) AS cpid FROM realsun ORDER BY check_date DESC"), ["$this->hospid", "$this->hospid", "$this->hospid"]);

        return $hiscase;
    }

    public function duplicateCases()
    {
        // list Cases
        $dupcase = DB::select(DB::raw("SELECT cure_stage,case_date,pid,COUNT(id) AS n FROM cases WHERE cases.hosp_id=? GROUP BY cure_stage, case_date, pid HAVING n > 1 ORDER BY case_date DESC"), ["$this->hospid"]);

        return $dupcase;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cases = Cases::where('id', $id)->first();

        return $this->respondWith($cases, new Cases1Transformer);
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

        // 尋找 Cases[pid]
        //$cases = Cases::where('pid', $request->pid)->first();
//        if ($cases) {
//            $this->validate($request, [
//                'pid' => 'required|max:10',
//            ]);
//        }

        // 尋找 Basis[id]
        $basis = Basis::where('pid', $request->pid)->first();

        // 新增 健保方案
        $cases = new Cases;
        $cases->basis_id = $basis->id;
        $cases->hosp_id = $request->hosp_id;
        $cases->pid = $request->pid;
        if ($request->dm != null) {
            $cases->cure_stage = $request->dm;
        }
        if ($request->undm != null) {
            $cases->cure_stage = $request->undm;
        }
        if ($request->offdm != null) {
            $cases->cure_stage = $request->offdm;
            $cases->close_date = date('Y-m-d');
        }
        $cases->case_date = date('Y-m-d');
        $cases->prsn_id = "G120833360";  // 強迫先預設: 游能俊醫師
        $cases->educator = $this->uid;
        $cases->blood_mne = "早";
        $cases->blood_ap = "前";
        $cases->periodontal = "無";
        $cases->masticatory = "正常";
        $cases->smoking = "無";
        $cases->foot_chk_right = json_encode(["正常"], JSON_UNESCAPED_UNICODE);
        $cases->foot_chk_left = json_encode(["正常"], JSON_UNESCAPED_UNICODE);
        $cases->ulcers = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->eye_chk8 = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->abi = json_encode(["正常"], JSON_UNESCAPED_UNICODE);
        $cases->cavi = json_encode(["正常"], JSON_UNESCAPED_UNICODE);
        $cases->kidneydisease = "無";
        $cases->kidneydisease_stage = "";
        $cases->neuropathy = "無";
        $cases->vascularlesion = "無";
        $cases->intermittentpain = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->cataract = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->ecg = json_encode(["正常"], JSON_UNESCAPED_UNICODE);
        $cases->coronaryheart = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->chh_year = "不詳";
        $cases->chh_month = "不詳";
        $cases->stroke = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->sh_year = "不詳";
        $cases->sh_month = "不詳";
        $cases->blindness = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->bh_year = "不詳";
        $cases->bh_month = "不詳";
        $cases->dialysis = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->dh_year = "不詳";
        $cases->dh_month = "不詳";
        $cases->amputation = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->ah_year = "不詳";
        $cases->ah_month = "不詳";
        $cases->medicaltreatment = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->drinking = json_encode(["無"], JSON_UNESCAPED_UNICODE);
        $cases->followdisease = json_encode(["無"], JSON_UNESCAPED_UNICODE);

        $cases->save();

        LogsController::saveLog('cases', 'store(保存)', $this->ip, $this->useragent);
        return $this->respondWith($cases, new Cases1Transformer);
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

        $cases = Cases::findOrFail($id);
        $cases->case_date = $request->case_date;
        $cases->prsn_id = $request->prsn_id;
        $cases->base_sbp = $request->base_sbp;
        $cases->base_ebp = $request->base_ebp;
        $cases->pulse = $request->pulse;
        $cases->base_weight = $request->base_weight;
        $cases->noweight = $request->noweight;
        $cases->blood_mne = $request->blood_mne;
        $cases->blood_ap = $request->blood_ap;
        $cases->blood_acpc = $request->blood_acpc;
        $cases->blood_mins = $request->blood_mins;
        $cases->blood_hba1c = $request->blood_hba1c;
        $cases->smoking = $request->smoking;
        $cases->havesmoke = $request->havesmoke;
        $cases->quitsmoke = $request->quitsmoke;

        // update
        $cases->cure_stage = $request->cure_stage;
        $cases->base_tall = $request->base_tall;
        $cases->ibw = $request->ibw;
        $cases->base_bmi = $request->base_bmi;
        $cases->waist = $request->waist;
        $cases->hips = $request->hips;
        $cases->foot_chk_left = json_encode($request->foot_chk_left, JSON_UNESCAPED_UNICODE);
        $cases->foot_chk_right = json_encode($request->foot_chk_right, JSON_UNESCAPED_UNICODE);
        $cases->ulcers = json_encode($request->ulcers, JSON_UNESCAPED_UNICODE);
        $cases->ulcers_urgent = $request->ulcers_urgent;
        $cases->ulcers_slow = $request->ulcers_slow;
        $cases->kidneydisease = $request->kidneydisease;
        $cases->kidneydisease_stage = $request->kidneydisease_stage;
        $cases->intermittentpain = json_encode($request->intermittentpain, JSON_UNESCAPED_UNICODE);
        $cases->abi = json_encode($request->abi, JSON_UNESCAPED_UNICODE);
        $cases->abi_right = $request->abi_right;
        $cases->abi_left = $request->abi_left;
        $cases->cavi = json_encode($request->cavi, JSON_UNESCAPED_UNICODE);
        $cases->cavi_right = $request->cavi_right;
        $cases->cavi_left = $request->cavi_left;
        $cases->cavi_rkcavi = $request->cavi_rkcavi;
        $cases->eye_chk8 = json_encode($request->eye_chk8, JSON_UNESCAPED_UNICODE);
        $cases->eye_chk8_right = $request->eye_chk8_right;
        $cases->eye_chk8_left = $request->eye_chk8_left;
        $cases->cataract = json_encode($request->cataract, JSON_UNESCAPED_UNICODE);
        $cases->ecg = json_encode($request->ecg, JSON_UNESCAPED_UNICODE);
        $cases->ecgitem = $request->ecgitem;
        $cases->ecgother = $request->ecgother;
        $cases->coronaryheart = json_encode($request->coronaryheart, JSON_UNESCAPED_UNICODE);
        $cases->coronaryheart_item = $request->coronaryheart_item;
        $cases->coronaryheart_other = $request->coronaryheart_other;
        $cases->chh_year = $request->chh_year;
        $cases->chh_month = $request->chh_month;
        $cases->stroke = json_encode($request->stroke, JSON_UNESCAPED_UNICODE);
        $cases->stroke_other = $request->stroke_other;
        $cases->sh_year = $request->sh_year;
        $cases->sh_month = $request->sh_month;
        $cases->blindness = json_encode($request->blindness, JSON_UNESCAPED_UNICODE);
        $cases->blindness_right = $request->blindness_right;
        $cases->blindness_left = $request->blindness_left;
        $cases->bh_year = $request->bh_year;
        $cases->bh_month = $request->bh_month;
        $cases->dialysis = json_encode($request->dialysis, JSON_UNESCAPED_UNICODE);
        $cases->dialysis_item = $request->dialysis_item;
        $cases->dh_year = $request->dh_year;
        $cases->dh_month = $request->dh_month;
        $cases->amputation = json_encode($request->amputation, JSON_UNESCAPED_UNICODE);
        $cases->amputation_right = $request->amputation_right;
        $cases->amputation_left = $request->amputation_left;
        $cases->ah_year = $request->ah_year;
        $cases->ah_month = $request->ah_month;
        $cases->amputation_other = $request->amputation_other;
        $cases->medicaltreatment = json_encode($request->medicaltreatment, JSON_UNESCAPED_UNICODE);
        $cases->medicaltreatment_times = $request->medicaltreatment_times;
        $cases->drinking = json_encode($request->drinking, JSON_UNESCAPED_UNICODE);
        $cases->drinkingother = $request->drinkingother;
        $cases->periodontal = $request->periodontal;
        $cases->masticatory = $request->masticatory;
        $cases->checker = $request->checker;
        $cases->smoking = $request->smoking;
        $cases->havesmoke = $request->havesmoke;
        $cases->quitsmoke = $request->quitsmoke;
        $cases->followdisease = json_encode($request->followdisease, JSON_UNESCAPED_UNICODE);
        if ($request->offdm == null) {
            $cases->close_date = null;
        } else {
            $cases->close_date = $request->close_date;
        }
        $cases->laboratory = $request->laboratory;
        $cases->bgmachine = $request->bgmachine;
        $cases->machine_blood = $request->machine_blood;
        $cases->machine_use = $request->machine_use;
        $cases->machine_exception = $request->machine_exception;
        $cases->machine_other = $request->machine_other;
        $cases->neuropathy = $request->neuropathy;
        $cases->vascularlesion = $request->vascularlesion;
        // examine
        $cases->cholesterol = $request->cholesterol;
        $cases->blood_tg = $request->blood_tg;
        $cases->blood_ldl = $request->blood_ldl;
        $cases->hdl = $request->hdl;
        $cases->gpt = $request->gpt;
        $cases->blood_creat = $request->blood_creat;
        $cases->uricacid = $request->uricacid;
        $cases->urine_micro = $request->urine_micro;
        $cases->upcr = $request->upcr;
        $cases->egfr = $request->egfr;
        $cases->urine_routine = $request->urine_routine;

        $cases->save();

        LogsController::saveLog('cases', 'update(更新)', $this->ip, $this->useragent);
        return $this->respondWith($cases, new CasesTransformer);
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
        $cases = Cases::findOrFail($id);
        $cases->delete();

        LogsController::saveLog('cases', 'destroy(删除)', $this->ip, $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '刪除健保方案成功!!'
        ]);
    }

    public function showPid2($pid)
    {
        $hid = $this->hospid;
        $cases = DB::table('cases')
            ->select('case_date', 'blood_hba1c')
            ->addSelect(DB::raw('ROUND(blood_hba1c, 1) as hba1c'))
            ->where('hosp_id', '=', $hid)
            ->where('pid', '=', $pid)
            ->where('cure_stage', '!=', '一般')
            ->orderBy('case_date', 'DESC')
            ->take(4)
            ->get();

        return $cases;
    }

    /**
     * Import file into database Code
     *
     * @var array
     */
    public function importCSV(Request $request)
    {
        if($request->hasFile('importCSVFile')) {
            $path = $request->file('importCSVFile')->getRealPath();
            $data = Excel::load($path, function($reader) {
            }, 'UTF-8')->get();
//            $data = Excel::load($path, function($reader) {
//                $reader->get()->toArray();
//            }, 'ASCII')->get();

            if(!empty($data) && $data->count()) {
                foreach ($data->toArray() as $key => $value) {
                    try {
                        if(!empty($value)) {
                            $insert[] = [
                                'hosp_id' => $this->hospid,
                                'check_date' => $value['check_date'],
                                'name' => $value['name'],
                                'pid' => $value['pid'],
                                'birthday' => $value['birthday'],
                                'sex' => $value['sex'],
                                'items' => $value['items'],
                                'values' => $value['values'],
                                'serialno' => $value['serialno'],
                                'print_date' => $value['print_date'],
                                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                                'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                            ];
                        }
                    } catch (\Illuminate\Database\QueryException $e) {
                        return $this->respondWithArray([
                            'status' => 'error',
                            'message' => '資料庫端發生錯誤!!'
                        ]);
                    } catch (\Exception $e) {
                        return $this->respondWithArray([
                            'status' => 'error',
                            'message' => '欄位名稱不相符!!'
                        ]);
                    }
                }

                if(!empty($insert)) {
                    try {
                        Checks::insert($insert);
                    } catch(\Exception $e) {
                        $errormsg = 'Database error::' . $e->getCode();
                        return $this->respondWithArray([
                            'status' => 'error',
                            'message' => $errormsg
                        ]);
                    }
                    LogsController::saveLog('checks', 'import(匯入)', $this->ip, $this->useragent);
                    return $this->respondWithArray([
                        'status' => 'success',
                        'message' => '匯入資料成功!!'
                    ]);
                }
            }
        }

        return $this->respondWithArray([
            'status' => 'error',
            'message' => '匯入資料失敗, 請檢查...資料是否有誤!!'
        ]);
    }

    public function importExcel(Request $request, $type)
    {
        $dcode = null;
        switch ($type) {
            case 1:
                $dcode = 'P4301C';
                break;
            case 2:
                $dcode = 'P4302C';
                break;
            case 7:
                $dcode = 'P1407C';
                break;
            case 8:
                $dcode = 'P1408C';
                break;
            case 9:
                $dcode = 'P1409C';
                break;
        }

        if($request->hasFile('importXLSFile')) {
            $path = $request->file('importXLSFile')->getRealPath();
            $data = Excel::selectSheets('Sheet1')->load($path, function($reader) {
                // Getting all results
                // $results = $reader->get();
                // Skip 2 results
                // $reader->skip(2);
            }, 'UTF-8')->get();

//            return $this->respondWithArray([
//                'status' => 'debug',
//                'message' => $data->toArray()
//            ]);

            if(!empty($data) && $data->count()) {
                foreach ($data->toArray() as $key => $value) {
                    try {
                        if(!empty($value) && trim($value['name']) != '合計') {
                            $insert[] = [
                                'hosp_id' => $this->hospid,
                                'drugcode' => $dcode,
                                'check_date' => $value['check_date'],
                                'checkno' => $value['checkno'],
                                'name' => trim($value['name']),
                                'birthday' => $value['birthday'],
                                'pid' => $value['pid'],
                                'tel' => trim($value['tel']),
                                'diseasename' => trim($value['diseasename']),
                                'prescriptiondose' => trim($value['prescriptiondose']),
                                'expirydate' => $value['expirydate'],
                                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                                'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                            ];
                        }
                    } catch (\Illuminate\Database\QueryException $e) {
                        return $this->respondWithArray([
                            'status' => 'error',
                            'message' => '資料庫端發生錯誤!!'
                        ]);
                    } catch (\Exception $e) {
                        return $this->respondWithArray([
                            'status' => 'error',
                            'message' => '欄位名稱不相符!!'
                        ]);
                    }
                }

                if(!empty($insert)) {
                    try {
                        Realsun::insert($insert);
                    } catch(\Exception $e) {
                        $errormsg = 'Database error::' . $e->getCode();  // . ': ' . $e->getMessage();
//                        return back()->with('error', $errormsg);
//                        return redirect('/')->with('error', $errormsg);
                        // return Response::json(['status'=>'error', 'message'=>$request->messages();
                        return $this->respondWithArray([
                            'status' => 'error',
                            'message' => $errormsg
                        ]);
                    }
                    LogsController::saveLog('realsun', 'import(匯入)', $this->ip, $this->useragent);
                    // return Response::json(['status'=>'success', 'message'=>'Save successful'], 200);
                    return $this->respondWithArray([
                        'status' => 'success',
                        'message' => '匯入資料成功!!'
                    ]);
                }
            }
        }

        return $this->respondWithArray([
            'status' => 'error',
            'message' => '匯入資料失敗, 請檢查...資料是否有誤!!'
        ]);
    }

    public function destroyRawdatavpn(Request $request)
    {
        $rawdatavpn = Rawdatavpn::where('hosp_id', $this->hospid)->delete();

        LogsController::saveLog('rawdatavpn', 'destroy(删除)', $this->ip, $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '刪除VPN上傳資料成功!!'
        ]);
    }

    public function destroyExcel(Request $request)
    {
        $realsun = Realsun::where('hosp_id', $this->hospid)->delete();

        LogsController::saveLog('realsun', 'destroy(删除)', $this->ip, $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '刪除HIS數據成功!!'
        ]);
    }

    public function destroyChecks(Request $request)
    {
        $checks = Checks::where('hosp_id', $this->hospid)->delete();

        LogsController::saveLog('checks', 'destroy(删除)', $this->ip, $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '刪除檢驗資料成功!!'
        ]);
    }

    public function insertCheckhistory(Request $request)
    {
        DB::insert("insert into checkhistory(`hosp_id`,`check_date`,`name`,`pid`,`birthday`,`sex`,`items`,`values`,`serialno`,`print_date`,`created_at`,`updated_at`) select `hosp_id`,`check_date`,`name`,`pid`,`birthday`,`sex`,`items`,`values`,`serialno`,`print_date`,`created_at`,`updated_at` from checks where hosp_id=?", ["$this->hospid"]);

        LogsController::saveLog('checkhistory', 'insert(插入)', $this->ip, $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '插入檢驗歷史資料成功!!'
        ]);
    }

    public function insertVpnhistory(Request $request)
    {
        DB::insert("insert into vpnhistory(`segment`,`branch_code`,`hosp_id`,`pid`,`birthday`,`prsn_id`,`cure_stage`,`case_date`,`iopd_type`,`fami_medic_his`,`f_medic_his`,`m_medic_his`,`c_medic_his`,`stage2_yn`,`diabetes_type`,`fall_ill_year`,`base_chk_date`,`base_tall`,`base_weight`,`base_sbp`,`base_ebp`,`blood_chk_date`,`blood_hba1c`,`blood_ldl`,`blood_ac`,`blood_pc`,`blood_tg`,`urine_chk_date`,`urine_micro`,`urine_routine`,`year_mark`,`foot_chk_left`,`foot_chk_right`,`blood_creat`,`eye_chk8`,`egfr`,`created_at`,`updated_at`) select `segment`,`branch_code`,`hosp_id`,`pid`,`birthday`,`prsn_id`,`cure_stage`,`case_date`,`iopd_type`,`fami_medic_his`,`f_medic_his`,`m_medic_his`,`c_medic_his`,`stage2_yn`,`diabetes_type`,`fall_ill_year`,`base_chk_date`,`base_tall`,`base_weight`,`base_sbp`,`base_ebp`,`blood_chk_date`,`blood_hba1c`,`blood_ldl`,`blood_ac`,`blood_pc`,`blood_tg`,`urine_chk_date`,`urine_micro`,`urine_routine`,`year_mark`,`foot_chk_left`,`foot_chk_right`,`blood_creat`,`eye_chk8`,`egfr`,NOW(),NOW() from rawdatavpn where hosp_id=?", ["$this->hospid"]);

        LogsController::saveLog('vpnhistory', 'insert(插入)', $this->ip, $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '插入VPN上傳資料成功!!'
        ]);
    }

    public function refreshCheck(Request $request)
    {
        LogsController::saveLog('cases', 'update(匯入更新)', $this->ip, $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '更新方案數據成功!!'
        ]);
    }

    /**
     * File Export Code
     *
     * @var array
     */
    public function downloadTxt(Request $request)
    {
        // 只下載該診所的資料
        $data = Rawdatavpn::select('segment', 'branch_code', 'hosp_id', 'pid', 'birthday', 'prsn_id', 'cure_stage', 'case_date', 'iopd_type', 'fami_medic_his', 'f_medic_his', 'm_medic_his', 'c_medic_his', 'stage2_yn', 'diabetes_type', 'fall_ill_year', 'base_chk_date', 'base_tall', 'base_weight', 'base_sbp', 'base_ebp', 'blood_chk_date', 'blood_hba1c', 'blood_ldl', 'blood_ac', 'blood_pc', 'blood_tg', 'urine_chk_date', 'urine_micro', 'urine_routine', 'year_mark', 'foot_chk_left', 'foot_chk_right', 'blood_creat', 'eye_chk8', 'egfr')
            ->where('hosp_id', $this->hospid)
            ->get()
            ->toArray();

        $fileName = "VPN_" . $this->hospid . "_" . date("YmdHi");
        LogsController::saveLog('rawdatavpn', 'download(下載)', $this->ip, $this->useragent);

        // 修改設定
        Config::set('excel.csv.delimiter', '');  // ','
        Config::set('excel.csv.enclosure', '');  // '"'
        return Excel::create($fileName, function($excel) use ($data) {
            $excel->sheet('VPN', function($sheet) use ($data)
            {
                // $sheet->fromArray($data);
                // Won't auto generate heading columns
                $sheet->fromArray($data, null, 'A1', false, false);
            });
        })->export('txt');

//        $text = json_encode($data);
//        $fileName = date("Ymd") . "_vpnUpload.txt";
//        File::put(public_path('/upload/text/' . $fileName), $text);
//        return Response::download(public_path('/upload/text/' . $fileName));
    }

    public function updateA1C(Request $request)
    {
        try {
            DB::update(DB::raw("UPDATE `cases` c SET c.`blood_hba1c` = ( SELECT CAST(`values` AS DECIMAL(5, 2)) FROM `checks` k WHERE k.`items` = 'A1C' AND k.`pid` = c.`pid` AND CONCAT(LEFT(k.`check_date`, 4) + 1911, '-', SUBSTRING(k.`check_date`, 6, 2), '-', RIGHT(k.`check_date`, 2)) = c.`case_date` ORDER BY `pid`, `check_date` DESC LIMIT 1 )"));
        } catch(\Exception $e) {
            $errormsg = 'Database error::' . $e->getCode();
            return $this->respondWithArray([
                'status' => 'error',
                'message' => $errormsg
            ]);
        }
        return $this->respondWithArray([
            'status' => 'success',
            'message' => '更新A1C成功!!'
        ]);
    }

    public function updateCHO(Request $request)
    {
        try {
            DB::update(DB::raw("update `cases` c set c.`cholesterol` = ( select cast(`values` as decimal(3,0)) from `checks` k where k.`items`='CHO' and k.`pid`=c.`pid` and concat(left(k.`check_date`,4)+1911,'-',substring(k.`check_date`,6,2),'-',right(k.`check_date`,2))=c.`case_date` order by `pid`,`check_date` desc limit 1 )"));
        } catch(\Exception $e) {
            $errormsg = 'Database error::' . $e->getCode();
            return $this->respondWithArray([
                'status' => 'error',
                'message' => $errormsg
            ]);
        }
        return $this->respondWithArray([
            'status' => 'success',
            'message' => '更新CHO成功!!'
        ]);
    }

    public function updateTG(Request $request)
    {
        try {
            DB::update(DB::raw("update `cases` c set c.`blood_tg` = ( select cast(`values` as decimal(7,2)) from `checks` k where k.`items`='TG' and k.`pid`=c.`pid` and concat(left(k.`check_date`,4)+1911,'-',substring(k.`check_date`,6,2),'-',right(k.`check_date`,2))=c.`case_date` order by `pid`,`check_date` desc limit 1 )"));
        } catch(\Exception $e) {
            $errormsg = 'Database error::' . $e->getCode();
            return $this->respondWithArray([
                'status' => 'error',
                'message' => $errormsg
            ]);
        }
        return $this->respondWithArray([
            'status' => 'success',
            'message' => '更新TG成功!!'
        ]);
    }

    public function updateLDL(Request $request)
    {
        try {
            DB::update(DB::raw("update `cases` c set c.`blood_ldl` = ( select cast(`values` as decimal(7,2)) from `checks` k where k.`items`='LDL' and k.`pid`=c.`pid` and concat(left(k.`check_date`,4)+1911,'-',substring(k.`check_date`,6,2),'-',right(k.`check_date`,2))=c.`case_date` order by `pid`,`check_date` desc limit 1 )"));
        } catch(\Exception $e) {
            $errormsg = 'Database error::' . $e->getCode();
            return $this->respondWithArray([
                'status' => 'error',
                'message' => $errormsg
            ]);
        }
        return $this->respondWithArray([
            'status' => 'success',
            'message' => '更新LDL成功!!'
        ]);
    }

    public function updateHDLC(Request $request)
    {
        try {
            DB::update(DB::raw("update `cases` c set c.`hdl` = ( select cast(`values` as decimal(3,0)) from `checks` k where k.`items`='HDLC' and k.`pid`=c.`pid` and concat(left(k.`check_date`,4)+1911,'-',substring(k.`check_date`,6,2),'-',right(k.`check_date`,2))=c.`case_date` order by `pid`,`check_date` desc limit 1 )"));
        } catch(\Exception $e) {
            $errormsg = 'Database error::' . $e->getCode();
            return $this->respondWithArray([
                'status' => 'error',
                'message' => $errormsg
            ]);
        }
        return $this->respondWithArray([
            'status' => 'success',
            'message' => '更新HDLC成功!!'
        ]);
    }

    public function updateGPT(Request $request)
    {
        try {
            DB::update(DB::raw("update `cases` c set c.`gpt` = ( select cast(`values` as decimal(4,0)) from `checks` k where k.`items`='GPT' and k.`pid`=c.`pid` and concat(left(k.`check_date`,4)+1911,'-',substring(k.`check_date`,6,2),'-',right(k.`check_date`,2))=c.`case_date` order by `pid`,`check_date` desc limit 1 )"));
        } catch(\Exception $e) {
            $errormsg = 'Database error::' . $e->getCode();
            return $this->respondWithArray([
                'status' => 'error',
                'message' => $errormsg
            ]);
        }
        return $this->respondWithArray([
            'status' => 'success',
            'message' => '更新GPT成功!!'
        ]);
    }

    public function updateCRE(Request $request)
    {
        try {
            DB::update(DB::raw("update `cases` c set c.`blood_creat` = ( select cast(`values` as decimal(7,2)) from `checks` k where k.`items`='CRE' and k.`pid`=c.`pid` and concat(left(k.`check_date`,4)+1911,'-',substring(k.`check_date`,6,2),'-',right(k.`check_date`,2))=c.`case_date` order by `pid`,`check_date` desc limit 1 )"));
        } catch(\Exception $e) {
            $errormsg = 'Database error::' . $e->getCode();
            return $this->respondWithArray([
                'status' => 'error',
                'message' => $errormsg
            ]);
        }
        return $this->respondWithArray([
            'status' => 'success',
            'message' => '更新CRE成功!!'
        ]);
    }

    public function updateUA(Request $request)
    {
        try {
            DB::update(DB::raw("update `cases` c set c.`uricacid` = ( select cast(`values` as decimal(4,1)) from `checks` k where k.`items`='UA' and k.`pid`=c.`pid` and concat(left(k.`check_date`,4)+1911,'-',substring(k.`check_date`,6,2),'-',right(k.`check_date`,2))=c.`case_date` order by `pid`,`check_date` desc limit 1 )"));
        } catch(\Exception $e) {
            $errormsg = 'Database error::' . $e->getCode();
            return $this->respondWithArray([
                'status' => 'error',
                'message' => $errormsg
            ]);
        }
        return $this->respondWithArray([
            'status' => 'success',
            'message' => '更新UA成功!!'
        ]);
    }

    public function updateMIC(Request $request)
    {
        try {
            DB::update(DB::raw("update `cases` c set c.`urine_micro` = ( select cast(`values` as decimal(7,2)) from `checks` k where k.`items`='MIC' and k.`pid`=c.`pid` and concat(left(k.`check_date`,4)+1911,'-',substring(k.`check_date`,6,2),'-',right(k.`check_date`,2))=c.`case_date` order by `pid`,`check_date` desc limit 1 )"));
        } catch(\Exception $e) {
            $errormsg = 'Database error::' . $e->getCode();
            return $this->respondWithArray([
                'status' => 'error',
                'message' => $errormsg
            ]);
        }
        return $this->respondWithArray([
            'status' => 'success',
            'message' => '更新MIC成功!!'
        ]);
    }

    public function updatePCR(Request $request)
    {
        try {
            DB::update(DB::raw("update `cases` c set c.`upcr` = ( select cast(`values` as decimal(6,1)) from `checks` k where k.`items`='PCR' and k.`pid`=c.`pid` and concat(left(k.`check_date`,4)+1911,'-',substring(k.`check_date`,6,2),'-',right(k.`check_date`,2))=c.`case_date` order by `pid`,`check_date` desc limit 1 )"));
        } catch(\Exception $e) {
            $errormsg = 'Database error::' . $e->getCode();
            return $this->respondWithArray([
                'status' => 'error',
                'message' => $errormsg
            ]);
        }
        return $this->respondWithArray([
            'status' => 'success',
            'message' => '更新PCR成功!!'
        ]);
    }

    public function updateUPRO(Request $request)
    {
        try {
            DB::update(DB::raw("update `cases` c set c.`urine_routine` = ( select case when `values` is null then null when substring(`values`,1,locate(')',`values`))='(-)' then '正常' when substring(`values`,1,locate(')',`values`))='(+)' then '+' when substring(`values`,1,locate(')',`values`))='(++)' then '2+' when substring(`values`,1,locate(')',`values`))='(+++)' then '3+' when substring(`values`,1,locate(')',`values`))='(++++)' then '4+' else '正常' end from `checks` k where k.`items`='UPRO' and k.`pid`=c.`pid` and concat(left(k.`check_date`,4)+1911,'-',substring(k.`check_date`,6,2),'-',right(k.`check_date`,2))=c.`case_date` order by `pid`,`check_date` desc limit 1 )"));
        } catch(\Exception $e) {
            $errormsg = 'Database error::' . $e->getCode();
            return $this->respondWithArray([
                'status' => 'error',
                'message' => $errormsg
            ]);
        }
        return $this->respondWithArray([
            'status' => 'success',
            'message' => '更新UPRO成功!!'
        ]);
    }

    public function updateGFR(Request $request)
    {
        try {
            DB::update(DB::raw("update `cases` c set c.`egfr` = ( select cast(`values` as decimal(4,1)) from `checks` k where k.`items`='GFR' and k.`pid`=c.`pid` and concat(left(k.`check_date`,4)+1911,'-',substring(k.`check_date`,6,2),'-',right(k.`check_date`,2))=c.`case_date` order by `pid`,`check_date` desc limit 1 )"));
        } catch(\Exception $e) {
            $errormsg = 'Database error::' . $e->getCode();
            return $this->respondWithArray([
                'status' => 'error',
                'message' => $errormsg
            ]);
        }
        return $this->respondWithArray([
            'status' => 'success',
            'message' => '更新GFR成功!!'
        ]);
    }

    public function checkCorrect(Request $request)
    {
        try {
            // 1408
            DB::insert("INSERT INTO `rawdatavpn` (`segment`,`branch_code`,`hosp_id`,`id`,`birthday`,`prsn_id`,`cure_stage`,`case_date`,`iopd_type`,`fami_medic_his`,`f_medic_his`,`m_medic_his`,`c_medic_his`,`stage2_yn`,`diabetes_type`,`fall_ill_year`,`base_chk_date`,`base_tall`,`base_weight`,`base_sbp`,`base_ebp`,`blood_chk_date`,`blood_hba1c`,`blood_ldl`,`blood_ac`,`blood_pc`,`blood_tg`,`urine_chk_date`,`urine_micro`,`urine_routine`,`year_mark`,`foot_chk_left`,`foot_chk_right`,`blood_creat`,`eye_chk8`,`egfr`) SELECT 'D',(SELECT branch_code FROM doctors WHERE hosp_id=?),?,r.`pid`,CONCAT(SUBSTRING(r.`birthday`,1,3)+1911,SUBSTRING(r.`birthday`,5,2),SUBSTRING(r.`birthday`,8,2)),c.`prsn_id`,CASE c.`cure_stage` WHEN 'DM初診' THEN RPAD('0',4,' ') ELSE RPAD('1',4,' ') END,CONCAT(SUBSTRING(r.`check_date`,1,3)+1911,SUBSTRING(r.`check_date`,5,2),SUBSTRING(r.`check_date`,8,2)),'1',' ',' ',' ',' ','N',' ',REPEAT(' ',4),REPEAT(' ',8),CASE WHEN c.`base_tall` IS NULL THEN REPEAT(' ',6) ELSE LPAD(ROUND(c.`base_tall`,1),6,'0') END,CASE WHEN c.`base_weight` IS NULL THEN REPEAT(' ',6) ELSE LPAD(ROUND(c.`base_weight`,2),6,'0') END,CASE WHEN c.`base_sbp` IS NULL THEN REPEAT(' ',3) ELSE LPAD(c.`base_sbp`,3,'0') END,CASE WHEN c.`base_ebp` IS NULL THEN REPEAT(' ',3) ELSE LPAD(c.`base_ebp`,3,'0') END,CONCAT(SUBSTRING(r.`check_date`,1,3)+1911,SUBSTRING(r.`check_date`,5,2),SUBSTRING(r.`check_date`,8,2)),LPAD(ROUND(IFNULL(c.`blood_hba1c`,0),2),6,'0'),CASE WHEN c.`blood_ldl` IS NULL THEN REPEAT(' ',8) ELSE LPAD(ROUND(c.`blood_ldl`,2),8,'0') END,CASE c.`blood_ap` WHEN '前' THEN LPAD(ROUND(IFNULL(c.`blood_acpc`,0),2),8,'0') ELSE REPEAT(' ',8) END,CASE c.`blood_ap` WHEN '後' THEN LPAD(ROUND(IFNULL(c.`blood_acpc`,0),2),8,'0') ELSE REPEAT(' ',8) END,CASE WHEN c.`blood_tg` IS NULL THEN REPEAT(' ',8) ELSE LPAD(ROUND(c.`blood_tg`,2),8,'0') END,CONCAT(SUBSTRING(r.`check_date`,1,3)+1911,SUBSTRING(r.`check_date`,5,2),SUBSTRING(r.`check_date`,8,2)),CASE WHEN c.`urine_micro` IS NULL THEN REPEAT(' ',8) ELSE LPAD(ROUND(c.`urine_micro`,2),8,'0') END,CASE WHEN c.`urine_routine` IS NULL THEN ' ' WHEN '正常' THEN '1' ELSE '2' END,CASE c.`cure_stage` WHEN 'DM年度' THEN 'Y' WHEN 'CKD複診+DM年度' THEN 'Y' ELSE ' ' END,' ',' ',CASE WHEN c.`blood_creat` IS NULL THEN REPEAT(' ',8) ELSE LPAD(ROUND(c.`blood_creat`,2),8,'0') END,CASE c.`eye_chk8` WHEN '無' THEN '0' WHEN '正常' THEN '1' ELSE '2' END,CASE WHEN c.`egfr` IS NULL THEN REPEAT(' ',5) ELSE LPAD(ROUND(c.`egfr`,1),5,'0') END FROM `realsun` r LEFT JOIN `cases` c ON r.`pid`=c.`pid` WHERE CONCAT(SUBSTRING(r.`check_date`,1,3)+1911,SUBSTRING(r.`check_date`,5,2), SUBSTRING(r.`check_date`,8,2))=c.`case_date` AND r.`drugcode`='P1408C' AND r.`hosp_id`=? ORDER BY r.`drugcode`,r.`check_date`,r.`pid`", ["$this->hospid", "$this->hospid", "$this->hospid"]);
            // 1409
            DB::insert("INSERT INTO `rawdatavpn` (`segment`,`branch_code`,`hosp_id`,`id`,`birthday`,`prsn_id`,`cure_stage`,`case_date`,`iopd_type`,`fami_medic_his`,`f_medic_his`,`m_medic_his`,`c_medic_his`,`stage2_yn`,`diabetes_type`,`fall_ill_year`,`base_chk_date`,`base_tall`,`base_weight`,`base_sbp`,`base_ebp`,`blood_chk_date`,`blood_hba1c`,`blood_ldl`,`blood_ac`,`blood_pc`,`blood_tg`,`urine_chk_date`,`urine_micro`,`urine_routine`,`year_mark`,`foot_chk_left`,`foot_chk_right`,`blood_creat`,`eye_chk8`,`egfr`) SELECT 'D',(SELECT branch_code FROM doctors WHERE hosp_id=?),?,r.`pid`,CONCAT(SUBSTRING(r.`birthday`,1,3)+1911,SUBSTRING(r.`birthday`,5,2),SUBSTRING(r.`birthday`,8,2)),c.`prsn_id`,CASE c.`cure_stage` WHEN 'DM初診' THEN RPAD('0',4,' ') ELSE RPAD('1',4,' ') END,CONCAT(SUBSTRING(r.`check_date`,1,3)+1911,SUBSTRING(r.`check_date`,5,2),SUBSTRING(r.`check_date`,8,2)),'1',' ',' ',' ',' ','N',' ',REPEAT(' ',4),CONCAT(SUBSTRING(r.`check_date`,1,3)+1911,SUBSTRING(r.`check_date`,5,2),SUBSTRING(r.`check_date`,8,2)),CASE WHEN c.`base_tall` IS NULL THEN REPEAT(' ',6) ELSE LPAD(ROUND(c.`base_tall`,1),6,'0') END,CASE WHEN c.`base_weight` IS NULL THEN REPEAT(' ',6) ELSE LPAD(ROUND(c.`base_weight`,2),6,'0') END,CASE WHEN c.`base_sbp` IS NULL THEN REPEAT(' ',3) ELSE LPAD(c.`base_sbp`,3,'0') END,CASE WHEN c.`base_ebp` IS NULL THEN REPEAT(' ',3) ELSE LPAD(c.`base_ebp`,3,'0') END,CONCAT(SUBSTRING(r.`check_date`,1,3)+1911,SUBSTRING(r.`check_date`,5,2),SUBSTRING(r.`check_date`,8,2)),LPAD(ROUND(IFNULL(c.`blood_hba1c`,0),2),6,'0'),CASE WHEN c.`blood_ldl` IS NULL THEN REPEAT(' ',8) ELSE LPAD(ROUND(c.`blood_ldl`,2),8,'0') END,CASE c.`blood_ap` WHEN '前' THEN LPAD(ROUND(IFNULL(c.`blood_acpc`,0),2),8,'0') ELSE REPEAT(' ',8) END,CASE c.`blood_ap` WHEN '後' THEN LPAD(ROUND(IFNULL(c.`blood_acpc`,0),2),8,'0') ELSE REPEAT(' ',8) END,CASE WHEN c.`blood_tg` IS NULL THEN REPEAT(' ',8) ELSE LPAD(ROUND(c.`blood_tg`,2),8,'0') END,CONCAT(SUBSTRING(r.`check_date`,1,3)+1911,SUBSTRING(r.`check_date`,5,2),SUBSTRING(r.`check_date`,8,2)),CASE WHEN c.`urine_micro` IS NULL THEN REPEAT(' ',8) ELSE LPAD(ROUND(c.`urine_micro`,2),8,'0') END,CASE WHEN c.`urine_routine` IS NULL THEN ' ' WHEN '正常' THEN '1' ELSE '2' END,CASE c.`cure_stage` WHEN 'DM年度' THEN 'Y' WHEN 'CKD複診+DM年度' THEN 'Y' ELSE ' ' END,CASE WHEN LOCATE('正常',c.`foot_chk_left`)>0 THEN '0' ELSE '1' END,CASE WHEN LOCATE('正常',c.`foot_chk_right`)>0 THEN '0' ELSE '1' END,CASE WHEN c.`blood_creat` IS NULL THEN REPEAT(' ',8) ELSE LPAD(ROUND(c.`blood_creat`,2),8,'0') END,CASE WHEN LOCATE('無',c.`eye_chk8`)>0 THEN '0' WHEN LOCATE('正常',c.`eye_chk8`)>0 THEN '1' ELSE '2' END,CASE WHEN c.`egfr` IS NULL THEN REPEAT(' ',5) ELSE LPAD(ROUND(c.`egfr`,1),5,'0') END FROM `realsun` r LEFT JOIN `cases` c ON r.`pid`=c.`pid` WHERE CONCAT(SUBSTRING(r.`check_date`,1,3)+1911,SUBSTRING(r.`check_date`,5,2), SUBSTRING(r.`check_date`,8,2))=c.`case_date` AND r.`drugcode`='P1409C' AND r.`hosp_id`=? ORDER BY r.`drugcode`,r.`check_date`,r.`pid`", ["$this->hospid", "$this->hospid", "$this->hospid"]);
        } catch(\Exception $e) {
            $errormsg = 'Database error::' . $e->getCode();
            return $this->respondWithArray([
                'status' => 'error',
                'message' => $errormsg
            ]);
        }
        return $this->respondWithArray([
            'status' => 'success',
            'message' => '新增VPN上傳數據成功!!'
        ]);
    }

}
