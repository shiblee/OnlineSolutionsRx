// Quantity Cart Start
var qty = {
    'minus' : function(product_id) {
        var input = $("input[id='input-quantity-" + product_id + "']");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            var minValue = parseInt(input.attr('min'));
            if (!minValue) minValue = 1;
            if (currentVal > minValue) {
                input.val(currentVal - 1).change();
            }
            if (parseInt(input.val()) == minValue) {
                $(this).attr('disabled', true);
            }
        }
        else {
            input.val(1);
        }
    },
    'plus' : function(product_id) {
        var input = $("input[id='input-quantity-" + product_id + "']");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            var maxValue = parseInt(input.attr('max'));
            if (!maxValue) maxValue = 999;
            if (currentVal < maxValue) {
                input.val(currentVal + 1).change();
                $('.dis-' + product_id).prop('disabled', false);    
            }
            if (parseInt(input.val()) == maxValue) {
                $(this).attr('disabled', true);
            }
        }
        else {
            input.val(1);
        }
    },
    'cart' : function(product_id) {
        var qty = $('#input-quantity-' + product_id).val();
        if(qty > 0) cart.add(product_id,qty);       
        else {
            //alert($("input[name='hid-qty-msg']").val());
            $('#content').parent().before('<div class="alert alert-danger alert-dismissible"><i class="fa fa-check-circle"></i> ' + $("input[name='hid-qty-msg']").val() + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');    
            $('html, body').animate({ scrollTop: 0 }, 'slow');
            $('#input-quantity-' + product_id).val("1");
        }
    }
}
// Quantity Cart End


/* responsive menu */



/* for desktop */
function openNav() {
    document.getElementById("myOverlay").style.display = "block";
    document.getElementById("stamenu").className = "active menu-fixed";
}

function closeNav() {
    document.getElementById("myOverlay").style.display = "none";
    document.getElementById("stamenu").className = "menu-fixed";
}

// function openNav() {
//     $('body').addClass("active");
//     document.getElementById("mySidenav").style.width = "280px";
// }
// function closeNav() {
//     $('body').removeClass("active");
//     document.getElementById("mySidenav").style.width = "0";
// }
 /* loader */
$(window).load(function myFunction() {
  $(".s-panel .loader").removeClass("wrloader");
});

//go to top
$(document).ready(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('#scroll').fadeIn();
        } else {
            $('#scroll').fadeOut();
        }
    });
    $('#scroll').click(function () {
        $("html, body").animate({scrollTop: 0}, 600);
        return false;
    });
});


$(document).ready(function () {
    if ($(window).width() <= 991) {
        $('.menusp').appendTo('.appmenu');
        $('.curr').appendTo('.haccount');
        $('.langg').appendTo('.haccount');
         $('.rightac').appendTo('.appendsearch');
    }

    $('.toprightw .owl-carousel.owl-theme .owl-buttons').appendTo('.apponbtn');
    $('.logoapp').appendTo('.logosl');
    $('.imgsp').appendTo('.img-app');
});


// function openSearch() {
//     $('body').addClass("active-search");
//     document.getElementById("search").style.height = "auto";
//     $('#search').addClass("sideb");
//     // $('.search_query').attr('autofocus', 'autofocus').focus();
// }
// function closeSearch() {
//     $('body').removeClass("active-search");
//     document.getElementById("search").style.height = "0";
//     $('#search').addClass("siden");
//     // $('.search_query').attr('autofocus', 'autofocus').focus();
// }


$(document).ready(function () {
$("#ratep,#ratecount").click(function() {
    $('body,html').animate({
        scrollTop: $(".product-tab").offset().top 
    }, 1500);
});
});

/* dropdown effect of account */
$(document).ready(function () {
    if ($(window).width() <= 767) {
    $('.catfilter').appendTo('.appres');

    $('.dropdown a.account').on("click", function(e) {
        $(this).next('ul').toggle();
        e.stopPropagation();
        e.preventDefault();
    });
}
});

$(document).ready(function () {
$('.dropdown button.test').on("click", function(e)  {
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
});
});



/* dropdown */


/* sticky header */
//   if ($(window).width() >= 992) {
//  $(document).ready(function(){
//       $(window).scroll(function () {
//         if ($(this).scrollTop() > 100) {
//             $('.homemenu').addClass('fixed fadeInDown animated');
//         } else {
//             $('.homemenu').removeClass('fixed fadeInDown animated');
//         }
//       });
// });
// };



$(document).ready(function(){
        if ($(window).width() >= 992 && $(window).width() <= 1199){
    var count_block = $('.moremenuh').length;
    var number_blocks = 5;
    //console.log(count_block);
    if(count_block < number_blocks){
        return false; 
    }
    else {
        $('.moremenuh').each(function(i,n){
            $('moremenuh').addClass(i);
            if(i == number_blocks) {
            $(this).append('<li class="view_more my-menu"><a class="dropdown-toggle">More Categories<i class="fa fa-plus thumb_img pull-img"></i></a></li>');
            }
            if(i> number_blocks) {
            $(this).addClass('wr_hide_menu').hide();
            }
        });
        $('.view_more').click(function() {
            $(this).toggleClass('active');
            $('.wr_hide_menu').slideToggle();
        });
    }
}
});

$(document).ready(function(){
        if ($(window).width() >= 1200 && $(window).width() <= 1409){
    var count_block = $('.moremenuh').length;
    var number_blocks = 8;
    //console.log(count_block);
    if(count_block < number_blocks){
        return false; 
    }
    else {
        $('.moremenuh').each(function(i,n){
            $('moremenuh').addClass(i);
            if(i == number_blocks) {
            $(this).append('<li class="view_more my-menu"><a class="dropdown-toggle">More Categories<i class="fa fa-plus thumb_img pull-img"></i></a></li>');
            }
            if(i> number_blocks) {
            $(this).addClass('wr_hide_menu').hide();
            }
        });
        $('.view_more').click(function() {
            $(this).toggleClass('active');
            $('.wr_hide_menu').slideToggle();
        });
    }
}
});


$(document).ready(function(){
       if ($(window).width() >= 1410 && $(window).width() <= 1589){
    var count_block = $('.moremenuh').length;
    var number_blocks = 8;
    //console.log(count_block);
    if(count_block < number_blocks){
        return false; 
    }
    else {
        $('.moremenuh').each(function(i,n){
            $('moremenuh').addClass(i);
            if(i == number_blocks) {
            $(this).append('<li class="view_more my-menu"><a class="dropdown-toggle">More Categories<i class="fa fa-plus thumb_img pull-img"></i></a></li>');
            }
            if(i> number_blocks) {
            $(this).addClass('wr_hide_menu').hide();
            }
        });
        $('.view_more').click(function() {
            $(this).toggleClass('active');
            $('.wr_hide_menu').slideToggle();
        });
    }
}
});

$(document).ready(function(){
       if ($(window).width() >= 1411){
    var count_block = $('.moremenuh').length;
    var number_blocks = 9;
    //console.log(count_block);
    if(count_block < number_blocks){
        return false; 
    }
    else {
        $('.moremenuh').each(function(i,n){
            $('moremenuh').addClass(i);
            if(i == number_blocks) {
            $(this).append('<li class="view_more my-menu"><a class="dropdown-toggle">More Categories<i class="fa fa-plus thumb_img pull-img"></i></a></li>');
            }
            if(i> number_blocks) {
            $(this).addClass('wr_hide_menu').hide();
            }
        });
        $('.view_more').click(function() {
            $(this).toggleClass('active');
            $('.wr_hide_menu').slideToggle();
        });
    }
}
});


$(document).ready(function(){
    $('.modal').on('hide.bs.modal', function() {
      var memory = $(this).html();
      $(this).html(memory);
    })
});

/* sidemenu */
$(document).ready(function(){
    $("#common-home").parent().addClass("home-page");
$('#closeButton').on('click', function(e) {
    $(".topbsp").slideToggle('slow');
});
});

$(document).ready(function(){

    $('.img-thumb').click(function () {

     var src = $(this).attr('src');
     console.log($(this).closest(".product-thumb").find('.js-product-cover').attr('src',src));
});
});



// Webi Timer
$( document ).ready(function() {
  $('.wb_product_countdown').each(function() {

    $.wbCountDownTimer($(this));
  }); 
});
$.wbCountDownTimer = (function(event) {
  setInterval(function() {
    var countDownDate = new Date($(event).data("date")).getTime();
    var now = new Date().getTime();
    var distance = countDownDate - now;
      $(event).find('.wb_countdown_days .wb_countdown_days_digit').text(Math.floor(distance / (1000 * 60 * 60 * 24)));
      $(event).find('.wb_countdown_hours .wb_countdown_hours_digit').text(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)));
      $(event).find('.wb_countdown_minutes .wb_countdown_minutes_digit').text(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)));
      $(event).find('.wb_countdown_seconds .wb_countdown_seconds_digit').text(Math.floor((distance % (1000 * 60)) / 1000));
    if (distance < 0) {
      clearInterval(x);
      $(event).text("EXPIRED");
    }
}, 1000);
});

$(document).ready(function(){
$('.heading').html(function(i, html){
   return html.replace(/(\w+\s)/, '<strong class="headfirst">$1</strong>')
})
});


