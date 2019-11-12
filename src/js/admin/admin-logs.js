function logs() {
    getLogs();
    logsTableHeaderTriggers();

    function getLogs(tab = 'all') {
        var request;
        var action = 'getLogs';
        var serializedData = 'tab=' + tab;
        request = $.ajax({
            url: 'src/php/ajax.php?action=' + action,
            type: 'post',
            data: serializedData,
        });

        request.done(function(response) {
            $('table.logs-table tbody').html(response);
        });
    }

    function logsTableHeaderTriggers() {
        $('.operation-label').click(function(e) {
            e.preventDefault();
            $('.dropdown-menu').addClass('active');
        });
        $('.dropdown-item').each(function(index, element) {
            $(element).click(function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('.dropdown-menu').removeClass('active');
                getLogs($(this).data('operation-id'));
            });
        });
    }
}

module.exports = {
    main: logs,
};
