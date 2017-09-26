<?php 
use App\Score;
use App\User;
$id=1;
$mytitle = $me->title();
$tol=0;
$res=null;
$users=User::paginate(10);
?>
<?php $title=($mytitle=="辅导员"?"学生成绩":"我的成绩");?>
@extends('layouts.main',['now'=>0])
@section('title',$title)
@section('nav')
<div class="ui eight item fixed main menu olive">
    <div class="ui container mynav">
    <h1 class="mosco" id="mosco">Mosco</h1>
    <a class="item" href="{{ url('/mosco')}}">返回评分面板</a>
    </div>
</div> 
@endsection

@section('content')
    <p>Hello，{{$me->name}}！班级：「{{$me->class}}」 id：「{{$me->id}}」</p>

    <h1 class="ui header">
        <i class="olive Selected Radio icon"></i>
        <div class="content">{{$title}}</div>
    </h1>

    <div class="ui info message">
      <i class="close icon"></i>
      <div class="header">说明</div>
      <ul class="list">
        <li>当前各项为总分，导出的excel里各项将显示平均分</li>
        <li>点击下方“导出excel”按钮可以导出表格文件</li>
        <li>鼠标移至评分项目名上可以查看评分介绍</li>
      </ul>
    </div>
    <table class="ui selectable teal celled table center aligned">
        <thead>
            <tr>
                <th></th>
                @foreach ($navs as $n)
                <th  title="{{$n->ename!='work'?$n->detail:'学生工作加分'}}">{{$n->name}}</th>
                @endforeach
                {{-- title是鼠标移上去显示detail介绍，学生工作含有表格故不显示detail --}}
                <th>总分</th>
            </tr>
        </thead>
        <tbody>
        @if($mytitle=="辅导员")
        @foreach ($users as $u)
            @if($u->title()!='辅导员')
            <?php $tol=0;?>
            <tr>
                <td class="table-text">
                    {{ $u->name }}
                </td>
                @foreach ($navs as $n)
                <td class="table-text">
                    <?php echo ($n->ename=="competition"&&($res=$u->hasMany('App\Result')->where('nav_id',$n->id)->first()))?$res->award:round($u->result($n),2); ?>
                </td>
                @endforeach
                <td>
                    {{round($u->mosco(),2)}}
                </td>
            </tr>
            @endif
        @endforeach
        @else
            <?php $tol=0;?>
            <tr>
                <td class="table-text">
                    {{ $me->name }}
                </td>
                @foreach ($navs as $n)
                <td class="table-text">
                    <?php echo ($n->ename=="competition"&&($res=$me->hasMany('App\Result')->where('nav_id',$n->id)->first()))?$res->award:round($me->result($n),2); ?>
                </td>
                @endforeach
                <td>
                    {{round($me->mosco(),2)}}
                </td>
            </tr>
        @endif
        </tbody>
    </table>
    
    <button class="ui button olive" onclick="window.open('output');">导出EXCEL</button>
    
    @if($mytitle=='辅导员')
    <p> {!!$users->render()!!}</p>
    @endif
@endsection
@section('footer')
@include('mosco.footer',['mytitle'=>$mytitle]);
@endsection
