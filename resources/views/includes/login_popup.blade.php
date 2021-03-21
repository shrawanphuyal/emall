<div style="background-color: rgba(0,0,0,0.9);position: fixed;top: 0;bottom: 0;left: 0;right: 0;height: 100%;overflow: auto;z-index: 999;display: none;"
     id="login-popup">
  <form class="reg-form"
        action="{{ route('signin') }}"
        style="position: absolute;background: #FFFFFF;left:calc(50% - 300px);top:0;padding:20px;width:600px;margin: 50px 0"
        method="post">
    @csrf
    <h3>Login Form</h3>

    <p><input type="email"
              class="form-control"
              required
              value="{{ old('email') }}"
              placeholder="Email address"
              name="email"></p>
    <p><input type="password"
              class="form-control"
              required
              placeholder="Password"
              name="password"></p>

    <button type="submit"
            class="custom-button login-submit">Login
    </button>
    <a type="submit"
       class="custom-button login-cancel">Cancel
    </a>
  </form>
</div>