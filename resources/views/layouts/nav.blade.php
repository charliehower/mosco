<a class="item" href="/mosco"><i class="Star icon"></i> Mosco </a>
@if(Auth::User())
    @if(Auth::User()->title()=='辅导员'||Auth::User()->name=='终极管理员')
    <a class="item" href="/counsellor"><i class="Browser icon"></i> 后台 </a>
	@foreach (App\Nav::All() as $nav)
        @if($nav->type=='导评项'||$nav->ename=='work')
         <a data-nav="1" class="item {{$now==$nav->id?"active":""}}" href="{{ url('mosco')}}/nav/{{$nav->id}}">{{$nav->name}}</a>
        @endif
    @endforeach
    @else
    @foreach (App\Nav::All() as $nav)
        @if($nav->type=='互评项')
         <a data-nav="1" class="item {{$now==$nav->id?"active":""}}" href="{{ url('mosco')}}/nav/{{$nav->id}}">{{$nav->name}}</a>
        @endif
    @endforeach
    <div class="item ui dropdown">自评项<i class="dropdown icon"></i>
    <div class="menu">
    @foreach (App\Nav::All() as $nav)
        @if($nav->type=='自评项')
         <a data-nav="1" class="item {{$now==$nav->id?"active":""}}" href="{{ url('mosco')}}/nav/{{$nav->id}}">{{$nav->name}}</a>
        @endif
    @endforeach
    </div>
	</div>
@endif
@endif