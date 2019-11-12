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
    checkCart();
    getTotal();
    cartTriggers();

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

    $('.service-content .sub-cnt').each(function(index, element) {
        $(element).click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(element).prop('disabled', true);
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

            var service = $(this).parents('tr');
            if (service.hasClass('table-active')) {
                cartUpdate(service.data('service-id'), service.find('input.cnt').val());
            } else {
                service.trigger('click');
            }
        });
    });
    $('.service-content input.cnt').each(function(index, element) {
        $(element).click(function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
    });
    $('.service-content .inc-cnt').each(function(index, element) {
        $(element).click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(element).prop('disabled', true);
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

            var service = $(this).parents('tr');

            if (service.hasClass('table-active')) {
                cartUpdate(service.data('service-id'), service.find('input.cnt').val());
            } else {
                service.trigger('click');
            }
        });
    });

    $('.service-content table tbody tr').click(function(e) {
        e.preventDefault();
        if ($(this).hasClass('table-active')) {
            $(this).removeClass('table-active');
            cartRemove($(this).data('service-id'));
        } else {
            $(this).addClass('table-active');
            cartAdd(
                $(this).data('service-id'),
                $(this)
                    .find('input.cnt')
                    .val()
            );
        }

        if ($('.service-content table tbody tr.table-active').length == 0) {
            $('.total-service').hide(500);
        } else {
            $('.total-service').show(500);
        }
    });

    $('.cart-confirm').click(function(e) {
        e.preventDefault();
        var request;
        var action = 'confirmCart';
        request = $.ajax({
            url: 'src/php/ajax.php?action=' + action,
            type: 'post',
        });

        request.done(function(response) {
            checkCart();
            cartTriggers();
        });
    });

    $('.show_cost_info').click(function(e) {
        e.preventDefault();
        $('.modal.cost_info').show();
    });

    $('.modal.cost_info .close').click(function(e) {
        e.preventDefault();
        $('.modal.cost_info').hide();
    });

    function cartTriggers() {
        $('.total-service .cart-item .btn').click(function(e) {
            e.preventDefault();
            var serviceID = $(this)
                .parents('.cart-item')
                .data('service-id');
            $('.service-content tr[data-service-id=' + serviceID + ']').trigger('click');
            $('.service-content tr[data-service-id=' + serviceID + ']')
                .find('.cnt')
                .val(1);
            $(this)
                .parents('.cart-item')
                .remove();
        });
    }

    function checkCart() {
        var request;
        var action = 'checkCart';
        request = $.ajax({
            url: 'src/php/ajax.php?action=' + action,
            type: 'post',
        });

        request.done(function(response) {
            if (response != 0) {
                $('.total-service').show();
                $('.total-service ul.list-group').html(response);
                $('.cart-item').each(function(index, element) {
                    var serviceID = $(element).data('service-id');
                    var count = $(element)
                        .find('.count')
                        .html()
                        .split(' ')[0];
                    $('.service-content tr[data-service-id=' + serviceID + ']').addClass(
                        'table-active'
                    );
                    $('.service-content tr[data-service-id=' + serviceID + ']')
                        .find('.cnt')
                        .val(count);
                });
            } else {
                $('.total-service').hide(500);
                $('.total-service ul.list-group').html('');
                $('.service-content tr[data-service-id]').removeClass('table-active');
                $('.service-content tr[data-service-id]')
                    .find('.cnt')
                    .val(1);
            }
            cartTriggers();
        });
    }

    function getTotal(params) {
        var request;
        var action = 'getTotalCost';

        request = $.ajax({
            url: 'src/php/ajax.php?action=' + action,
            type: 'post',
        });

        request.done(function(response) {
            totalCost = JSON.parse(response);
            $('.living-cost').html(totalCost.living.cost);
            $('.living-days').html(totalCost.living.daysCount + ' дн.');
            $('.service-cost').html(totalCost.service.cost);
            $('.total-cost').html(totalCost.total.cost);
            if (totalCost.service.cost != 0) {
                totalCost.service.items.forEach(element => {
                    $('.service-more').append(
                        '<div class="col-6 h6" style="padding-left:35px">' + element.name + '</div>'
                    );
                    $('.service-more').append('<div class="col-2">' + element.count + ' шт.</div>');
                    $('.service-more').append(
                        '<div class="col-2 text-right h5">' +
                            element.cost * element.count +
                            '</div><div class="col-2">руб.</div>'
                    );
                });
            }
        });
    }

    function cartAdd(serviceID, count) {
        var request;
        var action = 'cartAddService';
        var serializedData = 'serviceID=' + serviceID + '&serviceCount=' + count;
        request = $.ajax({
            url: 'src/php/ajax.php?action=' + action,
            type: 'post',
            data: serializedData,
        });

        request.done(function(response) {
            $('.total-service ul.list-group').html(response);
            $('.inc-cnt, .sub-cnt').prop('disabled', false);
            cartTriggers();
        });
    }
    function cartUpdate(serviceID, count) {
        cartAdd(serviceID, count);
    }
    function cartRemove(serviceID) {
        var request;
        var action = 'cartRemoveService';
        var serializedData = 'serviceID=' + serviceID;
        request = $.ajax({
            url: 'src/php/ajax.php?action=' + action,
            type: 'post',
            data: serializedData,
        });

        request.done(function(response) {
            $('.total-service ul.list-group').html(response);
            cartTriggers();
        });
    }
}
