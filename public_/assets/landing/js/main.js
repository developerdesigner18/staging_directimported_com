; (function ($) {
    "use strict";

    var offcanvasButton = $('.rid-offcanvas-btn'),
        sliderThreeCol = $('.rid-slider-three-col'),
        sliderFourCol = $('.rid-slider-four-col'),
        sliderFiveCol = $('.rid-slider-five-col'),
        totop = $('#toTop'),
        win = $(window);

    offcanvasButton.on('click', function (e) {
        e.preventDefault();

        $('.rid-offcanvas-btn').removeClass('open');

        $(this).toggleClass('open');

        $('.rid-offcanvas-sidebar').toggleClass('open');

        $('body').toggleClass('overlay-active');

        if ($('.rid-offcanvas-sidebar').hasClass('open')) {
            $('.rid-offcanvas-btn').addClass('open');
        }

    });

    $('.modal-btn').on('click', function () {
        $(this).closest('html').addClass('remove-scrollbar');
    });

    $('.btn-close').on('click', function () {
        $(this).closest('html').removeClass('remove-scrollbar');
    });

    if ($('.rid-slider-one-col').length) {
        $('.rid-slider-one-col').slick({
            speed: 800,
            slidesToShow: 1,
            adaptiveHeight: true,
            arrows: true,
            fade: true,
            slidesToScroll: 1,
            autoplay: true,
        });
    }

    $(document).ready(function () {

        if ($('.counter').length) {
            $('.counter').counterUp({
                delay: 10,
                time: 1500
            });
        }

        if ($('.circlechart').length) {
            $('.circlechart').circlechart();
        }

        if ($('select:not(.no-select2)').length) {
            $('select:not(.no-select2)').select2();
        }

        if ($('.popup-gallery').length) {
            $('.popup-gallery').magnificPopup({
                delegate: 'a',
                type: 'image',
                removalDelay: 500,
                gallery: {
                    enabled: true,
                    navigateByImgClick: true,
                    preload: [0, 1]
                },
                callbacks: {
                    beforeOpen: function () {
                        this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
                        this.st.mainClass = this.st.el.attr('data-effect');
                    }
                },
                closeOnContentClick: true,
                midClick: true
            });
        }

        if ($('.popup-youtube').length) {
            $('.popup-youtube').magnificPopup({
                disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,

                fixedContentPos: false
            });
        }

        // totop scroller
        win.on('scroll', function () {
            if (win.scrollTop() > 150) {
                totop.fadeIn();
            } else {
                totop.fadeOut();
            }
        });

        totop.on('click', function () {
            $("html,body").animate({
                scrollTop: 0
            }, 500);
        });

        //Three Col Slider
        if (sliderThreeCol.length) {
            sliderThreeCol.slick({
                speed: 800,
                slidesToShow: 3,
                adaptiveHeight: true,
                arrows: true,
                slidesToScroll: 1,
                autoplay: true,

                responsive: [
                    {
                        breakpoint: 992,
                        arrows: false,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 768,
                        arrows: false,
                        slidesToScroll: 1,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }


        //Four Col Slider

        if (sliderFourCol.length) {
            sliderFourCol.slick({
                speed: 800,
                slidesToShow: 4,
                adaptiveHeight: true,
                arrows: true,
                dots: false,
                slidesToScroll: 3,
                centerMode: false,
                autoplay: true,
                margin: 70,
                prevArrow: '<button class="slick-prev slick-arrow" aria-label="Previous" type="button" style="">Prev</button>',
                nextArrow: '<button class="slick-next slick-arrow" aria-label="Next" type="button" style="">Next</button>',
                responsive: [

                    {
                        breakpoint: 1400,
                        arrows: true,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                            arrows: true,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            arrows: true,
                            slidesToScroll: 1,
                        }
                    }
                ]
            });
        }

        //Five Col Slider

        if (sliderFiveCol.length) {
            sliderFiveCol.slick({
                speed: 800,
                slidesToShow: 5,
                adaptiveHeight: false,
                arrows: true,
                dots: false,
                slidesToScroll: 5,
                centerMode: false,
                autoplay: true,
                margin: 70,
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 3,
                            arrows: true,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            arrows: true,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            arrows: true,
                            slidesToScroll: 1,
                        }
                    }
                ]
            });
        }

    });

})(jQuery);


