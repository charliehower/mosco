@extends('layouts.main',['now' => $now])
@section('title','评分面板')
@section('content')
    <p id="hello">Hello，{{$me->name}}！班级：「{{$me->daban}} {{$me->class}}」 id：「{{$me->id}}」</p>
    <p>
    <?php if($mytitle)echo "任职：「".$mytitle."」"; ?>
    </p>
    <div class="ui pro_container olive segment">
        <div class="ui tiny olive progress" id="progressbar">
            <div class="bar"></div>
            <div class="label"></div>
        </div>
    </div>
        @if($mytitle != '辅导员')
        @foreach ($nav as $i)
        @if($i->id==$now)
            @if($i->type=='互评项')
                <h1 class="ui header">
                    <i class="olive Selected Radio icon"></i>
                    <div class="content">{{$i->name}}</div>
                </h1>
                <div class="ui info message">
                  <i class="close icon"></i>
                  <div class="header">评分介绍</div>
                  <ul class="list">
                    <?php echo $i->detail;?>
                  </ul>
                </div>
                <form class="ui form" action="" method="post">
              {!! csrf_field() !!}
                    <table class="ui selectable teal celled table center aligned">
                        <thead>
                            <tr>
                                <th>姓名</th>
                                @if($i->ename=='work')
                                <th>任职</th>
                                @endif
                                <th>学号</th>
                                <th>分数</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                        @if($user->id!=$me->id)
                            <?php
                                $oldval=$me->oldval($user,$i);
                             ?>
                            @if($i->ename == 'work' )
                                @if($begin=$me->toScore($user))
                                    <tr>
                                        <td class="table-text">
                                            <div>{{ $user->name }}</div>
                                        </td>
                                        
                                        <td class="table-text">
                                            <div>{{ $user->title() }}</div>
                                        </td>
                                        <td class="table-text">
                                            <div>{{ $user->id }}</div>
                                        </td>
                                        <td>
                                        <div class="inline fields">
                                            @for($v=$begin;$v<=$begin+1.01;$v+=0.2)
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="_{{$i->id}}_{{$user->id}}" value="{{$v}}" {{$oldval-$v<0.01&&$oldval-$v>-0.01?"checked":" " }} >
                                                    <label> {{$v}} </label>
                                                </div>
                                            </div>
                                            @endfor
                                        </div>

                                        </td>
                                    </tr>
                                @endif
                            @else
                                @if($user->class==$me->class)
                                <tr>
                                    <td class="table-text">
                                        {{ $user->name }}
                                    </td>
                                    <td class="table-text">
                                        {{ $user->id }}
                                    </td>
                                    <td>
                                    <div class="inline fields">
                                    @for($v=$i->begin;$v<=$i->end;$v+=$i->add)
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="_{{$i->id}}_{{$user->id}}" value="{{$v}}" {{$v-$oldval<0.01&&$v-$oldval>-0.01?"checked":" " }} >
                                                <label> {{$v}} </label>
                                            </div>
                                        </div>
                                    @endfor
                                    </div>
                                    </td>
                                </tr>
                                @endif
                            @endif
                        @endif
                        @endforeach
                        </tbody>
                    </table>
                    <button class="ui olive button" type="submit">{{$me->complish($i)?"更新":"OK!"}}</button>
                </form>
            @endif
        @endif
        
        @endforeach
        @foreach ($nav as $i)
        
        @if($i->id==$now)
            <?php
                $mycp=$me->complish($i);
            ?>
            @if($i->type=='自评项')

                <h1 class="ui header">
                    <i class="olive Selected Radio icon"></i>
                    <div class="content">{{$i->name}}</div>
                </h1>
                <div class="ui info message">
                  <i class="close icon"></i>
                  <div class="header">评分介绍</div>
                  <ul class="list">
                    <?php echo $i->detail;?>
                  </ul>
                </div>
                <form  class="ui form" action="" method="post">

              {!! csrf_field() !!}
                    @if($i->name=='文娱体育分')
                    <table class="ui selectable teal celled table center aligned">
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
                                <td>
                                <?php
                                    $oldval=$me->oldval($me,$i,$i->id."_".$act->id);
                                ?>
                                    <div class="inline fields">
                                        <div class="field">
                                        <div class="ui selection dropdown">
                                           <input type="hidden" name="_{{$i->id}}_{{$me->id}}_{{$act->id}}" value="{{$oldval?$oldval:0 }}">
                                          <i class="dropdown icon"></i>
                                          <div class="text">{{$oldval}}</div>
                                          <div class="menu">
                                            <div class="item" data-value="0">0</div>
                                            <?php $sco=explode('-', $act->seg);?>
                                            @for($j=0;$j<count($sco);$j+=2)
                                            @for($k=$sco[$j];$k<=$sco[$j+1]+0.001;$k+=$act->sub)
                                                <div class="item" data-value="{{$k}}">{{$k}}</div>
                                            @endfor
                                            @endfor
                                          </div>
                                        </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                      <div class="field">
                        <label>获得奖项</label>
                        <input type="text" name="_{{$i->id}}_{{$me->id}}" value="{{ $mycp?$me->oldval($me,$i):"" }}" placeholder="填写上一学年参加竞赛获得的奖项（需要提供复印件）">
                      </div>
                    @endif
                    <button class="ui olive button"  type="submit">{{ $mycp?"更新":"OK!"}}</button>
                </form>
            @endif
        @endif
        
        @endforeach
        
        @else

        @foreach ($nav as $i)
        @if($i->id==$now)
            <?php
                $mycp=$me->complish($i);
            ?>
            @if($i->type=='导评项' || $i->ename=='work')
                <h1 class="ui header">
                    <i class="olive cloud icon"></i>
                    <div class="content">{{$i->name}}</div>
                </h1>
                <div class="ui info message">
                  <i class="close icon"></i>
                  <div class="header">评分介绍</div>
                  <ul class="list">
                    <?php echo $i->detail;?>
                  </ul>
                </div>
                <form class="ui form" action="" method="post">
              {!! csrf_field() !!}
                <table class="ui selectable teal celled table  center aligned">
                    <thead>
                        <tr>
                            <th>姓名</th>
                            @if($i->ename=='work')
                            <th>任职</th>
                            @endif
                            <th>学号</th>
                            <th>分数</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $user)
                    @if($user->id!=$me->id)
                    <?php $oldval=$me->oldval($user,$i) ?>
                        <?php $tit=$user->title();?>
                        @if($i->ename == 'work')
                            @if($begin=$me->toScore($user))
                                <tr>
                                    <td class="table-text">
                                        <div>{{ $user->name }}</div>
                                    </td>
                                    <td class="table-text">
                                        <div>{{ $tit }}</div>
                                    </td>
                                    <td class="table-text">
                                        <div>{{ $user->id }}</div>
                                    </td>
                                    <td>
                                    <div class="inline fields">
                                        @for($v=$begin;$v<=$begin+1.01;$v+=0.2)
                                            <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="_{{$i->id}}_{{$user->id}}" value="{{$v}}" {{$oldval-$v<0.01&&$oldval-$v>-0.01?"checked":" " }} >
                                                <label> {{$v}} </label>
                                            </div>
                                            </div>
                                        @endfor
                                    </div>

                                    </td>
                                </tr>
                            @endif
                        @elseif($tit!='辅导员'){{-- 如果被打分的是同学--}}
                            <tr>
                                <td class="table-text">
                                    {{ $user->name }}
                                </td>
                                <td class="table-text">
                                    {{ $user->id }}
                                </td>
                                <td>
                                <div class="inline fields">
                                    <?php
                                        $oldval=$me->oldval($user,$i);
                                    ?>
                                    @if($i->ename=='xueshenghui')
                                        <div class="field">
                                        <input type="number" name="_{{$i->id}}_{{$user->id}}" value="<?php 
    $oldval=0;
    $xsh = \DB::table('title_user')->whereNull('title_id')->where('user_id',$user->id)->get();
    //将title_user表中title_id为Null的，且user_id为当前同学的条目取出来
    foreach ($xsh as $key => $value) {
        $oldval=max($oldval,($value->score-(ord($value->rank)-ord('A'))*0.1)*$value->time);//找出最大值
    }
    echo e($oldval);
                                        ?>" min="0" max="4" step="0.05">
                                        </div>
                                    @elseif($i->ename=='civilization')
                                        @for($v=$i->begin;$v<=$i->end+0.01;$v+=$i->add)
                                        <?php 
                                            if(isset($fin[$user->id][0]))$oldval=$fin[$user->id][0];
                                         ?>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="_{{$i->id}}_{{$user->id}}" value="{{$v}}" {{$v-$oldval<0.001&&$v-$oldval>-0.001?"checked":" " }} >
                                                    <label> {{$v}} </label>
                                                </div>
                                            </div>
                                        @endfor
                                    @else
                                        @for($v=$i->begin;$v<=$i->end+0.01;$v+=$i->add)
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="_{{$i->id}}_{{$user->id}}" value="{{$v}}" {{$v-$oldval<0.001&&$v-$oldval>-0.001?"checked":" " }} >
                                                    <label> {{$v}} </label>
                                                </div>
                                            </div>
                                        @endfor
                                    @endif
                                 </div>
                                </td>
                            </tr>
                        @endif
                    @endif
                    @endforeach
                    </tbody>
                </table>
                 <button class="ui olive button" type="submit">{{$mycp?"更新":"OK!"}}</button>
                </form>
            @endif
        @endif
        @endforeach
        @endif
    <div class="ui info message">
      <i class="close icon"></i>
      <div class="header">🤗</div>
      <ul class="list">
<li><b>献血在竞赛加分处填写</b></li>
	<li>德育、智育、竞赛鼓励分的总积分情况综合统计后得出每个学生本学年综合素质评价的考核成绩，作为评定学生奖学金以及三好学生、优秀干部等的依据。该竞赛鼓励分在大四保研时剔除，另外根据学院当年政策单独加竞赛加分。
</li><li>德育分带来的名次浮动不得超过本专业人数的10%。
<li>转专业同学的德育分，上半学期参加的文娱体育活动、担任的学生工作、宿舍卫生检查结果由原所在学院进行认定，并提交纸质版的认定材料。再由现学院依据认定材料，对照现学院的评定标准，以及下半学期转专业同学的表现，给出评分。
</li>
      </ul>
    </div>
@endsection
@section('footer')
@include('mosco.footer',['mytitle'=>$mytitle])
@endsection
@section('js')
    <script>
        $('.dropdown').dropdown();
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });
        var tol=$("[data-nav=1]").length;
        $(".progress>.label").html("共有"+tol+"项评分");
        $('#progressbar').progress({
            total:tol,
            value:{{$me->comnum()}},
            text: {
                active: '已完成 {value} of '+tol+' 个评分',
                success: tol+' 项评分全部完成啦!'
            }
        });
        $('form').api({
            async : true,
            method : 'post',
            url: '{{ url('mosco/update')}}',
            serializeForm: true,
            beforeSend:function(s){
                s.data.irst=$(this).children("button").html();
                return s;
            },
            onSuccess:function(response){
                if(response.success){
                    btn=$(this).children("button");
                    if(btn.html()=='更新')
                        btn.html("更新成功");
                    else {
                        btn.html("更新");
                        $('#progressbar').progress('increment');
                        location.href='{{ url('mosco')}}/nav/{{$now==13?1:($now==5?11:($now+1))}}';
                    }
                }else
                    alert(response.info);
            },
            onFailure:function(response){
                alert("出错了，请联系管理员");
            }
        });
    </script>
@endsection
