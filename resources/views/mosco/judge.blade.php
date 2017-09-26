<?php
    use App\User,App\Nav;
    $tol=0;
    $mytitle = $me->title();
    $id=isset($_GET['id'])?$_GET['id']:$me->id;
?>
@extends('mosco.main')
@section('title','分数详情页')
@section('nav')
<div class="ui eight fixed item main menu olive">
    <div class="ui container mynav">
    <h1 class="mosco" id="mosco">Mosco</h1>
    <a class="item" href="{{ url('mosco2.0')}}">返回评分面板</a>
    </div>
</div>
@endsection

@section('content')
    <h1 class="ui header">
        <i class="olive Selected Radio icon"></i>
        <div class="content">分数详情页</div>
    </h1>

        <div class="ui info message">
          <i class="close icon"></i>
          <div class="header">说明</div>
          <ul class="list">
            <li><p>学生会加分和学生工作取最高不累加,竞赛加分将加入总评（非德育分）.</p></li>
            <li><p>文娱体育最高为10分.</p></li>
            <li><p>点击下方“预览和打印”按钮可以导出表格文件</p></li>
			<li><p>由于总分是每项未四舍五入时计算的，故与当前各项之和会有偏差</p></li>
          </ul>
        </div>
    @if($mytitle=='辅导员')
        <form>
        <div class="ui search">
          <div class="ui icon input">
            <input class="prompt" type="text" id="id" placeholder="输入学号查询">
            <i class="circular search link icon" onclick="$('form').submit();"></i>
          </div>
        </div>
        </form>
    @endif

    @if($id)
        <?php $sb=User::find($id);?>
        @if( $sb )
<button class="ui button olive" onclick="location.href='{{ url('mosco')}}/judge?id={{$id-1}}';">-1</button>
<button class="ui button olive" onclick="preview();">预览并打印</button>
<button class="ui button olive" onclick="location.href='{{ url('mosco')}}/judge?id={{$id+1}}';">+1</button>
        <!--startprint-->
                <p>学号：{{$sb->id}} &nbsp; 姓名：{{$sb->name}} &nbsp; 班级： {{$sb->class}} &nbsp; 签字：</p>

            <?php
            /*
            <table class="ui selectable single line celled table center aligned">
                <thead>
                    <tr>
                        <th>项目</th>
                        @foreach($navs as $n)
                            @if($n->type=='互评项'&&$n->ename!='work')
                        <th rowspan="2">{{$n->name}}</th>
                            @endif
                        @endforeach
                        <th>项目</th>
                        @foreach($navs as $n)
                            @if($n->type=='互评项'&&$n->ename!='work')
                        <th rowspan="2">{{$n->name}}</th>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        <th>学号</th>
                        <th>学号</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $classmate=$sb->where('class',"$sb->class")->get();$i=0;?>
                    <tr>
                    @foreach ($classmate as $t)
                        @if($t->id!=$sb->id)
                            <td class="table-text positive">
                                {{ $t->id }}
                                <?php $i++;?>
                            </td>
                            @foreach($navs as $n)
                                @if($n->type=='互评项'&&$n->ename!='work')
                                    <td class="table-text">
                                    <?php

                                    $score=$sb->scores($t->id,$n->id);
                                    ?>
                                        {{$score?$score->score:($sb->complish($n)?$sb->defsco($t,$n->id):"/")}}
                                    </td>
                                @endif
                            @endforeach
                        <?php echo ($i%2?"":"</tr><tr>"); ?>
                        @endif
                    @endforeach

                    <?php echo ($i%2?"<td></td><td></td><td></td><td></td><td></td><td></td></tr>":""); ?>
                    </tr>
               </tbody>
            </table>


            <table class="ui selectable single line celled table center aligned">
                <thead>
                    <tr>
                        <th>学号</th>
                        <th>职位</th>
                        <th>得分</th>
                        <th>学号</th>
                        <th>职位</th>
                        <th>得分</th>
                        <th>学号</th>
                        <th>职位</th>
                        <th>得分</th>
                        <th>学号</th>
                        <th>职位</th>
                        <th>得分</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=0;?>
                <tr>
                    @if($sb->complish())
                    @foreach(User::All() as $user)
                        @if($sb->toScore($user))
                            <td class="table-text positive">
                                {{ $user->id }}
                                <?php $i++;?>
                            </td>
                            <td>{{$user->title()}}</td>
                            <td>{{$sb->oldval($user,Nav::find(8))}}</td>
                            <?php echo ($i%4?"":"</tr><tr>"); ?>
                        @endif
                    @endforeach
                    @endif
                    <?php for($j=0;$j<(4-$i%4)%4;$j++)echo "<td></td><td></td><td></td>"; ?>
                    </tr>
                </tbody>
            </table>

            <table class="ui selectable single line celled table center aligned">
                <thead>
                    <tr>
                        <th colspan="6"> 文娱体育活动自评</th>
                    </tr>
                    <tr>
                    <th>活动</th>
                    <th>分数</th>
                    <th>活动</th>
                    <th>分数</th>
                    <th>活动</th>
                    <th>分数</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=0;?>
                    <tr>
                        @foreach ($sb->scores()->get() as $t)
                            <?php
                                $array=explode('_',$t->nav);
                                $act=0;
                                if($array[0]==6)
                                    $act=$acts->find($array[1])->name;
                            ?>
                        @if($act)
                            <?php $i++;?>
                            <td class="table-text positive">
                                {{$act}}
                            </td>
                            <td class="table-text">
                                {{$t->score}}
                            </td>
                            <?php echo $i%3?"":"</tr><tr>";?>
                        @endif
                        @endforeach
                        <?php  for(;$i%3;$i++)echo "</td><td></td><td>";?>
                    </tr>
                </tbody>
            </table>
            <table class="ui selectable teal celled table center aligned">
                <thead>
                    <tr>
                    @foreach ($navs as $n)
                    @if ($n->ename != 'competition')
                        <th>{{$n->name}}</th>
                    @endif
                    @endforeach
                        <th>德育分</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=0;?>
                <tr>
					@foreach ($navs as $n)
					@if ($n->ename != 'competition')
					<td class="table-text">
						{{ $sb->result($n) }}
					</td>
					@endif
					@endforeach
				<td class="table-text">
					{{$sb->mosco()}}
				</td>
				</tr>
                </tbody>
            </table>
            @if($sb->award())
            <table  class="ui selectable celled table center aligned">
            <tr>
                <td class="positive">竞赛加分</td>
                <td>{{$sb->award()}}</td>
                <td>{{$sb->result(Nav::where('ename','competition')->first())}}分</td>
            </tr>
            </table>
            @endif
            */?>
            <!--endprint-->
            <button class="ui button olive" onclick="location.href='{{ url('mosco')}}/judge?id={{$id-1}}';">-1</button>
<button class="ui button olive" onclick="preview();">预览并打印</button>
<button class="ui button olive" onclick="location.href='{{ url('mosco')}}/judge?id={{$id+1}}';">+1</button>

    	@else
    	    <div class="ui negative message">
              <i class="close icon"></i>
              <div class="header">不好意思！</div>
              <p>没有查到学号 「{{$id}}」 的同学。</p>

            </div>
            <button class="ui button olive" onclick="location.href='{{ url('mosco')}}/judge?id={{$id-1}}';">-1</button>
            <button class="ui button olive" onclick="location.href='{{ url('mosco')}}/judge?id={{$id+1}}';">+1</button>
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
    @if($mytitle=='辅导员')
        $('form').submit(function(e){
            e.preventDefault();
            location.href='{{ url('mosco')}}/judge?id='+$('#id').val();
        });
    @endif
    function preview()
    {
    	bdhtml=window.document.body.innerHTML;
        sprnstr="<!--startprint-->";
        eprnstr="<!--endprint-->";
        prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17);
        prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr));
        selfhtml = window.document.body.innerHTML;
        window.document.body.innerHTML=prnhtml;
        window.print();
        window.document.body.innerHTML=selfhtml;
    }
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });
    </script>
@endsection
