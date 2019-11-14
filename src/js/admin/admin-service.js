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
            let serviceQueries = JSON.parse(response);

            let uniqQueryGroups = getUniqQueryGroup(serviceQueries);

            $('.service-table tbody').html('');
            uniqQueryGroups.forEach(element => {
                if (element.active_count != 0) {
                    $('.service-table tbody').append('<tr class="table-info service-meta">');
                } else {
                    $('.service-table tbody').append('<tr class="table-success service-meta">');
                }
                let curRow = $('.service-table tbody tr').last();
                curRow.append(`<th class="book_number">${element.book_number}</th>`);
                curRow.append(`<th class="room_number">${element.room_number}</th>`);
                curRow.append(`<th class="active_count">${element.active_count}</th>`);
                curRow.append(`<th class="total_count">${element.total_count}</th>`);
                curRow.append(`<th class="total_cost">${element.total_cost}</th>`);
                curRow.after('<tr class="service-data"><th><table></th></tr>');
                curRow.next().html(`<thead>
                <tr>
                  <th scope="col">Название услуги</th>
                  <th scope="col">Кол-во</th>
                  <th scope="col">Дата заявки</th>
                  <th scope="col">Дата выполнения</th>
                  <th scope="col">Стоимость</th>
                </tr>
              </thead>
              <tbody>
              </tbody>`);
                let detail_table = curRow.next();
                element.items.forEach(det_element => {
                    if (det_element.sbStatus != 1) {
                        detail_table
                            .find('tbody')
                            .append(`<tr class="table-info" data-idsb="${det_element.IDsb}">`);
                    } else {
                        detail_table
                            .find('tbody')
                            .append(`<tr class="table-success" data-idsb="${det_element.IDsb}">`);
                    }
                    let curDetailRow = detail_table.find('tbody tr').last();
                    curDetailRow.append(`<th class="sName">${det_element.sName}</th>`);
                    curDetailRow.append(`<th class="sbCount">${det_element.sbCount}</th>`);
                    curDetailRow.append(
                        `<th class="sbCreateDate">${det_element.sbCreateDate}</th>`
                    );
                    if (det_element.sbResolveDate != null) {
                        curDetailRow.append(
                            `<th class="sbResolveDate">${det_element.sbResolveDate}</th>`
                        );
                    } else {
                        curDetailRow.append(`<th class="sbResolveDate">не выполнена</th>`);
                    }
                    curDetailRow.append(`<th class="totalCost">${det_element.totalCost}</th>`);
                    curDetailRow.click(function(e) {
                        e.preventDefault();
                        if ($(this).hasClass('table-info')) {
                            if ($(this).hasClass('table-active')) {
                                $(this).removeClass('table-active');
                            } else {
                                $(this).addClass('table-active');
                            }
                            if (
                                $(this)
                                    .parents('.service-data')
                                    .find('.table-active').length == 0
                            ) {
                                $('.service-table-confirmSolve').hide(500);
                            } else {
                                $('.service-table-confirmSolve').show(500);
                            }
                        }
                    });
                });
                curRow.click(function(e) {
                    e.preventDefault();
                    let detail_service_table = $(this).next();

                    if (detail_service_table.hasClass('active')) {
                        detail_service_table.removeClass('active');
                    } else {
                        detail_service_table.addClass('active');
                    }
                });
            });
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
            $('.service-table-confirmSolve').hide(500);
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

        $('.service-table-confirmSolve').click(function(e) {
            e.preventDefault();
            $('.service-content .modal').show();
        });
        $('.service-content .modal .btn-secondary, .service-content .modal .close').click(function(
            e
        ) {
            e.preventDefault();
            $('.service-content .modal').hide();
        });
        $('.service-content .modal .btn-primary').click(function(e) {
            e.preventDefault();
            let idsb_array = [];
            $('.service-data .table-active').each(function(index, element) {
                idsb_array.push($(element).data('idsb'));
            });
            let request;
            let serializedData = 'sbid_array=' + JSON.stringify(idsb_array);
            let action = 'confirmServiceSolve';
            request = $.ajax({
                url: 'src/php/ajax.php?action=' + action,
                type: 'post',
                data: serializedData,
            });
            request.done(function(response) {
                $('.service-content .modal').hide();
                $('.service-table-refresh').trigger('click');
            });
        });
    }
    function getUniqQueryGroup(queries_array) {
        const book_numbers = [...new Set(queries_array.map(item => item.book_number))];
        let outputs = [];
        book_numbers.forEach(number => {
            let uniqQuery = {
                    book_number: '',
                    room_number: '',
                    items: [],
                    total_count: 0,
                    active_count: 0,
                    total_cost: 0,
                },
                temp_cost = 0;
            queries_array.forEach(query => {
                if (query.book_number == number) {
                    uniqQuery.book_number = query.book_number;
                    uniqQuery.room_number = query.roomNumber;
                    uniqQuery.items.push(query);
                    if (query.sbStatus == 1) {
                        uniqQuery.total_cost += query.totalCost;
                    } else {
                        uniqQuery.active_count++;
                    }
                }
            });
            uniqQuery.total_count = uniqQuery.items.length;
            outputs.push(uniqQuery);
        });
        return outputs;
    }
}

module.exports = {
    main: service,
};
