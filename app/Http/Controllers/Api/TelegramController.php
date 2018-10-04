<?php

namespace App\Http\Controllers\Api;

use Auth;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Laravel\Facades\Telegram;


class TelegramController extends ApiController
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
        // return $this->respondWith(Checks::all(), new CasesTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @return \Response
     */
    public function getHome()
    {
        // return view('/home');
    }

    public function getUpdates()
    {
        $updates = Telegram::getUpdates();
        dd($updates);
    }

    public function getSendMessage()
    {
        return view('send-message');
    }

    public function postSendMessage(Request $request)
    {
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => '測試中!!'
//        ]);

        $rules = [
            'message' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
        {
//            return redirect()->back()
//                ->with('status', 'danger')
//                ->with('message', 'Message is required');
            return $this->respondWithArray([
                'status' => 'error',
                'message' => 'Message is required...訊息內容不可空白!!'
            ]);
        }

        $chat = $request->input('chatid');
//        return $this->respondWithArray([
//            'status' => 'success',
//            'message' => $chat['userid']
//        ]);

        Telegram::sendMessage([
//            'chat_id' => env('GROUP_ID'),
            'chat_id' => $chat['userid'],
            'text' => $request->get('message')
        ]);

//        return redirect()->back()
//            ->with('status', 'success')
//            ->with('message', 'Message sent');
        return $this->respondWithArray([
            'status' => 'success',
            'message' => 'Message sent...訊息已發送!!'
        ]);
    }

    /**
     * 傳送群組訊息.
     *
     * @param message
     */
    public static function sendMessage($message)
    {
        Telegram::sendMessage([
            'chat_id' => env('GROUP_ID'),
            'text' => $message
        ]);
    }

}
