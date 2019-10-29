(function($) {
    $(document).ready(function() {
        $('.list-group .list-group-item-action').on('click', function(e) {
            e.preventDefault();
            $('.list-group .list-group-item-action.active').removeClass('active');
            $('.item-data.active').removeClass('active');
            $(this).addClass('active');
            var data_tab = $(
                '.item-data[data-val=' +
                    $('.list-group .list-group-item-action.active').attr('data-val') +
                    ']'
            ).addClass('active');
        });

        if ($('#uphone').length > 0) {
            IMask(document.getElementById('uphone'), {
                mask: '+{7}(000)000-00-00',
            });
        }

        $('.edit-user').click(function(e) {
            e.preventDefault();
            $('.static-data').hide();
            $('.edit-data').show();
        });

        $('.edit-data form').submit(function(e) {
            e.preventDefault();

            var request;
            var action = 'editUserData';
            var serializedData = $('.edit-data form').serialize();
            serializedData += '&uid=' + $('#uname').attr('data-uid');
            request = $.ajax({
                url: 'src/php/ajax.php?action=' + action,
                type: 'post',
                data: serializedData,
            });

            request.done(function(response) {
                $('.static-uname').html($('#uname').val());
                $('.static-ufam').html($('#ufam').val());
                $('.static-uphone').html($('#uphone').val());

                $('.static-data').show();
                $('.edit-data').hide();
            });
        });

        serviceTriggers();
    });
})(jQuery);

function serviceTriggers() {
    $('.service-btn button').click(function(e) {
        e.preventDefault();
        $('.service-content>div').hide();
        $(
            '.service-content>div.' +
                $(this)
                    .data('type')
                    .toString()
        ).show();
        $('.service-content').show(500);
    });

    $('div.eat .sub-cnt').each(function(index, element) {
        $(element).click(function(e) {
            e.preventDefault();
            let count = $(this)
                .next()
                .val();
            if (count > 1) {
                count--;
            } else {
                count = 1;
            }
            $(this)
                .next()
                .val(count);
        });
    });
    $('div.eat .inc-cnt').each(function(index, element) {
        $(element).click(function(e) {
            e.preventDefault();
            let count = $(this)
                .prev()
                .val();
            if (count < 99) {
                count++;
            } else {
                count = 99;
            }
            $(this)
                .prev()
                .val(count);
        });
    });

    $('div.eat table tbody tr').click(function(e) {
        e.preventDefault();
        if ($(this).hasClass('table-active')) {
            $(this).removeClass('table-active');
        } else {
            $(this).addClass('table-active');
        }
    });
}
