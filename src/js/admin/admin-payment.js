function payment() {
    updatePaymentBookList();
    $('div.payment-content .payment-table-refresh').click(updatePaymentBookList);

    function updatePaymentBookList() {
        let request;
        if (request) {
            request.abort();
        }
        let action = 'getTotalCostTable';
        request = $.ajax({
            url: 'src/php/ajax.php?action=' + action,
            type: 'post',
        });

        request.done(function(response) {
            $('table.payment-table tbody').html(response);
            tableTriggers();
        });
    }

    function tableTriggers() {
        $('tbody tr').click(function(e) {
            e.preventDefault();
            $('table.payment-table tbody tr').removeClass('table-active');
            $('table.payment-table tbody tr').removeClass('table-success');
            $(this).addClass('table-active');
        });
        $('tbody tr').dblclick(function(e) {
            e.preventDefault();
            $('table.payment-table tbody tr').removeClass('table-active');
            $('table.payment-table tbody tr').removeClass('table-success');
            $(this).addClass('table-success');
            $('div.payment-content div.modal').show();
        });
    }

    $('div.payment-content #find-payment-table input').keyup(function(e) {
        let input_val = $(this).val();
        if (input_val.length == 0) {
            $('table.payment-table tbody tr').show(400);
        } else {
            $('table.payment-table tbody tr').each(function(index, element) {
                if (
                    !$(element)
                        .find('.bookNumber')
                        .data('bookNumber')
                        .toString()
                        .match(input_val.replace(/ /g, ''))
                ) {
                    $(element).hide(400);
                } else {
                    $(element).show(400);
                }
            });
        }
    });

    function afterConfirmPayment() {
        $('div.payment-content div.modal').hide();
        $('table.payment-table tbody tr.table-success').addClass('table-active');
        $('table.payment-table tbody tr.table-success').removeClass('table-success');
        setTimeout(() => {
            $('div.payment-content div.payment-confirmed').show(500);
            setTimeout(() => {
                $('div.payment-content div.payment-confirmed').hide(1000);
            }, 1000);
        }, 200);
    }

    $('div.payment-content div.modal .btn-secondary, div.payment-content div.modal .close').click(
        function(e) {
            e.preventDefault();
            $('table.payment-table tbody tr.table-success').addClass('table-active');
            $('table.payment-table tbody tr').removeClass('table-success');
            $('div.payment-content div.modal').hide();
        }
    );
    $('div.payment-content .btn-primary').click(function(e) {
        e.preventDefault();
        let request;
        if (request) {
            request.abort();
        }
        let client_id = $('table.payment-table tbody tr.table-success').data('clientId');

        let serializedData = 'id=' + client_id;
        let action = 'confirmPayment';
        request = $.ajax({
            url: 'src/php/ajax.php?action=' + action,
            type: 'post',
            data: serializedData,
        });
        request.done(function(response) {
            if (response) {
                afterConfirmPayment();
                updatePaymentBookList();
            }
        });
    });
}
module.exports = {
    main: payment,
};
