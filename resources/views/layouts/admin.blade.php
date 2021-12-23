<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> {{-- edge対応タグ --}} 
        <meta name="viewport" content="width=device-width, initial-scale=1"> {{-- 小画面時の文字や画像調整タグ --}} 

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- 各ページごとにtitleタグを入れるために@yieldで空けておく。 --}}
        <title>@yield('title')</title>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="{{ secure_asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ secure_asset('css/app.css') }}" rel="stylesheet"> {{-- secure_asset()はpublicディレクトリのパスを返す関数 --}} 
        <link href="{{ secure_asset('css/admin.css') }}" rel="stylesheet">
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
            <img class="gazou" style="max-height:30px;" src="{{ secure_asset('image/' . 'Quitan!.png') }}">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <a class="nav-link per200_font" href="{{action('Admin\CourseController@index')}}">🏠<span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link per200_font" href="{{action('Admin\CourseController@profile')}}">⚙ </a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                 
                </a>
                <div class="dropdown-menu per200_font" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="{{action('Admin\CourseController@csv2')}}">Create（単語帳を作る）</a>
                  <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
              </li>
            </ul>
          </div>
        </nav>
    </head>
    <body>
        <div id="app"></div>
        <main class="py-4">
            {{-- コンテンツをここに入れるための、@yield --}}
            @yield('content')
        </main>
    @yield('js')
    </body>
    <footer style="text-align: center">
      <p class="copyright">(C) 2021 T.Aoi</p>
    </footer>
</html>