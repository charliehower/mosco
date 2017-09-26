
<div id="footer" class="ui vertical olive center aligned footer segment">
    <div class="ui celled horizontal list">
	@if(Auth::User()->title()=="辅导员")
        <a class="item" href="{{ url('mosco/search')}}">公示查询</a>
        <a class="item" href="{{ url('mosco/judge')}}">不显眼的查询</a>
		<a class="item" href="{{ url('mosco/show')}}">所有同学分数</a>
	@endif
    </div>
    <p>Copyright © 2015BUPTscs Moral Score System</p>
    <p class="less-significant"><a target="_blank" href="http://sighttp.qq.com/authd?IDKEY=63bd4765e3444d9667e5e93cbbb26c527dfc81e5ccf0a7f5" >Design by flipped（bug反馈）</a>
    </p>
</div>
