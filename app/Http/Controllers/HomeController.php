<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Yiban\YBOpenApi;
use App\Yiban\YBException;
use App\User,App\Nav,App\Activity;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',[
            'except' => ['yibanAuth']
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function counsellor()
    {
        return view('counsellor');
    }
    public function mosco($now = 1)
    {
        $mytitle = "";
        $me=Auth::user();
        foreach($me->titles() as $title) $mytitle .= ($title->name).' ';
        return view('mosco.index',
            ['now'  => $now,
             'me'   => $me,
             'users'=> User::where('daban',$me->daban)->get(),
             'nav'  => Nav::all(),
             'mytitle' => trim($mytitle),
             'oldval'=> 0,
             'acts' =>Activity::all()
            ]);
    }
    public function yibanAuth(Request $request)
    {
        if (empty($request->input('verify_request'))) return redirect('login');

        $yibanApi = YBOpenApi::getInstance()->init();
        $yibanIapp  = $yibanApi->getIApp();

        try
        {
            //轻应用获取access_token，未授权则跳转至授权页面
            $info = $yibanIapp->perform();
            if($info == false) return response('Redirecting to authorize page...', 302);
            $yibanApi->bind($info['visit_oauth']['access_token']);
            $yibanRealnameInfo = $yibanApi->request('user/real_me');
            //$yibanRealnameInfo = $yibanApi->request('oauth/revoke_token', array('client_id'=>$yibanApi->getConfig('appid')), true);
            //dd($yibanRealnameInfo);
        }
        catch (YBException $ex)
        {
            return redirect('login')->withInput($request->except('password'))->with('message', '授权失败！请重试！');
        }

        if ($yibanRealnameInfo['status'] != 'success')
            return redirect('login')->withInput($request->except('password'))->with('message', $yibanRealnameInfo['info']['msgCN']);
        if ($user=User::find($yibanRealnameInfo['info']['yb_studentid']) && Auth::loginUsingID($user->id))
            return redirect('/');
        else
            return redirect('login')->withInput($request->except('password'))->with('message', '鉴权信息不存在！');
    }
}
