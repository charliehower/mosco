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
		<h1 class="fnt fntlarge">BUPTscs</h1>
	    <form class="ui segment form form-horizontal login" role="form" method="POST" action="{{ url('/login') }}">

			  {!! csrf_field() !!}
	        <div class="field">
	        <div class="ui left icon input">
	        	<i class="user icon"></i>
	            <input type="text" name="id" placeholder="学号"  value="{{ old('id') }}">
	        </div>
	        </div>
	        <div class="field">
	        <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" placeholder="密码(默认为学号)">
          	</div>
	        </div>
			  <div class="field">
			  <div class="col-md-6 col-md-offset-4">
			    <div class="ui checkbox">
			      <input type="checkbox" tabindex="0" name="remember" class="hidden">
			      <label>下次自动登录</label>
			    </div>
			   </div>
			  </div>
	         @if(Session::has('message'))
	         <div class="ui error message">
	         <ul class="list"><li>{{ Session::get('message') }}</li></ul>
             </div>
             @endif
	        <button class="ui fluid large button olive" type="submit">
	        <i class="fa fa-btn fa-sign-in"></i>登录
	        </button>
				<br>
				<a href="{{ env('YB_CALLBACK') }}">
				  <button class="ui fluid large button blue" type="button">
					<i class="fa fa-btn fa-sign-in"></i>通过 易班账号 登陆
				  </button>
				</a>
	    </form>
	</div>
@endsection
@section('js')
	<script type="text/javascript">
	$('.checkbox').checkbox();
	@if(Session::has('message'))
		$('.ui.error.message').css("display","block");
	@endif
 	</script>
@endsection
