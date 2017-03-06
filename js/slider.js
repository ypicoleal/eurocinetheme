$('.slider').slider({
  height: 231,
});

menu();
hoverFooter();


function hoverFooter(){
    $('.activar-hover').hover(function() {
      /* Stuff to do when the mouse enters the element */
      $(".footer-hover").addClass('active');
    }, function() {
      /* Stuff to do when the mouse leaves the element */
      $(".footer-hover").removeClass('active');
      console.log("remove");
    });
}



function menu(){
    $('.social').waypoint(function() {
        $('.cont-menu').addClass('all-fixed');
        $('.espacio').css('display', 'none');
    }, { offset: '50%' });

    $('.social').waypoint(function() {
      console.log("entro");
        $('.cont-menu').removeClass('all-fixed');
        $('.espacio').css('display', 'inherit');
    }, { offset: '60%' });
    $('.modal').modal();
}
