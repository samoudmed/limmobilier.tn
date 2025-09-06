$(document).ready(function() {

  // Initialize the main image slider
  $(".property .main-img-slider").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    infinite: true,
    arrows: true,
    fade: true,
    autoplay: true,
    autoplaySpeed: 4000,
    speed: 300,
    lazyLoad: "ondemand",
    asNavFor: ".thumb-nav",
    prevArrow: '<div class="slick-prev"><i class="i-prev"></i><span class="sr-only sr-only-focusable"><</span></div>',
    nextArrow: '<div class="slick-next"><i class="i-next"></i><span class="sr-only sr-only-focusable">></span></div>'
  });

  // Initialize the thumbnail navigation slider
$(".thumb-nav").slick({
  slidesToShow: 4, // Default for large screens
  slidesToScroll: 1,
  // ... other settings
  responsive: [
    {
      breakpoint: 1024, // For screens smaller than 1024px
      settings: {
        slidesToShow: 5 // It will show 5 slides, not 8!
      }
    },
    {
      breakpoint: 600, // For screens smaller than 600px
      settings: {
        slidesToShow: 3 // It will show 3 slides
      }
    }
  ]
});

  // Custom event to add a 'slick-current' class to the active thumb
  $(".main-img-slider").on("afterChange", function(event, slick, currentSlide) {
    $(".thumb-nav .slick-slide").removeClass("slick-current");
    $(".thumb-nav .slick-slide:not(.slick-cloned)").eq(currentSlide).addClass("slick-current");
  });

});