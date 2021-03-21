<!-- Mobile main menu -->
<a href="#"
   id="mobile-trigger"><i class="fa fa-list"
                          aria-hidden="true"></i></a>
<div id="mob-menu">
  <ul>
    <li class="current-menu-item"><a href="{{ route('home') }}">Home</a></li>
    <li><a href="{{ route('news.all') }}">Blog</a></li>
    <li class="app-link">
      <a href="https://play.google.com/store/apps/details?id=com.thesunbi.emall"
         target="_blank">Download App</a>
    </li>
  </ul>
</div> <!-- #mob-menu -->

<div id="tophead">
  <div class="container">
    <div id="main-navigation">
      <nav class="main-navigation">
        <ul>
          <li class="current-menu-item"><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ route('news.all') }}">Blog</a></li>
          <li class="app-link">
            <a href="https://play.google.com/store/apps/details?id=com.thesunbi.emall"
               target="_blank">Download App</a>
          </li>
        </ul>
      </nav> <!-- .site-navigation -->
    </div> <!-- #main-navigation -->
    <div id="cart-section">
      <a class="cart-icon"
         href="{{ route('cart-items') }}"><img src="{{ frontend_url('images/icons/cart-icon.png') }}">Cart<strong class="toal-cart">{{ $cartCount }}</strong></a>
    </div><!-- #cart-section -->
    <div id="right-tophead">
      <div id="login-section">
        <ul>
          @guest
            <li class="account-login login-trigger"><a href="#">Login</a></li>
            <li class="account-login"><a href="{{ route('signup') }}">Register</a></li>
          @else
            <li><a>Hi, <b>{{ auth()->user()->name }}</b></a></li>
            <li class="account-login"><a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a></li>
            <form action="{{ url('/logout') }}" method="post" id="logout-form" style="display: none;">@csrf</form>
          @endguest
        </ul>
      </div> <!-- .cart-section -->
    </div><!-- #right-tophead -->
  </div> <!-- .container -->
</div> <!-- #tophead -->