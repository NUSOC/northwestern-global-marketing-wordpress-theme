(function($){
    $(function(){
        var e=[];
        $(".swiper-container").each(function(s){
            var t=$(".swiper-button-next:eq("+s+")"),i=$(".swiper-button-prev:eq("+s+")"),n=$(this);
            var swiper = new Swiper(this, {
                slidesPerView: 1,
                spaceBetween: 30,
                keyboardControl: !0,
                nextButton: t,
                prevButton: i,
                loop: 1,
                init: false,
                speed: 500,
                autoplay: 4000,
                a11y: !0,
                prevSlideMessage: "Previous slide",
                nextSlideMessage: "Next slide",
                firstSlideMessage: "This is the first slide",
                lastSlideMessage: "This is the last slide",/*onReachEnd:function(e){n.append('<div class="swiper-button-next refresh-button" aria-disabled="false" tabindex="0" role="button" aria-label="Return to first slide" style="background-color: rgb(78, 42, 132); background-image: url(\'//common.northwestern.edu/v8/css/images/icons/refresh.svg\');"></div>'),n.on("click",".refresh-button",function(){e.slideTo(0),$(this).remove()}),n.find(".swiper-button-prev").click(function(){$(".refresh-button").remove()})}*/
            });
            swiper.on('transitionEnd', function () {
                n.find('.swiper-slide:not(.swiper-slide-active)').attr('aria-hidden', true);
                n.find('.swiper-slide.swiper-slide-active').attr('aria-hidden', false);
            });
            swiper.init();
            e.push(swiper);
        });
    });
})(jQuery);