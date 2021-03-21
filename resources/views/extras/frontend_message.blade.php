<style>
  .frontend-alert-message {
    position : fixed;width : auto;top : 20px;right : 20px;z-index : 9999;color : white;padding : 15px 20px;
  }

  .frontend-alert-message.success {
    background : rgba(0, 255, 0, 0.9);
  }

  .frontend-alert-message.warning {
    background : rgba(255, 69, 0, 0.9);
  }

  .frontend-alert-message.error {
    background : rgba(255, 0, 0, 0.9);
  }

  .frontend-alert-message .dismissible {
    float : right;margin-left : 20px;cursor : pointer;
  }

  .frontend-alert-message.show {
    display : block;
  }

  .frontend-alert-message.hide {
    display : none;
  }

  .frontend-alert-message > p {
    display : inline;
  }
</style>

<div class="frontend-alert-message hide">
  <p></p> <span class="fa fa-times dismissible"></span>
</div>

@push('script')
  <script>
    $(document).ready(function () {
      @if(session('success_message'))
      showAlertMessage('{{ session('success_message') }}', 'success', 4);
      @elseif(session('failure_message'))
      showAlertMessage('{{ session('failure_message') }}', 'warning', 4);
      @endif
      @if(count($errors))
      showAlertMessage('{{ $errors->first() }}', 'error');
      @endif

      $('.fa.fa-times').on('click', function (event) {
        event.preventDefault();
        $(this).parent().fadeOut();
      })
    });

    function showAlertMessage(message, type, hideAfterSomeSeconds) {
      var $frontendAlertMessage = $('.frontend-alert-message');
      $frontendAlertMessage.addClass('show ' + type);
      $frontendAlertMessage.removeClass('hide');
      $frontendAlertMessage.children('p').html(message);

      if (hideAfterSomeSeconds) {
        setTimeout(function () {
          $frontendAlertMessage.fadeOut();
        }, hideAfterSomeSeconds * 1000)
      }
    }
  </script>
@endpush