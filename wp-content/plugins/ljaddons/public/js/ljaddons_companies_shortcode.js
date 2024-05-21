$(document).ready(function() {
  $('.ljaddon_companies-carousel').slick({
    slidesToShow: 6,
    slidesToScroll: 6,
    autoplay: true,
    autoplaySpeed: 5000,
    arrows: true,
    dots: false,
    pauseOnHover: false,
    infinite: true,
    prevArrow: '<div class="slick-prev"><</div>',
    nextArrow: '<div class="slick-next">></div>',
    responsive: [{
      breakpoint: 768,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4,
      }
    }, {
      breakpoint: 520,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
      }
    }]
  });


    $('.partners-cats a').click(function() {
      if(!$(this).attr('value')){
          return false;
      } else {
		  $('.partners-cats a').removeClass( "active" );
          $(this).addClass( "active" );
		  $('.lj_company_boxed_card').hide();
		  $('.lj_company_boxed_card[id='+ $(this).attr('value') +']').show();
          
      }
    });
});