global.jQuery = require('jquery');
import popper from 'popper.js';
import bootstrap from 'bootstrap';
import 'jquery.cookie/jquery.cookie.js';
import 'bootstrap-datepicker';
import 'bootstrap-datepicker/js/locales/bootstrap-datepicker.ru';

import '../../css/theme.css';
import '../../css/style.css';
import '../../css/admin.css';

(function($) {
    function check_login() {
        var request;
        var action = 'checkAdminHash';
        request = $.ajax({
            url: 'src/php/ajax.php?action=' + action,
            type: 'post',
        });
        request.done(function(response) {
            if (response == true) {
                $('#auth #login-form').hide(800);
                $('#auth').removeClass('active');

                $('#main .message').show(1000);
                $('main').addClass('active');

                setTimeout(() => {
                    $('#main .message').hide(800);
                    start_admin_module();
                }, 1300);
            }
        });
    }
    check_login();

    $(document).ready(function() {
        const login_form = $('#auth #login-form');
        var request;
        const $form = login_form.find('form');
        $form.submit(function(event) {
            event.preventDefault();
            if (request) {
                request.abort();
            }
            $('#auth')
                .find('.invalid-feedback')
                .hide(200);

            var $inputs = $form.find('input, select, button, textarea');
            var serializedData = $form.serialize();
            $inputs.prop('disabled', true);
            var action = 'authorizeAdmin';
            request = $.ajax({
                url: 'src/php/ajax.php?action=' + action,
                type: 'post',
                data: serializedData,
            });

            request.done(function(response) {
                check_login();
                setTimeout(() => {
                    if ($('#auth').hasClass('active')) {
                        $('#auth')
                            .find('.invalid-feedback')
                            .show(1000);
                    } else {
                        $('#auth')
                            .find('.valid-feedback')
                            .show(1000);
                    }
                    $inputs.prop('disabled', false);
                }, 1000);
            });
        });
    });

    function start_admin_module() {
        console.log('я работаю');
    }
})(jQuery);
