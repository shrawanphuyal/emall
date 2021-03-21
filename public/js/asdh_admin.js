$(document).ready(function () {

});


function showSuccessMessage(message) {
  var $frontendMessageContainer = $('.frontend-message-container');

  $frontendMessageContainer.fadeIn();

  $('.frontend-message').html(message);

  var handle = setTimeout(function () {
    $frontendMessageContainer.fadeOut();
  }, 5000);
}