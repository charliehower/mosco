<!-- Stored in app/views/layouts/main.blade.php -->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>@yield('title') - 计院服务站</title>
    <script src="https://upcdn.b0.upaiyun.com/libs/jquery/jquery-2.0.2.min.js"></script>
    <link rel="stylesheet" href="//cdn.bootcss.com/semantic-ui/2.2.4/semantic.min.css">
    <style>
        .ui.menu{
            background: rgba(255, 255, 255, 0.56);
        }
        .ui.container.content{
            padding-top: 50px;
        }
        @font-face {
            font-family: 'Brandon';
            src: url('{{ secure_url('img/brandon_blk.eot?v1') }}');
            src: url('{{ secure_url('img/brandon_blk.eot?v1#iefix') }}') format('embedded-opentype'), url('{{ secure_url('img/brandon_blk.woff?v1') }}') format('woff');
        }
        .fnt {
            font-family: 'Brandon', sans-serif;
            color: #FFF;
            text-shadow: 0 0 1px #000, 0 0 2px rgba(0, 0, 0, .5), 0 0 3px rgba(0, 0, 0, .25);
            line-height: 40px;
            font-size: 36px;
            margin: 5px 10px;
            letter-spacing: 3px;
            text-align: center;
            padding-top:5px;
        }
        #footer{
            margin-top:40px;
        }
    </style>
    <script src="//cdn.bootcss.com/semantic-ui/2.2.4/semantic.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-serialize-object/2.5.0/jquery.serialize-object.min.js"></script>
    @yield('css')
</head>
<body>
    <div class="ui top fixed  menu">
      @include('layouts.nav',['now' => $now])
      <div class="right menu">
        @if (Auth::guest())
        <a class="ui item" href="{{ url('login')}}"><i class="sign in icon"></i>登录 </a>
        @else
        <span class="ui item" >{{ Auth::user()->name }} </span>
        <a class="ui item" href="{{ url('/resetpassword') }}"><i class="sign out icon"></i>修改密码 </a>
        <a class="ui item" href="{{ url('/logout') }}"><i class="sign out icon"></i>注销 </a>
        @endif
      </div>
    </div>
    <div class="ui container content">
        @yield('content')
    </div>
    @yield('footer')
    <script type="text/javascript">
        $('ul.pagination').addClass('ui menu');
        $('ul.pagination>li').addClass('item');
        $('.ui.container').height-=40;
    </script>
    @yield('js')
</body>
</html>
