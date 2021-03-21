<footer id="colophon"
        class="site-footer">
  <div class="footer-widget-area">
    <div class="container">
      <div class="footer-widget-main-wrap">
        <div class="inner-wrapper">
          <aside class="footer-widget-item col-grid-6">
            <img class="footer-logo"
                 src="{{ frontend_url('images/footer-logo.png') }}">
            <h3>{{ $company->name }}</h3>
            {!! $company->about !!}
            <ul class="quick-contact">
              <li class="quick-address">{{ $company->address }}</li>
              <li class="quick-contact">{{ $company->phone }}</li>
            </ul>
          </aside>
          <aside class="footer-widget-item col-grid-6">
            <h3>Join Our Newsletter</h3>
            <p> Get 15% Discount Code For Your First Purchase</p>
            <form class="news-letter-form"
                  action="{{ route('subscribe') }}"
                  method="post">
              @csrf
              <input class="news-letter-email"
                     type="email"
                     required
                     name="email"
                     value="{{ old('email') }}"
                     placeholder="Enter your email address">
              <input class="news-letter-submit"
                     type="submit"
                     value="Subscribe Now">
            </form>
          </aside>
        </div>
      </div>
    </div>
  </div>
  <div class="colophon-bottom">
    <div class="container">
      <div class="copyright">
        <p> Copyright © {{ date('Y') }} <a> {{ $company->name }}</a>. All rights reserved. </p>
      </div>
      <div class="social-links">
        <ul>
          <li>
            <!--Start of Zendesk Chat Script-->
            <script type="text/javascript">
              window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
                d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
              _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
                $.src="https://v2.zopim.com/?64WdhmLPT3mLzHAdrNpJEAQQ4NZFoYIN";z.t=+new Date;$.
                  type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
            </script>
            <!--End of Zendesk Chat Script-->
          </li>
          <li><a href="{{ $company->facebook_url }}"
                 target="_blank"></a></li>
          <li><a href="{{ $company->twitter_url }}"
                 target="_blank"></a></li>
        </ul>
      </div> <!-- .social-links -->
    </div><!--.container -->
  </div> <!-- .colophon-bottom -->
</footer> <!-- footer ends here -->