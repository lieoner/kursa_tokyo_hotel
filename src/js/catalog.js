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

        $('#to-book .btn').click(function() {
            $('body').css('overflow', 'hidden');
            gridowl.trigger('stop.owl.autoplay');
            $('.modal').show(500);
            $(this).addClass('selectedRoom');
        });

        $('.modal .close').click(function() {
            $('.modal').hide(500);
            $('.modal-footer input[type=submit]').show();
            gridowl.trigger('play.owl.autoplay');
            $('body').css('overflow', 'auto');
            $('#book-form')[0].reset();
            $('.modal .alert-success').hide();
            $('.modal .alert-danger').hide();
            $('.modal .alert-info').hide();
            $('#room-check span').show();
            $('#room-check').hide();
            $('#room-check img')
                .css('animation', '1s linear 0s normal none infinite running rot')
                .attr('src', 'src/image/findrat.png');

            $('#to-book .btn').removeClass('selectedRoom');
        });

        $('.input-daterange').datepicker({
            format: 'dd-mm-yyyy',
            todayBtn: 'linked',
            orientation: 'bottom auto',
            language: 'ru',
            daysOfWeekHighlighted: '0,6',
            maxViewMode: 2,
            startDate: new Date(),
        });
    });
})(jQuery);
