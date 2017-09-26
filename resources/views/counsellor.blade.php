<?php 
    use App\User;
    use App\Title;
?>
@extends('layouts.main',['now'=>0])
@section('title','后台')
@section('css')
<style>
.ui.container.content{padding: 60px 0;}
.ui.button{margin-top: 10px;margin-bottom: 0px;}
.ui.label.hide{
  display: none;
}
</style>
@endsection
@section('content')
<h1 class="ui header">
        <i class="olive Browser icon"></i>
        <div class="content">后台</div>
</h1> 
@if(Auth::user()->title()=='辅导员'||Auth::user()->name=='终极管理员')

<div class="ui info message">
  <div class="header">说明</div>
  <ul class="list">
  <li>删除后不能恢复，请小心操作</li>
  <li>修改后不能撤销，请小心操作</li>
  <li>密码为空则代表不修改密码</li>
  </ul>
</div>
<button class="ui olive button addStu"  type="submit">添加</button>   
  <h3>学生信息</h3>
  <?php $i=1;?>
  <table class="ui table">
    <thead>
      <th>序号</th>
      <th>学号</th>
      <th>姓名</th>
      <th>大班</th>
      <th>班级</th>
      <th>职务</th>
      <th>操作</th>
    </thead>
      <?php
        $stus=User::all();
      ?>
    @foreach($stus as $u)
    <tr>
    <td>{{$i++}}</td>
    <td class="userid">{{$u->id}}</td>
    <td class="username">{{$u->name}}</td>
    <td class="userclass">{{$u->class}}</td>
    <td><span class="usertitles"><?php foreach($u->titles() as $title)echo ($title->name.' '); ?></span>
    <span class="userworktime" hidden>{{$u->worktime()}}</span></td>
    <td><button class="ui olive button change"  type="submit">修改</button> 
   <button class="ui negative button delStu"  type="submit"> 删除</button></td>
    </tr> 
    @endforeach
  </table>
<div class="ui modal">
  <i class="close icon"></i>
  <div class="header">
    修改
  </div>
  <div class="content">
  <form class="ui form">
  {!! csrf_field() !!}
  <input type="hidden" name="type" value="add">
  <div class="filed"><label>学号</label><input type="text" name="id" maxlength="10" readonly></div>
    <div class="filed"><label>密码</label><input type="password" name="password" maxlength="20" value=""></div>
  <div class="filed"><label>姓名</label><input type="text" name="name" maxlength="20" ></div>
  <div class="filed"><label>班级</label><input type="text" name="class" maxlength="40" ></div>
  <div class="filed"><label>职务</label>
  <input type="hidden" name="newtitle" value="">

    <div class="ui blue labels" name="titles">
      @foreach(Title::All() as $title)
        <a class="ui label hide">{{$title->name}} <i class="icon close"></i></a>
      @endforeach
    </div>  
    <div class="ui labels" name="notitles">
      @foreach(Title::All() as $title)
        <a class="ui label">{{$title->name}} <i class="icon add"></i></a>
      @endforeach
    </div>
  </div>
  
 <div class="filed"> <label>任职时间</label><input type="number" name="worktime" min='0' max='1' step="0.5"></div>
  <div class="actions">
    <button class="ui olive button"  type="submit" >提交</button>
  </div>
  </form>
</div>

@else
  <h3>非法访问！</h3>
@endif
@endsection
@section('js')
<script type="text/javascript">

$('i.add').parent('a').click(function(){
  //点击灰色标签后
  var titleList = $('[name=titles]').children();
  //获取蓝色标签列表，遍历找到相同的
  for(var i = 0; i < titleList.length; ++i){
    var t = $(titleList[i]);
    //t为蓝色标签的DOM元素
    var addTitle=$(this).html().split(' ')[0];
    if(addTitle == t.html().split(' ')[0]){
      t.removeClass('hide');
      $(this).addClass('hide');
      $('[name=newtitle]').val($('[name=newtitle]').val()+addTitle+' ');
    }
  }
});

$('i.close').parent('a').click(function(){
  //点击蓝色标签后
  var titleList = $('[name=notitles]').children();
  //获取灰色标签列表，遍历找到相同的
  for(var i = 0; i < titleList.length; ++i){
    var t = $(titleList[i]);
    //t为灰色标签的DOM元素
    var removeTitle=$(this).html().split(' ')[0];
    if(removeTitle == t.html().split(' ')[0]){
      t.removeClass('hide');
      $(this).addClass('hide');
      $('[name=newtitle]').val($('[name=newtitle]').val().replace(removeTitle+' ',''));
    }
  }
});

  $('form').api({
      method : 'POST',
      url: 'counsellor',
      serializeForm: true,
      onSuccess:function(response){
          if(response.success){
              location=location;
          }else
              alert(response.info);
      },
      onFailure:function(response){
          alert("出错了，请联系管理员");
      }
  });

  $('.change').click(function(){
      var root=$(this).parent().parent();
      $('.ui.modal>.header').html("修改");
      $('[name=id]').attr("readonly","readonly")
      $('[name=id]').val(root.find('.userid').html());
      $('[name=class]').val(root.find('.userclass').html());
      $('[name=name]').val(root.find('.username').html());
      $('[name=newtitle]').val(root.find('.usertitles').html());
      $('[name=worktime]').val(root.find('.userworktime').html());

      $('[name=type]').val("change");
      var titles = root.find('.usertitles').html().split(/\s+/);

      var titleList = $('[name=titles]').children();

      var vis= new Array();
      for(var i = 0; i < titles.length; ++i){
        for(var j = 0; j < titleList.length; ++j){
          var t = $(titleList[j]);
          if(titles[i] == t.html().split(' ')[0]){
            t.removeClass('hide');
            vis[j]=true;
          }
          else if(!t.is('hide')&&!vis[j])//vis数组记录它不是这次改变的
            t.addClass('hide');//之前打开修改窗口则需要复原
        }
      }

      titleList = $('[name=notitles]').children();

      for(var i = 0; i < titles.length; ++i){
        for(var j = 0; j < titleList.length; ++j){
          var t = $(titleList[j]);
          if(titles[i] == t.html().split(' ')[0]){
            t.addClass('hide');vis[j]=true;
          }
          else if(t.is('hide') && !vis[j])
            t.removeClass('hide');
        }
      }

      $('.ui.modal')
        .modal('show')
      ;
  });

  $('.addStu').click(function(){
      $('.ui.modal>.header').html("添加用户");
      $('[name=id]').val("");
      $('[name=id]').removeAttr("readOnly");
      $('[name=class]').val("");
      $('[name=name]').val("");
      $('[name=type]').val("add");

      var titleList = $('[name=titles]').children();
      for(var i = 0; i < titleList.length; ++i)
        $(titleList[i]).addClass('hide');
      titleList = $('[name=notitles]').children();
      for(var i = 0; i < titleList.length; ++i)
        $(titleList[i]).removeClass('hide');

      $('.ui.modal')
        .modal('show')
      ;
  });

  $('.delStu').click(function(){
    if(confirm("确定删除？")){
      if(confirm("再确定一次，您要删除[id="+$(this).parent().siblings('.userid').html()+"]？")){
       $.ajax({
        type:"post",
        url:"counsellor",
        data:{type:"del",id:$(this).parent().siblings('.userid').html(),_token:"{{ csrf_token() }}"},
        dataType: "json",
        success: function(data){
          location=location;
        },
        error:function(data){
          alert("服务器错误");
        }
       });
     }
    }
  })
  $('.ui.modal')
  .modal()
;
</script>
@endsection
