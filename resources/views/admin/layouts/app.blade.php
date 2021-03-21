<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
  @include('admin.layouts.header')
</head>

<body>

<div class="wrapper">
  @include('admin.layouts.leftSidebar')
  <div class="main-panel">
    @include('admin.layouts.navbar')
    <div class="content">
      <div class="container-fluid">
        @include('extras.backend_message')
        <div class="alert alert-success frontend-message-container"
             style="position: fixed;top: 5px;right: 5px;z-index: 9999;width: 500px;opacity: 0.9;display: none;">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
            {{--<span class="material-icons">clear</span>--}}
          </button>
          <p class="frontend-message"></p>
        </div>
        @yield('content')
      </div>
    </div>
    <footer class="footer">
      <div class="container-fluid">
        <p class="copyright pull-right">
          &copy;
          <a href="{{ route('home') }}">{{$company->name}}</a>,
          <script>
            document.write(new Date().getFullYear())
          </script>
        </p>
      </div>
    </footer>
  </div>
</div>

</body>

@include('admin.layouts.footer')
</html>