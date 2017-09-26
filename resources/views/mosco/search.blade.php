@extends('layouts.main',['now'=>0])
@section('title','公示查询页')
<?php 
    use App\User;
    $mytitle = $me->title();
?>
@section('content')

    <h1 class="ui header">
        <i class="olive Selected Radio icon"></i>
        <div class="content">公示查询页</div>
    </h1>
    <div class="ui info message">
      <i class="close icon"></i>
      <div class="header">说明</div>
      <ul class="list">
        <li>自评项的文娱体育项作为公示</li>
        <li>输入学号进行查询</li>
      </ul>
    </div>
    <form>
    <div class="ui search">
      <div class="ui icon input">
        <input class="prompt" type="text" id="id" placeholder="2015211...">
        <i class="circular search link icon" onclick="$('form').submit();"></i>
      </div>
      <button class="ui button olive" onclick="window.open('output_act');">导出EXCEL</button>
      <div class="results"></div>
    </div>
    </form>
    
    @if( $id )
        @if(($user=User::where('id', $id)->first())&&$user->title()!='辅导员')
            <p>学号：「{{$user->id}}」 查询到的用户：姓名：「{{$user->name}}」，班级：「{{$user->class}}」 </p>
            <table class="ui selectable celled table center aligned">
                <thead>
                    <tr>
                        <th>活动</th>
                        <th>最高分</th>
                        <th>备注</th>
                        <th>分数</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($acts as $act)
                    <tr>
                        <td class="table-text">
                            {{ $act->name }}
                        </td>
                        <td class="table-text">
                            {{ $act->max }}
                        </td>
                        <td class="table-text">
                            {{ $act->detail }}
                        </td>
                        <?php $oldval=$user->oldval($user,Nav::find(6),'6_'.$act->id);?>
                        <td class="text {{$oldval?"negative":""}}">
                        {{$oldval}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
        <div class="ui negative message">
          <i class="close icon"></i>
          <div class="header">不好意思！</div>
          <p>没有查到学号 「{{$id}}」 的同学。</p>
        </div>
        @endif
    @else
        <p> &nbsp;</p>
        <p> &nbsp;</p>
        <p> &nbsp;</p>
        <p> &nbsp;</p>
        <p> &nbsp;</p>
        <p> &nbsp;</p>
        <p> &nbsp;</p>
        <p> &nbsp;</p>
        <p> &nbsp;</p>
        <p> &nbsp;</p>
        <p> &nbsp;</p>
        <p> &nbsp;</p>
        <p> &nbsp;</p>
        <p> &nbsp;</p>
    @endif
    <p> &nbsp;</p>
@endsection
@section('footer')
@include('mosco.footer',['mytitle'=>$mytitle]);
@endsection
@section('js')
    <script>

    $('form').submit(function(e){
        e.preventDefault();
        location.href="{{ url('mosco/search?id=')}}"+$('#id').val();
    });
    </script>
@endsection
