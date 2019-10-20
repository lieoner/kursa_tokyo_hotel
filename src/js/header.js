(function($) {
    function check_login() {
        var request;
        var action = 'checkHash';
        request = $.ajax({
            url: 'src/php/ajax.php?action=' + action,
            type: 'post',
        });
        request.done(function(response) {
            var res = JSON.parse(response);
            if (res.status == true) {
                $('#login-form form').replaceWith(
                    'Привет, ' +
                        res.user_name +
                        '<br> <a href="/profile.php' +
                        '">Личный кабинет</a> <br><a id="logout" href="/src/php/ajax.php?action=logout">Выйти</a>'
                );
            } else {
                if (document.location.pathname != '/') {
                    if (document.location.pathname != '/index.php') {
                        document.location.href = '/';
                    }
                }
            }
            setTimeout(() => {
                if (!$('#header #login-form').hasClass('active')) {
                    if (
                        document.location.pathname == '/' ||
                        document.location.pathname == '/index.php'
                    ) {
                        $('#header #login-toggler').trigger('click');
                    }
                }
            }, 1500);
        });
    }

    check_login();

    $(document).ready(function() {
        const login_form = $('#header #login-form');
        $('#header #login-toggler').on('click', function() {
            if (login_form.length > 0) {
                if (login_form.hasClass('active')) {
                    login_form.removeClass('active').hide(1000);
                } else {
                    login_form.addClass('active').show(1000);
                }
            }
        });
        if (document.getElementById('login')) {
            IMask(document.getElementById('login'), {
                mask: '000 000 000',
            });
        }
        if (document.getElementById('pass')) {
            IMask(document.getElementById('pass'), {
                mask: '********',
            });
        }

        var request;
        const $form = login_form.find('form');
        $form.submit(function(event) {
            event.preventDefault();
            if (request) {
                request.abort();
            }

            $form.find('#short-pass-feedback').hide(500);
            $form.find('#short-login-feedback').hide(500);
            if ($form.find('#login').val().length != 11) {
                $form.find('#login').addClass('is-invalid');
                $form.find('#short-login-feedback').show(500);
            }
            if ($form.find('#pass').val().length != 8) {
                $form.find('#pass').addClass('is-invalid');
                $form.find('#short-pass-feedback').show(500);
            }

            var $inputs = $form.find('input, select, button, textarea');
            var serializedData = $form.serialize();
            $inputs.prop('disabled', true);
            var action = 'login';
            request = $.ajax({
                url: 'src/php/ajax.php?action=' + action,
                type: 'post',
                data: serializedData,
            });
            request.done(function(response) {
                setTimeout(() => {
                    check_login();
                    $inputs.prop('disabled', false);
                }, 1000);
            });
        });
    });
})(jQuery);
