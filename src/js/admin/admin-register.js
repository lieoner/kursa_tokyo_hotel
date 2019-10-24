(function() {
    $(document).ready(function() {
        function afterConfirmBook() {
            $('div.register-content div.modal').hide();
            $('table.book-table tbody tr.table-success').addClass('table-active');
            $('table.book-table tbody tr.table-success').removeClass('table-success');
            setTimeout(() => {
                $('div.register-content div.book-confirmed').show(500);
                setTimeout(() => {
                    $('div.register-content div.book-confirmed').hide(1000);
                }, 1000);
            }, 200);
        }

        $(
            'div.register-content div.modal .btn-secondary, div.register-content div.modal .close'
        ).click(function(e) {
            e.preventDefault();
            $('div.register-content div.modal').hide();
        });
        $('div.register-content .btn-primary').click(function(e) {
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

        $('div.register-content .book-table-refresh').click(updateNearestBookList);

        $('div.register-content #find-book-table input').keyup(function(e) {
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

        function mainTriggers() {
            $('#back_btn').click(function(e) {
                e.preventDefault();
                $('#back_btn').hide(500);
                //BACKBUTTON
                $('#admin-main .register-content').hide(500);
                $('#admin-main .admin-buttons').show(500);
            });
            $('div.register button').click(function(e) {
                e.preventDefault();
                $('#admin-main .admin-buttons').hide(500);
                //CONFIRM
                $('#admin-main .register-content').show(500);
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
                $('div.register-content div.modal').show();
            });
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
    });
})(jQuery);
