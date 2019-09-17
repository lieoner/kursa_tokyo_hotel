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
    });
})(jQuery);
