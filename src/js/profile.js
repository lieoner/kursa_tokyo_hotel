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
    });
})(jQuery);
