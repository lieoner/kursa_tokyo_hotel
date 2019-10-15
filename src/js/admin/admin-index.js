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
        var action = '';
        request = $.ajax({
            url: 'src/php/ajax.php?action=' + action,
            type: 'post',
        });
        request.done(function(response) {
            console.log(response);
        });
    }
    //check_login();
    console.log($.cookie('admins'));
    $(document).ready(function() {
        const login_form = $('#auth #login-form');
        var request;
        const $form = login_form.find('form');
        $form.submit(function(event) {
            event.preventDefault();
            if (request) {
                request.abort();
            }

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
                $.cookie('admins', JSON.stringify(JSON.parse(response).user));
                setTimeout(() => {
                    $inputs.prop('disabled', false);
                }, 1000);
            });
        });
    });
})(jQuery);
