<script src="{{ frontend_url('third-party/jquery/jquery-3.2.1.min.js') }}"></script>
<script src="{{ frontend_url('third-party/jquery/jquery-migrate-3.0.0.min.js') }}"></script>
<!--Include all compiled plugins (below), or include individual files as needed-->
<script src="{{ frontend_url('third-party/sidr/js/jquery.sidr.js') }}"></script>
<script src="{{ frontend_url('third-party/cycle2/jquery.cycle2.js') }}"></script>
<script src="{{ frontend_url('third-party/slick/js/slick.min.js') }}"></script>
<script src="{{ frontend_url('js/custom.js') }}"></script>
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $(document).ready(function () {
    $('.login-trigger').on('click', function (event) {
      event.preventDefault();

      $('html').css('overflow', 'hidden');
      $('#login-popup').fadeIn();
    });

    $('.login-cancel').on('click', hideLoginPopUp);
    $('#login-popup').on('click', function (event) {
      if($(this).is(event.target)) {
        hideLoginPopUp(event);
      }
    });
  });

  function hideLoginPopUp(event) {
    event.preventDefault();

    $('html').css('overflow', 'auto');
    $('#login-popup').fadeOut();
  }
</script>