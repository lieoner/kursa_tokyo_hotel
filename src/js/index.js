global.jQuery = require('jquery');
import popper from 'popper.js';
import bootstrap from 'bootstrap';
import IMask from 'imask';
import 'jquery.cookie/jquery.cookie.js';
import 'bootstrap-datepicker';
import 'bootstrap-datepicker/js/locales/bootstrap-datepicker.ru';
import 'owl.carousel/dist/assets/owl.carousel.css';
import 'owl.carousel';
import '../css/theme.css';
import '../css/style.css';
import './header.js';
import './uslugi.js';
import './catalog.js';

(function($) {
    $(document).ready(function() {
        $('.small-grid-image').click(function() {
            $('.small-grid-image.active').removeClass('active');
            $('.big-grid-image.active').removeClass('active');
            $('.small-grid-image[num=' + $(this).attr('num') + ']').addClass('active');
            $('.big-grid-image[num=' + $(this).attr('num') + ']').addClass('active');
        });
        var alertbox = $('.alert');
        if (alertbox.length != 0) {
            $('.alert .close').on('click', function(event) {
                event.preventDefault();
                alertbox.hide(1000);
            });
            var star = $('.star');
            if (star.length != 0) {
                star.on('click', function() {
                    alertbox.show(1000);
                });
            }
        }
        $('#overview .btn').on('click', function(event) {
            event.preventDefault();
            var id = $(this).attr('href'),
                top = $(id).offset().top;
            $('body,html').animate({ scrollTop: top }, 1000);
        });
    });
})(jQuery);
