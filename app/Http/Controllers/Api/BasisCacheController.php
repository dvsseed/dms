<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\BasisCache;
use Illuminate\Http\Request;
use App\Transformers\BasisCacheTransformer;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BasisCacheController extends ApiController
{
    protected $uid;

    /**
     * BasisCacheController constructor.
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->middleware('timeout');

//        $this->uid = $request->user()->id;
        $this->uid = Auth::user()->id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        return $this->respondWith(BasisCache::all(), new BasisCacheTransformer);
        $basiscache = BasisCache::where('user_id', $this->uid)->first();

        return $this->respondWith($basiscache, new BasisCacheTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Response
     */
    public function show($id)
    {
        $basiscache = BasisCache::where('user_id', $this->uid)->first();

        return $this->respondWith($basiscache, new BasisCacheTransformer);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        $basiscache = BasisCache::where('id', $id)->first();
//
//        return $this->respondWith($basiscache, new BasisCacheTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 暫存使用者輸入資料
        $basiscache = new BasisCache;

        return $this->respondWith($basiscache, new BasisCacheTransformer);
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

        $basiscache = BasisCache::findOrFail($id);

        $basiscache->save();

        return $this->respondWith($basiscache, new BasisCacheTransformer);
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
//        $basiscache = BasisCache::findOrFail($id);
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => '123'
//        ]);
        $basiscache = BasisCache::where('user_id', $this->uid)->first();
        if ($basiscache) {
            $basiscache->delete();
            return $this->respondWithArray([
                'success' => true,
                'message' => '刪除使用者的基本資料(T2DM)成功!!'
            ]);
        } else {
            return $this->respondWithArray([
                'error' => true,
                'message' => '刪除使用者的基本資料(T2DM)失敗!!'
            ]);
        }
    }

}
