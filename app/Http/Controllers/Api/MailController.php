<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Mail\Message;
use Mail;

class MailController extends ApiController
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
     * 由 Queue 寄信.
     *
     * @param message
     */
    public function mailByLater()
    {
        // return view('/home');
    }

    public function mailByQueue(Request $request)
    {
//        return $this->respondWithArray([
//            'status' => 'error',
//            'message' => '測試中!!',
//            'subject' => $request->subject,
//            'cc' => empty($request->cc)
//        ]);

        //寄信者
        $from = [
            'email' => 'admin@dmclinicyu.com',
            'name' => 'DMClinicYU游能俊診所',
            'subject' => $request->subject
        ];

        //收信人
        $to = [
            'email' => $request->recipient,
            'name' => $request->rname
        ];

        //信件的內容=>對應post.blade.php
        $data = [
            'subject' => $request->subject,
            'msg' => $request->ebody
        ];

        $cc = [
            'email' => $request->cc
        ];

        //同步寄出信件
        try {
            Mail::send('emails.post', $data, function($message) use ($from, $to, $cc) {
                $message->from($from['email'], $from['name']);
                $message->to($to['email'], $to['name']);
                if (!empty($cc['email'])) {
                    $message->cc($cc['email']);
                }
                $message->subject($from['subject']);
            });
        } catch (Exception $e) {
            // check for failures
            if (count(Mail::failures()) > 0) {
                //$failures[] = $to['email'];
                // return response showing failed emails
                //dd('Failure!!');
                return $this->respondWithArray([
                    'success' => false,
                    'message' => '寄信失敗!!'
                ]);
            }
        }
        //若要寄送副本
        //$message->to($to['email'], $to['name'])->cc('abc@mail.com');
        //若要夾帶檔案
        //$message->attach('檔案路徑');

        //非同步
//        Mail::queue('emails.post', $data, function($message) use ($from, $to) {
//            $message->from($from['email'], $from['name']);
//            $message->to($to['email'], $to['name'])->subject($from['subject']);
//        });

        //延遲幾秒後寄送
//        Mail::later(10, 'emails.post', $data, function($message) use ($from, $to) {
//            $message->from($from['email'], $from['name']);
//            $message->to($to['email'], $to['name'])->subject($from['subject']);
//        });

        // return redirect('/dashboard');
        // otherwise everything is okay ...
        // dd('Done.');
//        return redirect()->back()->with('success', '寄信成功!');
        // return redirect('dashboard')->with('success', '寄信成功!');
        return $this->respondWithArray([
            'success' => true,
            'message' => '寄信成功!!'
        ]);
    }

}
