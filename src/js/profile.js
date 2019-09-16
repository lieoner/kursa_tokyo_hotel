(function($) {
    $(document).ready(function() {
        $('.list-group .list-group-item-action').on('click', function(e) {
            e.preventDefault();
            $('.list-group .list-group-item-action.active').removeClass('active');
            $(this).addClass('active');
        });
    });
})(jQuery);
