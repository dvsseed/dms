<?php

namespace App\Http\Controllers\Api;

use Auth;
use DB;
use App\Doctor;
use App\Transformers\DoctorTransformer;
use App\Transformers\Doctor1Transformer;
use Illuminate\Http\Request;

use App\Http\Requests;

class DoctorsController extends ApiController
{
    protected $user;
    protected $ip;
    protected $uid;
    protected $useragent;
    protected $hospid;

    /**
     * DoctorsController constructor.
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->middleware('timeout');

        $this->user = Auth::user();
        $this->ip = $request->ip();
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
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => 'test'
//        ]);

        if ($request->user()->name == 'admin' || $request->user()->role_level == 9) {
            return $this->respondWith(Doctor::whereNotNull('hosp_id')
                ->orderBy('created_at', 'DESC')
                ->paginate(10)
                ->appends(['include' => $request->input('include')]), new DoctorTransformer);
        } else {
            //依醫事機構代碼
            return $this->respondWith(Doctor::where('hosp_id', $request->user()->hosp_id)
                ->orderBy('created_at', 'DESC')
                ->paginate(10)
                ->appends(['include' => $request->input('include')]), new DoctorTransformer);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $doctor = new Doctor;
        $doctor->branch_code = '1';
        $doctor->hosp_id = $this->hospid;
        $doctor->name = '請輸入醫師姓名';
        $doctor->save();

        LogsController::saveLog('doctors', 'store(保存)', $request->ip(), $this->useragent);
        return $this->respondWith($doctor, new DoctorTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        $doctor = Doctor::findOrFail($id);
        $doctor = Doctor::where('id', $id)->first();

        return $this->respondWith($doctor, new DoctorTransformer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        $doctor = Doctor::findOrFail($id);
        $doctor = Doctor::where('id', $id)->first();

        return $this->respondWith($doctor, new DoctorTransformer);
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
//            'message' => $request->branch_code[0]
//        ]);

        $this->validate($request, [
            'name' => 'required',
            'pid' => 'required',
        ]);

        $doctor = Doctor::findOrFail($id);
        //$doctor->fill($request->all());
        $doctor->branch_code = $request->branch_code[0];
        $doctor->hosp_id = $request->hosp_id;
        $doctor->name = $request->name;
        $doctor->pid = $request->pid;
        $doctor->save();

        LogsController::saveLog('doctors', 'update(更新)', $request->ip(), $this->useragent);
        return $this->respondWith($doctor, new DoctorTransformer);
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
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        LogsController::saveLog('doctors', 'destroy(删除)', $request->ip(), $this->useragent);
        return $this->respondWithArray([
            'success' => true,
            'message' => '刪除醫師成功!!'
        ]);
    }

    /**
     * Get the records of doctors.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function getDoctorsData()
    {
        return Doctor::select('id', 'hosp_id', 'name', 'pid')->orderBy('created_at', 'DESC')->get()->toArray();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function allDoctors(Request $request)
    {
        // return $this->respondWith(Doctor::where('hosp_id', $request->user()->hosp_id)->get(), new DoctorTransformer);
        return $this->respondWith(Doctor::select('name', 'pid')->where('hosp_id', $request->user()->hosp_id)->orderBy('pid', 'ASC')->get(), new Doctor1Transformer);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $page
     * @return \Illuminate\Http\Response
     */
    public function showPage(Request $request, $page = 10)
    {
        if ($request->user()->name == 'admin' || $request->user()->role_level == 9) {
            return $this->respondWith(Doctor::whereNotNull('hosp_id')
                ->orderBy('created_at', 'DESC')
                ->paginate($page)
                ->appends(['include' => $request->input('include')]), new DoctorTransformer);
        } else {
            //依醫事機構代碼
            return $this->respondWith(Doctor::where('hosp_id', $request->user()->hosp_id)
                ->orderBy('created_at', 'DESC')
                ->paginate($page)
                ->appends(['include' => $request->input('include')]), new DoctorTransformer);
        }
    }

}
