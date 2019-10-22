global.jQuery = require('jquery');
import popper from 'popper.js';
import bootstrap from 'bootstrap';
import IMask from 'imask';
import 'jquery.cookie/jquery.cookie.js';
import 'bootstrap-datepicker';
import 'bootstrap-datepicker/js/locales/bootstrap-datepicker.ru';

import '../../css/theme.css';
import '../../css/style.css';
import '../../css/admin.css';

(function($) {
    function check_login() {
        let request;
        let action = 'checkAdminHash';
        request = $.ajax({
            url: 'src/php/ajax.php?action=' + action,
            type: 'post',
        });
        request.done(function(response) {
            if (response == true) {
                $('#auth #login-form').hide(800);
                $('#auth').removeClass('active');

                $('#admin-main .is_auth').show(1000);
                $('#admin-main').addClass('active');

                setTimeout(() => {
                    $('#admin-main .is_auth').hide(500);
                    start_admin_module();
                }, 900);
            }
        });
    }
    check_login();

    $(document).ready(function() {
        //$('body').on('contextmenu', false);

        if (document.getElementById('book-number')) {
            IMask(document.getElementById('book-number'), {
                mask: '000 000 000',
            });
        }

        let book_number_find_form = $('#find-book-form').find('form');
        book_number_find_form.submit(function(e) {
            e.preventDefault();
            search_book_name($(this));
        });

        const login_form = $('#auth #login-form');
        let request;
        const $form = login_form.find('form');
        $form.submit(function(event) {
            event.preventDefault();
            if (request) {
                request.abort();
            }
            $('#auth')
                .find('.invalid-feedback')
                .hide(200);
            $('#auth')
                .find('.valid-feedback')
                .hide(200);

            let $inputs = $form.find('input, select, button, textarea');
            let serializedData = $form.serialize();
            $inputs.prop('disabled', true);
            let action = 'authorizeAdmin';
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

    function search_book_name($form) {
        let request;
        if (request) {
            request.abort();
        }
        let $inputs = $form.find('input, select, button, textarea');
        let serializedData = $form.find('#book-number');
        let action = 'selectBookByNumber';

        beforeAjax();

        if ($form.find('#book-number').val().length != 11) {
            $form.find('#book-number').addClass('is-invalid');
            $form.find('.invalid-feedback').show();
            afterAjax();
            return;
        }

        request = $.ajax({
            url: 'src/php/ajax.php?action=' + action,
            type: 'post',
            data: serializedData,
        });

        request.done(function(response) {
            console.log(JSON.parse(response));
            afterAjax();
        });

        function beforeAjax() {
            $form.find('#book-number').removeClass('is-invalid');
            $form.find('.invalid-feedback').hide();
            $inputs.prop('disabled', true);
        }
        function afterAjax() {
            $inputs.prop('disabled', false);
        }
    }

    function start_admin_module() {
        $('#auth').hide(500);
        $('#admin-main .admin-buttons').show(500);
        //$('#admin-main .booking_confirm').show(600);
    }
})(jQuery);
