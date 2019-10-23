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

                whoAuthorize();

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

        const book_number_find_form = $('#find-book-form').find('form');
        book_number_find_form.submit(function(e) {
            e.preventDefault();
            search_book_name($(this));
        });

        const $login_form = $('#auth #login-form').find('form');
        $login_form.submit(function(event) {
            event.preventDefault();
            let request;
            if (request) {
                request.abort();
            }
            $('#auth')
                .find('.invalid-feedback')
                .hide(200);
            $('#auth')
                .find('.valid-feedback')
                .hide(200);

            let $inputs = $login_form.find('input, select, button, textarea');
            let serializedData = $login_form.serialize();
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

        function afterConfirmBook() {
            $('div.booking_confirm div.modal').hide();
            $('table.book-table tbody tr.table-success').addClass('table-active');
            $('table.book-table tbody tr.table-success').removeClass('table-success');
            setTimeout(() => {
                $('div.booking_confirm div.book-confirmed').show(500);
                setTimeout(() => {
                    $('div.booking_confirm div.book-confirmed').hide(1000);
                }, 1000);
            }, 200);
        }

        $(
            'div.booking_confirm div.modal .btn-secondary, div.booking_confirm div.modal .close'
        ).click(function(e) {
            e.preventDefault();
            $('div.booking_confirm div.modal').hide();
        });
        $('div.booking_confirm .btn-primary').click(function(e) {
            e.preventDefault();
            let request;
            if (request) {
                request.abort();
            }
            let client_id = $('table.book-table tbody tr.table-success').data('clientId');
            let room_id = $('table.book-table tbody tr.table-success').data('roomId');

            let serializedData = 'client_id=' + client_id + '&room_id=' + room_id;
            let action = 'confirmBook';
            request = $.ajax({
                url: 'src/php/ajax.php?action=' + action,
                type: 'post',
                data: serializedData,
            });
            request.done(function(response) {
                if (response) {
                    afterConfirmBook();
                    updateNearestBookList();
                }
            });
        });

        mainTriggers();
        tableTriggers();

        $('div.booking_confirm .book-table-refresh').click(updateNearestBookList);

        $('div.booking_confirm #find-book-table input').keyup(function(e) {
            let input_val = $(this).val();
            if (input_val.length == 0) {
                $('table.book-table tbody tr').show(400);
            } else {
                $('table.book-table tbody tr').each(function(index, element) {
                    if (
                        !$(element)
                            .find('.bookNumber')
                            .data('bookNumber')
                            .toString()
                            .match(input_val.replace(' ', ''))
                    ) {
                        $(element).hide(400);
                    } else {
                        $(element).show(400);
                    }
                });
            }
        });
    });

    function whoAuthorize() {
        let request;
        if (request) {
            request.abort();
        }
        let action = 'getAdminName';
        request = $.ajax({
            url: 'src/php/ajax.php?action=' + action,
            type: 'post',
        });

        request.done(function(response) {
            let admin = JSON.parse(response);
            $('#header #is-login').append(
                'logged as ' + admin.person_name + ' ' + admin.person_fam
            );
            $('#header #is-login').show(500);
        });
    }

    function mainTriggers() {
        $('#back_btn').click(function(e) {
            e.preventDefault();
            $('#back_btn').hide(500);
            //BACKBUTTON
            $('#admin-main .booking_confirm').hide(500);
            $('#admin-main .admin-buttons').show(500);
        });
        $('div.register button').click(function(e) {
            e.preventDefault();
            $('#admin-main .admin-buttons').hide(500);
            //CONFIRM
            $('#admin-main .booking_confirm').show(500);
            $('#back_btn').show(500);
        });
        $('div.payment button').click(function(e) {
            e.preventDefault();
            $('#admin-main .admin-buttons').hide(500);
            //PAYMENT
            $('#back_btn').show(500);
        });
        $('div.statistics button').click(function(e) {
            e.preventDefault();
            $('#admin-main .admin-buttons').hide(500);
            //STATISTIC
            $('#back_btn').show(500);
        });
        $('div.logs button').click(function(e) {
            e.preventDefault();
            $('#admin-main .admin-buttons').hide(500);
            //LOGS
            $('#back_btn').show(500);
        });
    }
    function tableTriggers() {
        $('tbody tr').click(function(e) {
            e.preventDefault();
            $('table.book-table tbody tr').removeClass('table-active');
            $('table.book-table tbody tr').removeClass('table-success');
            $(this).addClass('table-active');
        });
        $('tbody tr').dblclick(function(e) {
            e.preventDefault();
            $('table.book-table tbody tr').removeClass('table-active');
            $('table.book-table tbody tr').removeClass('table-success');
            $(this).addClass('table-success');
            $('div.booking_confirm div.modal').show();
        });
    }

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

    function updateNearestBookList() {
        let request;
        if (request) {
            request.abort();
        }
        let action = 'getNearestBookingTable';
        request = $.ajax({
            url: 'src/php/ajax.php?action=' + action,
            type: 'post',
        });

        request.done(function(response) {
            $('table.book-table tbody').html(response);
            tableTriggers();
        });
    }

    function start_admin_module() {
        $('#auth').hide(500);
        $('#admin-main .admin-buttons').show(500);
        //$('#admin-main .booking_confirm').show(600);
    }
})(jQuery);
