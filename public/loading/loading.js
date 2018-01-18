$(document).ready(function(){
   // $('body').append('<div class="loader"><div class="spin-box"></div></div>');
   // $('body').append('<div class="new_loader"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>')
   $('body').append('<div class="load"><div class="dot"></div><div class="outline"><span></span></div></div>')
});

$(window).load(function() {
    // $('.new_loader').fadeOut(function() {
	$('.load').fadeOut(function() {
        $('.showLoader').show();
        if($('body').hasClass('login')) {
            $('html').css('background-image', 'url(../images/background.jpg)');
        }
    });
});
