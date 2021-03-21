<!DOCTYPE html>
<html lang="en">

<head>
  @include('layouts.head')
  @stack('style')
</head>

<body>
  @include('extras.frontend_message')
  <div id="page" class="site">
    @include('layouts.navTop')

    @include('layouts.navBottom')

    @yield('content')

    @include('layouts.footer')
  </div> <!--#page-->

  <div id="btn-scrollup">
    <a title="Go Top" class="scrollup" href="#"><i class="fas fa-angle-up"></i></a>
  </div>

  @include('includes.login_popup')

  @include('layouts.foot')
  @stack('script')
</body>
</html>
