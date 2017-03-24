$('.slider').slider({
    height: 231,
});

$(".caja-p").each(function(index, el) {
    $(this).css({
        'height': $(this).width()
    });
});
$("#ciudad").val($(".collection-item.active").text());

$(".collection-item").click(function(event) {
  /* Act on the event */
  $(".collection-item").each(function(index, el) {
      if ($(el).hasClass('active')) {
        $(el).removeClass('active');
      }
  });
  $(this).addClass('active');
  $("#ciudad").val($(this).text());
});

var cont_patrocinador = $(".cont-slider-patrocinador");
var cont_equipo = $(".cont-slider-equipo");
var trailer = $(".trailer");
cont_patrocinador.css({
    height: cont_patrocinador.width()
});
cont_equipo.css({
    height: cont_equipo.width()
});
trailer.css({
    height: trailer.width() * 0.7
});

/*var max = 0;
$(".filtros a").each(function(index, el) {
  var elemen = $(this).width();
    if (elemen>max) {
      max = $(this).width();
    }
});
$(".filtros a").css({
  width: max,
});
*/


menu();
hoverFooter();


function hoverFooter() {
    $('.activar-hover').hover(function() {
        /* Stuff to do when the mouse enters the element */
        $(".footer-hover").addClass('active');
    }, function() {
        /* Stuff to do when the mouse leaves the element */
        $(".footer-hover").removeClass('active');
        console.log("remove");
    });
}



function menu() {
    $('.social').waypoint(function() {
        $('.cont-menu').addClass('all-fixed');
        $('.espacio').css('display', 'none');
    }, {
        offset: '50%'
    });

    $('.social').waypoint(function() {
        console.log("entro");
        $('.cont-menu').removeClass('all-fixed');
        $('.espacio').css('display', 'inherit');
    }, {
        offset: '60%'
    });
    $('.modal').modal();
}


var getMonthMatrix = function(contex, year, month) {
    var d = new Date();
    var firstDay = -(new Date(year, month).getDay() - 1);
    var calendar = document.querySelector(contex);
    var result = [];
    var full = false;
    for (var j = 0; j <= 5; j++) {
        var week = calendar.querySelector('.cb_week_' + (j + 1));
        week.classList.remove('cb_n');
        var k = 1;
        for (var i = firstDay; i <= (firstDay + 6); i++) {
            var aux = new Date(year, month, i);
            var day = week.querySelector('.cb_day_' + (k));
            var e = day.querySelector('.cb_day_num');
            e.innerHTML = aux.getDate();
            day.classList.remove('cb_nday');
            day.classList.remove('cb_now');
            if (aux.getFullYear() == d.getFullYear() && aux.getMonth() == d.getMonth() && aux.getDate() == d.getDate()) {
                day.classList.add('cb_now');
            }
            if ((aux.getMonth()) != (new Date(year, month).getMonth())) {
                day.classList.add('cb_nday');
                if ((j + 1) === 5) {
                    full = true;
                }
                if (full && (j + 1) === 6 && week.style.display != 'none') {
                    week.classList.add('cb_n');
                }
                if (k === 1 && (j + 1) === 6 && week.style.display != 'none') {
                    week.classList.add('cb_n');
                }
            }
            day.appendChild(e);
            k++;
        }
        firstDay = i;
    }
    return result;
};

function numero(num){
  if (num < 10) {
    return "0" + num;
  }
  return num;
}

$(document).ready(function() {
    var date = new Date();
    var months = [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre"
    ];
    var m = date.getMonth();
    var m2 = m;
    var d2 = date.getDate();
    var fecha = date.getFullYear()+'/'+numero(m2+1)+'/'+numero(d2); // input con el valor
    $("#fecha").val(fecha);
    $('.cb_month').text(months[m]);
    getMonthMatrix('.cb_calendar', date.getFullYear(), m);
    $('.cb_prev').click(function() {
        m = m - 1;
        m2 = m;
        $('.cb_month').text(months[m]);
        getMonthMatrix('.cb_calendar', date.getFullYear(), m);
    });
    $('.cb_next').click(function() {
        m = m + 1;
        m2 = m;
        $('.cb_month').text(months[m]);
        getMonthMatrix('.cb_calendar', date.getFullYear(), m);
    });
    $('.click_b').click(function() {
        var d2 = $(this).find('.cb_day_num').text();
        document.querySelector('.cb_now').classList.remove('cb_now');
        $(this).get(0).classList.add('cb_now');
        fecha = date.getFullYear()+'/'+numero(m2+1)+'/'+d2; // input con el valor
        $("#fecha").val(fecha);
    });

});



$('#checkbox').change(function(){
  setInterval(function () {
      moveRight();
  }, 3000);
});

$('#slider ul li').width($("#slider").width());
var slideCount = $('#slider ul li').length;
var slideWidth = $('#slider ul li').width();
var slideHeight = $('#slider ul li').height();
var sliderUlWidth = slideCount * slideWidth;

//$('#slider').css({ width: "100%", height: "100%" });

$('#slider ul').css({ width: sliderUlWidth, marginLeft: - slideWidth });

  $('#slider ul li:last-child').prependTo('#slider ul');

  function moveLeft() {
      $('#slider ul').animate({
          left: + slideWidth
      }, 200, function () {
          $('#slider ul li:last-child').prependTo('#slider ul');
          $('#slider ul').css('left', '');
      });
  };

  function moveRight() {
      $('#slider ul').animate({
          left: - slideWidth
      }, 200, function () {
          $('#slider ul li:first-child').appendTo('#slider ul');
          $('#slider ul').css('left', '');
      });
  };

  $('a.control_prev').click(function () {
      moveLeft();
  });

  $('a.control_next').click(function () {
      moveRight();
  });
