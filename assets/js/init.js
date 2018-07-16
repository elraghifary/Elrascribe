$( document ).ready(function() {
    $('.button-collapse').sideNav();
    $('.parallax').parallax();
    $('.collapsible').collapsible();
    $('.dropdown-nav').dropdown({
      belowOrigin: true, // Displays dropdown below the button
    }
  );
});

function closeToast() {
    $('#toast-container').fadeOut();
};
