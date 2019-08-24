(function($) {
    $(document).ready(function() {
        var gridowl = $('#catalog .grid.owl-carousel').owlCarousel({
            center: true,
            responsive: {
                0: {
                    items: 1,
                    margin: 50,
                    smartSpeed: 1000,
                    autoplaySpeed: 2000,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                },
            },
        });
        gridowl.on('changed.owl.carousel', function(event) {
            if (event.page.index + 1 == event.page.count) {
                setTimeout(() => {
                    gridowl.trigger('to.owl.carousel', [0, 500]);
                }, 5050);
            }
        });
        $('.grid-image-big[num="0"]').addClass('active');
        $('.grid-image-small[num="0"]').addClass('active');

        $('.grid-image-small').click(function() {
            $('.grid-image-big.active').removeClass('active');
            $('.grid-image-small.active').removeClass('active');
            $('.grid-image-big[num=' + $(this).attr('num') + ']').addClass('active');
            $('.grid-image-small[num=' + $(this).attr('num') + ']').addClass('active');
        });

        gridowl.on('change.owl.carousel', function() {
            $('.grid-image-big.active').removeClass('active');
            $('.grid-image-small.active').removeClass('active');
            $('.grid-image-big[num="0"]').addClass('active');
            $('.grid-image-small[num="0"]').addClass('active');
        });
    });
})(jQuery);
