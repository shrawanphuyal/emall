@extends('layouts.master')

@section('title', 'Signup')

@section('content')

  <div id="breadcrumb">
    <div class="container">
      <div aria-label="Breadcrumbs"
           class="breadcrumbs breadcrumb-trail">
        <ul class="trail-items">
          <li class="trail-item trail-begin"><a href="/"
                                                rel="home"><span>Home</span></a></li>
          <li class="trail-item trail-end"><span>Register</span></li>
        </ul>
      </div> <!-- .breadcrumbs -->
    </div><!-- .container -->
  </div> <!-- #breadcrumb -->

  <div id="content"
       class="site-content full-width-template">
    <div class="container">
      <div class="inner-wrapper">
        <div id="primary"
             class="content-area">
          <main id="main">
            <div class="inner-wrapper">
              <div class="col-grid-6">
                <h2>Register Now</h2>
                <form class="reg-form"
                      action="{{ route('signup') }}"
                      onsubmit="disableBtn()"
                      method="post">
                  @csrf

                  <p><input type="text"
                            class="form-control"
                            required
                            value="{{ old('first_name') }}"
                            placeholder="First Name"
                            name="first_name"></p>
                  <p><input type="text"
                            class="form-control"
                            required
                            value="{{ old('last_name') }}"
                            placeholder="Last Name"
                            name="last_name"></p>
                  <p><input type="email"
                            class="form-control"
                            required
                            value="{{ old('email') }}"
                            placeholder="Email address"
                            name="email"></p>
                  <p><input type="text"
                            class="form-control"
                            required
                            value="{{ old('address') }}"
                            placeholder="Permanent Address"
                            name="address"></p>
                  <p><input type="password"
                            class="form-control"
                            required
                            placeholder="Password"
                            name="password"></p>
                  <p><input type="password"
                            class="form-control"
                            required
                            placeholder="Confirm Password"
                            name="password_confirmation"></p>
                  <p><input type="tel"
                            class="form-control"
                            required
                            value="{{ old('phone') }}"
                            placeholder="Telephone Number"
                            name="phone"></p>
                  <p>Terms & Conditions<textarea rows="5"
                                                 readonly>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab accusantium corporis dolore dolorem enim error expedita, fugiat fugit laudantium nulla placeat quidem quis reiciendis sint vero. Accusantium aperiam blanditiis deleniti dolore ea inventore iusto minus qui. Ab consequuntur cupiditate delectus deserunt dignissimos dolor ea et, explicabo illo iusto laudantium molestias nihil non nostrum obcaecati odio officia perferendis perspiciatis praesentium quae quaerat reiciendis rem repellendus reprehenderit repudiandae saepe, sed tempore ut vel veritatis? Enim facilis impedit in nihil quo, ratione rerum vero! Asperiores consequuntur cumque distinctio earum explicabo maxime nisi nulla. Corporis error magni quam saepe temporibus! Ab deserunt iure sint.</textarea>
                  </p>
                  <p>
                    <label>
                      <input type="checkbox"
                             checked> Accept terms &amp; conditions
                    </label>
                  </p>
                  <button type="submit"
                          class="custom-button disable-on-submit">Register
                  </button>
                </form>
              </div>
              <div class="col-grid-6">
                <div class="optioner-al">
                  <h2>Already a registered Member ?</h2>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda aut corporis deleniti ducimus eaque est ex, nesciunt praesentium?
                    A dicta eligendi molestiae perspiciatis tempore, veritatis.
                    Ipsam mollitia natus necessitatibus sequi.</p>
                  <button type="button"
                          class="btn btn-md btn-regg md-trigger login-trigger"
                          data-modal="modal-3">Login
                  </button>
                </div>
              </div>
            </div>
          </main><!-- #main -->
        </div><!-- #primary -->
      </div> <!-- #inner-wrapper -->
    </div><!-- .container -->
  </div> <!-- #content-->

@endsection

@push('script')
  <script>
    function disableBtn() {
      var $btnToDisable = $('.disable-on-submit');

      $btnToDisable.css('cursor', 'wait');
      $btnToDisable.attr('disabled', true);
    }
  </script>
@endpush