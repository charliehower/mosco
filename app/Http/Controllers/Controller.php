<?php

namespace App\Http\Controllers;
use App\Feedback;
use App\Askoff;
use App\User;
use App\Title;
use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //后台操作
    //若不存在id则为添加操作：
    //否则若操作类型type为del，则为删除用户操作：	
    //否则为修改信息：
    //密码为空则不修改密码
    public function counsellorPost(Request $request){

    	if(!Auth::check() || Auth::user()->title()!='辅导员'&&Auth::user()->name!='终极管理员')
    		return response(['success' => false,'info'=>"非法操作!"]);
        $input=$request->all();

        if(!isset($input['id']))
    		return response(['success' => false,'info'=>"非法进入!"]);

		if(!isset($input['password']) || !isset($input['name'])  
			|| !isset($input['class'] ))
			response(['success' => false,'info'=>"信息不合法!"]);

        $xuehao=$input['id'];

        $u=User::find($xuehao);
        if(isset($input['type']) && $input['type'] == 'add'){ //插入操作
	        if(!$u){
	        	if(($xuehao!='') && ($xuehao[0] != '0') && (strlen($xuehao) < 11)){
		        	$id=DB::table('users')->insert([
					    'id' => $xuehao,
					    'name' => $input['name'],
					    'class' => $input['class'],
					    'password' => app('hash')->make($input['password']),
					]);

		        	if(isset($input['newtitle'])){
		        		$titleArry=explode(' ',$input['newtitle']);
			        	foreach($titleArry as $title)
			        	if($title!='')
			        		Title::where('name',$title)->first()->users()->attach($id,['time' => $input['worktime']]);
			        }

					return response(['success' => true]);

	        	}
	        	return response(['success' => false,'info'=>"信息不合法!"]);
	        }else{
	    		return response(['success' => false,'info'=>"学号已存在!"]);
	    	}
	    }

        //删除
        if(isset($input['type']) && $input['type']=='del' ){
        	$u->delete();
        	return response(['success' => true]);
		}
		
        //输入密码不为空才要改密码
        if($input['password'] != "")
        	$u->password=app('hash')->make($input['password']);

       	$u->name=$input['name'];

       	$u->class=$input['class'];

        $u->save();

		if(isset($input['newtitle'])){
	        $titleArry=explode(' ',$input['newtitle']);
	        //该职位要删除
	        foreach($u->titles() as $tit){
	        	$delete=true;
	        	foreach($titleArry as $title)
        			if($tit->name==$title){
						$delete=false;
						break;
					}
				if($delete==true)
	        		Title::where('name',$tit->name)->first()->users()->detach($u->id);
	        }

	        //如果该职位之前没有就添加
	        foreach($titleArry as $title)
	        if($title!=''){
	        	$alreadyHasTitle=false;
        		foreach($u->titles() as $tit)
        			if($tit->name==$title){
						$alreadyHasTitle=true;
						break;
					}
				if($alreadyHasTitle==false)
	        		Title::where('name',$title)->first()->users()->attach($u->id,['time' => $input['worktime']]);
	        }
	    }
        return response(['success' => true]);
    }
}
