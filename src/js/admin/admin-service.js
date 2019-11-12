function service() {
    getService();
    serviceTableHeaderTriggers();

    function getService(book_number = 'all', status = 'all') {
        var request;
        var action = 'getServiceByBookNumberAndStatus';
        var serializedData = 'book_number=' + book_number + '&status=' + status;
        request = $.ajax({
            url: 'src/php/ajax.php?action=' + action,
            type: 'post',
            data: serializedData,
        });

        request.done(function(response) {
            $('.service-table tbody').html(response);
        });
    }
    function serviceTableHeaderTriggers() {
        $('.dropdown-item').click(function(e) {
            e.preventDefault();
            $('.dropdown-item').removeClass('active');
            $(this).addClass('active');
            $(this)
                .parents('.sbStatusGroup')
                .trigger('click');
        });

        $('.service-table-refresh').click(function(e) {
            e.preventDefault();
            let query_status = $('.dropdown-item.active').data('query-status');
            let book_number = $('#service-table-book_number')
                .val()
                .toString()
                .replace(/ /g, '');
            if (book_number.length == 0) {
                book_number = 'all';
            }
            getService(book_number, query_status);
        });
    }
}

module.exports = {
    main: service,
};
