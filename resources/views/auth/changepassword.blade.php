@extends('layouts.main',['now'=>0])
@section('title', '登录')
@section('css')
	<style>
	.login{
		width:320px;
		margin:auto!important;
	}
    .fntlarge {
        font-size: 60px;
        margin:50px 0 30px!important;
        letter-spacing: 3px;
        text-align: center;
    }
    </style>
@endsection
@section('content')
	<div class="ui container">
		<h1 class="mosco moscolarge">Mosco</h1>
	    <form class="ui form segment login" method="post" action="/resetpassword">
			 {!! csrf_field() !!}
	        <div class="field">
		        <div class="ui left icon input">
	            <i class="lock icon"></i>
	            <input type="password" name="password" placeholder="新密码" value="">
	          	</div>
	        </div>
			<div class="field">
		        <div class="ui left icon input">
	            <i class="lock icon"></i>
	            <input type="password" name="repassword" placeholder="请再次输入" value="">
	          	</div>
	        </div>
	        <div class="ui error message">
	        </div>
	        <button class="ui fluid large button olive" type="submit">修改</button>
	    </form>
	</div>
@endsection
@section('js')
	<script type="text/javascript">
	@if(isset($message))
		$('.ui.error.message').html("<ul class=\"list\"><li>{{$message}}</li></ul>");
		$('.ui.error.message').css("display","block");
		<?php unset($message); ?>
	@endif

	$('.checkbox').checkbox();

	function showMsg(msg){
		$('.ui.error.message').html("<ul class=\"list\"><li>"+msg+"</li></ul>");
		$('.ui.error.message').css("display","block");
	}
	$('.ui.form.login').form({
	    fields: {
	      password : ['empty','maxLength[16]'],
	      repassword : ['empty','maxLength[16]'],
	    }
  	}).api({
/*	  		async : true,
			method : 'post',
			url: '/resetpassword',
			serializeForm: true,
			beforeSend:function(s){
				$('.ui.error.message').css("display","none");
				return s;
			},
		    onSuccess: function(response) {
		    	if(response.success)
		    		location.href="/?"+$.param( response.token );
		    	else
		    		showMsg("输入密码不一致！");
		    },
		   	onFailure: function(response) {
		   		showMsg("出错了，请联系管理员");
		    },
		    onAbort: function(errorMessage) {
		    	$(".button").click();
  			// 如果直接回车，就会返回到这里
			*/
			$.post("/resetpassword",qdata,function(data){
	            console.log(data);
	            if(alertStatus(data['status'])) {
					alert('登陆成功');
					location.href="/";
	            }
	        });
			}
	});
 	</script>
@endsection
