<?php

namespace App\Http\Controllers;
use Excel;
use App\Activity;
use App\Nav;
use App\Score;
use App\User;
use App\Result;
use DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Auth;

class MosController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * 渲染我的打分页
     *
     */
    public function judge($id=0){
        return view('mosco.judge', [
            'me'    =>Auth::user(),
            'navs'  =>Nav::all(),
            'scores'=>Score::all(),
            'sb'    =>$id?User::find($id):Auth::user(),
            'id'    =>$id?$id:Auth::user()->id,
            'mytitle'=>Auth::user()->title(),
            'tot'   =>0
        ]);
    }
    /**
     * 渲染公示查询页
     *
     */
    public function search($id = 0)
    {
        return view('mosco.search', [
            'id'    =>$id,
            'me'    =>Auth::user(),
            'users' => User::all(),
            'scores'=>Score::all(),
            'acts'  =>Activity::all(),
        ]);
    }
    /**
     * 渲染我的成绩、学生成绩页面
     *
     */
    public function show()
    {
	    return view('mosco.show', [
            'me'    =>Auth::user(),
            'users' =>User::all(),
            'navs'  =>Nav::all(),
            'scores'=>Score::all()
        ]);
    }
    /**
     * 评分记录 me给can的nav项目打的分数sco
     */
    public function recd($can,$sco,$nav)
    {
        $wo=Auth::user();
        $ta=User::find($can);
        $i=Nav::find($nav);

        $score=Score::where('candiate',$can)
            ->where('user_id',$wo->id)
            ->where('nav',$nav)
            ->first();


        $tnav=$nav;
        if(strstr($nav, '_'))//如果是文娱体育的，那就是第6项完成了
            $tnav=6;
        

        //我的第nav项结果
        $mres=Result::firstOrCreate([
            'user_id'=>$wo->id,
            'nav_id'=>$tnav
            ]);

        //ta的第nav项结果
        $tres=Result::firstOrCreate([
            'user_id'=>$can,
            'nav_id'=>$tnav
        ]);
        
        if(!$mres->finish){
            $tres->update(['cnt'=>DB::raw('cnt+1')]);//加一个打分人数
        }

        if($can==$wo->id)//如果是自评，可能一次提交多个。
            $tres->update(['cnt'=>1]);//打分人数设为1人

        if($i->type=='导评项'||$i->ename=='xueshenghui')
            $tres->update(['cnt'=>1]);//打分人数设为1人
        
        $def=$wo->defsco($ta,$i);

        //竞赛
        if($i->ename=='competition'){
            //记录到award里
            $mres->update(['award'=>$sco]);
        }
        //否则如果是默认分
        else if($sco==$def){
            //如果以前有记录（不可能是默认分）
            if($score)
                //删掉
                $score->delete();

        //否则，判断一下合法性，分数为正，如果默认分不为零，分数不能大于等于默认分
        }else if($sco>=-0.01&&!($def&&$sco>=$def)){

            //有记录就修改
            if($score)
                $score->update(['score'=>$sco]);

            //没有就新建一条
            else
                $score = Score::create(['candiate'=>$can,'user_id'=>$wo->id,'score'=>$sco,'nav'=>$nav]);
        }    
    }
    /**
     * 提交评分
     *
     */
    public function update(Request $request){
       // if(Auth::User()->title()!='辅导员')
         //   return response()->json(['success' => false,'info'=>"评分已结束"]);
        
        $input=$request->all();
//'_token'=>'xxx','_1_2015211457_12'=>10,'_1_xx_xx'=>10,'irst'=>'OK!',
        $arr=array_keys($input);
//0=>'token',1=>'_1_2015211457_12'，2=>'_1_xx_xx',3=>'irst'

        if(!Auth::check())
            return response(['success' => false,'info'=>"非法操作!"]);
        $nav=null;$op=null;
        for($i=0;$i<count($arr);$i++)if($arr[$i][0]=='_'&&$arr[$i][1]!='t'){
            $op=explode('_',$arr[$i]);
            //[0]:'',[1]:nav,[2]:userid,[3]:sport_nav
            $nav=$op[1];
            if(isset($op[3])){
                $op[1]=$op[1]."_".$op[3];
            }
            $this->recd($op[2],$input[$arr[$i]],$op[1]);
        }

        if(!isset($nav))
            return response()->json(['success' => true,'info'=>"无效提交"]);

        Result::firstOrCreate([
            'user_id' => Auth::user()->id,
            'nav_id'  => $nav
            ])->update(['finish'=>1]);//标记用户完成该项打分
        return response()->json(['success' => true,'info'=>"成功提交"]);
    }

    private function output_score($u,$nav){
        $j=0;
        $array=array();
        $array[0]=$u->id;//学号
        $array[1]=$u->name;//姓名
        foreach ($nav as  $j => $n) {
            if($n->ename=="competition"){
                if($res=$u->hasMany('App\Result')->where('nav_id',$n->id)->first()){
                      $array[$j+2]=$res->award;
                }else{
                    $array[$j+2]="";//为空必须写上，否则后面会偏移
                }
            }else{
                 $array[$j+2]=round($u->result($n),2);
            }
        }
        $array[$j+3]=round($u->mosco(),2);
        return $array;
    }

    public function output(){
        $me = Auth::User();
        $array;
        $users = User::all();
        $nav=Nav::all();

        $array[0][0]='学号';
        $array[0][1]='姓名';
        $i=2;
        foreach ($nav as $n)
            $array[0][$i++]=$n->name;//第i个项目
        $array[0][$i]='总分';
        $i=0;
        $filename=$me->name;
        if($me->title()=="辅导员"){
            foreach ($users as $u) if($u->title()!="辅导员"&&$u->name!='终极管理员'){
                $array[++$i]=$this->output_score($u,$nav);
            }
            $filename=$me->daban;
        }
        else{//非导员
            $array[1]=$this->output_score($me,$nav);
	    }
        Excel::create($filename.'德育分成绩表.'.date("Y-m-d-his"),function($excel) use ($array){
                $excel->sheet('score', function($sheet) use ($array){
                $sheet->rows($array);
            });
        })->export('xls');
        die();
    }
    public function output_act(){
        $me = Auth::User();
        if($me->title()=="辅导员"){
            $array=array();
            $users = User::where('daban',$me->daban)->get();
            $acts = Activity::All();
            $array[0][0]='学号';
            $array[0][1]='姓名';
            foreach ($acts as $i => $act){
                $array[0][$i+2]=$act->name;
            }
            array_push($array[0],"总分");
            $n=Nav::find(8);//文娱体育
            foreach ($users as $i => $u) if($u->title()!="辅导员"){
                $array[$i+1][0]=$u->id;//学号
                $array[$i+1][1]=$u->name;//姓名
                foreach($acts as $j => $act){
                    $array[$i+1][$j+2]=$u->oldval($u,$n,'6_'.$act->id);
                }
                array_push($array[$i+1],round($u->result($n),2));
            }
            Excel::create($me->daban.'文娱体育评分.'.date("Y-m-d-his"),function($excel) use ($array){
                $excel->sheet('score', function($sheet) use ($array){
                $sheet->rows($array);
            });
        })->export('xls');
        }
        die();
    }

}
