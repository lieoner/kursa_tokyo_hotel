global.jQuery = require('jquery');
import popper from 'popper.js';
import bootstrap from 'bootstrap';
import 'bootstrap-datepicker';
import 'bootstrap-datepicker/js/locales/bootstrap-datepicker.ru';
import 'owl.carousel/dist/assets/owl.carousel.css';
import 'owl.carousel';
import '../css/theme.css';
import '../css/style.css';
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
        var request;

        $('#book-form').submit(function(event) {
            event.preventDefault();
            if (request) {
                request.abort();
            }
            $('.modal .alert-danger').hide(500);

            var $form = $(this);
            var inputs = $form.find('input.input-sm');

            var emtyfield = false;
            inputs.each(function() {
                $(this).removeClass('is-invalid');
                if (!$(this).val()) {
                    emtyfield = true;
                    $(this).addClass('is-invalid');
                }
            });
            if (emtyfield) {
                $('.modal .alert-danger').show(1000);
                $(this).addClass('is_invalid');
            } else {
                $('#room-check').show();
                $('.modal-footer input[type=submit]').hide();
                var $inputs = $form.find('input, select, button, textarea');
                var serializedData = $form.serialize();
                var room_id = $('#to-book .btn.selectedRoom').attr('data-roomtype-id');

                serializedData = serializedData + '&roomtypeID=' + room_id;
                $inputs.prop('disabled', true);
                request = $.ajax({
                    url: 'src/php/ajax.php',
                    type: 'post',
                    data: serializedData,
                });
                request.done(function(response) {
                    setTimeout(() => {
                        console.log(response);
                        const result = JSON.parse(response);
                        $inputs.prop('disabled', false);
                        if (result.status) {
                            $('.modal .alert-success').show(1000);
                            $('#room-check span').hide();
                            $('#room-check img')
                                .css('animation', '0')
                                .attr('src', 'src/image/dab.png');
                        } else {
                            $('.modal .alert-info').show(1000);
                            $('#room-check span').hide();
                            $('#room-check img')
                                .css('animation', '0')
                                .attr('src', 'src/image/findpig.png');
                        }
                    }, 1100);
                });
                request.fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('The following error occurred: ' + textStatus, errorThrown);
                });
            }
        });
    });
})(jQuery);
