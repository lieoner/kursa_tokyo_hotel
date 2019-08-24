(function($) {
    $(document).ready(function() {
        var owl = $('#eat.owl-carousel').owlCarousel({
            center: true,
            loop: true,
            margin: 10,
            responsive: {
                0: {
                    items: 1,
                },
                400: {
                    items: 3,
                },
                1000: {
                    items: 4,
                },
            },
        });

        var is_show_eat = false;
        var is_show_bar = false;
        var is_show_parkur = false;
        var is_show_pool = false;

        $('#parkur-title').on('click', function() {
            if (is_show_eat) {
                $('#eat-title').trigger('click');
            }
            if (is_show_bar) {
                $('#bar-title').trigger('click');
            }
            if (is_show_pool) {
                $('#pool-title').trigger('click');
            }
            if (!is_show_parkur) {
                $('#parkur').show(1000);
                is_show_parkur = true;
            } else {
                $('#parkur').hide(1000);
                is_show_parkur = false;
            }
        });
        $('#pool-title').on('click', function() {
            if (is_show_eat) {
                $('#eat-title').trigger('click');
            }
            if (is_show_bar) {
                $('#bar-title').trigger('click');
            }
            if (is_show_parkur) {
                $('#parkur-title').trigger('click');
            }
            if (!is_show_pool) {
                $('#pool').show(1000);
                is_show_pool = true;
            } else {
                $('#pool').hide(1000);
                is_show_pool = false;
            }
        });

        $('#bar-title').on('click', function() {
            if (is_show_eat) {
                $('#eat-title').trigger('click');
            }
            if (is_show_parkur) {
                $('#parkur-title').trigger('click');
            }
            if (is_show_pool) {
                $('#pool-title').trigger('click');
            }
            if (!is_show_bar) {
                $('#bar').show(1000);
                is_show_bar = true;
            } else {
                $('#bar').hide(1000);
                is_show_bar = false;
            }
        });

        $('#eat-title').on('click', function() {
            if (is_show_bar) {
                $('#bar-title').trigger('click');
            }
            if (is_show_parkur) {
                $('#parkur-title').trigger('click');
            }
            if (is_show_pool) {
                $('#pool-title').trigger('click');
            }
            if (!is_show_eat) {
                $('#eat')
                    .animate({ 'max-height': '250' }, 500)
                    .show(500)
                    .animate({ 'min-height': '250' }, 500);
                setTimeout(() => {
                    owl.trigger('refresh.owl.carousel');
                    $('#eat .owl-item').fadeTo(500, 1);
                }, 1000);
                is_show_eat = true;
            } else {
                $('#eat .owl-item').fadeTo(500, 0);
                $('#eat')
                    .animate({ 'max-height': '0' }, 500)
                    .animate({ 'min-height': '0' }, 500)
                    .hide(500);
                is_show_eat = false;
            }
        });
    });
})(jQuery);
