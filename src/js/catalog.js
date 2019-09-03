(function($) {
    $(document).ready(function() {
        var gridowl = $('#catalog .grid.owl-carousel').owlCarousel({
            center: true,
            responsive: {
                0: {
                    items: 1,
                    margin: 50,
                    smartSpeed: 1000,
                    autoplaySpeed: 2000,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                },
            },
        });
        gridowl.on('changed.owl.carousel', function(event) {
            if (event.page.index + 1 == event.page.count) {
                setTimeout(() => {
                    gridowl.trigger('to.owl.carousel', [0, 500]);
                }, 5050);
            }
        });
        $('.grid-image-big[num="0"]').addClass('active');
        $('.grid-image-small[num="0"]').addClass('active');

        $('.grid-image-small').click(function() {
            $('.grid-image-big.active').removeClass('active');
            $('.grid-image-small.active').removeClass('active');
            $('.grid-image-big[num=' + $(this).attr('num') + ']').addClass('active');
            $('.grid-image-small[num=' + $(this).attr('num') + ']').addClass('active');
        });

        gridowl.on('change.owl.carousel', function() {
            $('.grid-image-big.active').removeClass('active');
            $('.grid-image-small.active').removeClass('active');
            $('.grid-image-big[num="0"]').addClass('active');
            $('.grid-image-small[num="0"]').addClass('active');
        });

        $('#to-book .btn').click(function() {
            $('body').css('overflow', 'hidden');
            gridowl.trigger('stop.owl.autoplay');
            $('.modal').show(500);
            $(this).addClass('selectedRoom');
        });

        $('.modal .close').click(function() {
            $('#book-form')
                .find('input, select, button, textarea')
                .prop('disabled', false);
            var startfields = `<fieldset id="f1"><div class="input-daterange input-group"><div class="row col-12"><div class="col-md-6 col-12"><label class="control-label" for="dateFirst">Дата заезда</label><input type="text" class="input-sm form-control" id="dateFirst" name="start" autocomplete="off" /></div><div class="col-md-6 col-12"><label class="control-label" for="dateSecond">Дата отъезда</label><input type="text" class="input-sm form-control" id="dateSecond" name="end" autocomplete="off" /></div></div></div></fieldset>`;
            $('.modal fieldset#f2').replaceWith($(startfields));
            setdatepickers();
            $('.modal').hide(500);
            $('.modal-footer input[type=submit]').show();
            gridowl.trigger('play.owl.autoplay');
            $('body').css('overflow', 'auto');
            $('#book-form')[0].reset();
            $('.modal .alert-success').hide();
            $('.modal .alert-success .btn').prop('disabled', true);
            $('.modal .alert-danger').hide();
            $('.modal .alert-info').hide();
            $('#room-check span').show();
            $('#room-check').hide();
            $('#room-check img')
                .css('animation', '1s linear 0s normal none infinite running rot')
                .attr('src', 'src/image/findrat.webp');

            $('#to-book .btn').removeClass('selectedRoom');
        });

        function setdatepickers() {
            $('.input-daterange').datepicker({
                format: 'dd-mm-yyyy',
                todayBtn: 'linked',
                orientation: 'bottom auto',
                language: 'ru',
                daysOfWeekHighlighted: '0,6',
                maxViewMode: 2,
                startDate: new Date(),
            });
        }
        setdatepickers();
        var request;

        $('#book-form').submit(function(event) {
            event.preventDefault();
            if (request) {
                request.abort();
            }
            $('.modal .alert-danger').hide(500);
            $('.modal .alert-info').hide(500);

            $('#room-check img')
                .css('animation', '1s linear 0s normal none infinite running rot')
                .attr('src', 'src/image/findrat.webp');

            var $form = $(this);
            var inputs = $form.find('input.input-sm');

            var emtyfield = false;
            inputs.each(function() {
                $(this).removeClass('is-invalid');
                if (!$(this).val()) {
                    emtyfield = true;
                    $(this).addClass('is-invalid');
                }
            });
            if (emtyfield) {
                $('.modal .alert-danger').show(1000);
                $(this).addClass('is_invalid');
            } else {
                $('#room-check').show();
                $('.modal-footer input[type=submit]').hide();
                var $inputs = $form.find('input, select, button, textarea');
                var serializedData = $form.serialize();
                var room_id = $('#to-book .btn.selectedRoom').attr('data-roomtype-id');

                serializedData = serializedData + '&roomtypeID=' + room_id;
                var dates = serializedData;
                $inputs.prop('disabled', true);

                var action = 'checkFreeRoom';
                request = $.ajax({
                    url: 'src/php/ajax.php?action=' + action,
                    type: 'post',
                    data: serializedData,
                });

                request.done(function(response) {
                    setTimeout(() => {
                        var result = JSON.parse(response);
                        if (result.status) {
                            var fioinput = `<fieldset id="f2"><div class="input-userdata input-group"><div class="row col-12"><div class="col-md-6 col-12"><label class="control-label" for="dateFirst">Ваше имя</label><input type="text" class="input-sm form-control" id="uname" name="uname" autocomplete="on" /></div><div class="col-md-6 col-12"><label class="control-label" for="dateSecond">Ваш телефон</label><input type="text" class="input-sm form-control " id="uphone" name="uphone" autocomplete="on" /></div></div></div></fieldset>`;
                            $('.modal fieldset#f1').replaceWith($(fioinput));
                            IMask(document.getElementById('uphone'), {
                                mask: '+{7}(000)000-00-00',
                            });
                            IMask(document.getElementById('uname'), {
                                mask: /[^0-9]/,
                            });
                            $('.modal .alert-success').show(1000);
                            $('.modal .alert-success .btn').prop('disabled', false);
                            $('#room-check span').hide();
                            $('#room-check img')
                                .css('animation', '0')
                                .attr('src', 'src/image/dab.webp');
                            request = false;
                            continueBtnClick(result);
                            $.cookie('result', JSON.stringify(result));
                        } else {
                            $inputs.prop('disabled', false);
                            $('.modal .alert-info').show(1000);
                            $('#room-check span').hide();
                            $('#room-check img')
                                .css('animation', '0')
                                .attr('src', 'src/image/findpig.webp');
                            setTimeout(() => {
                                $('#room-check').hide(1000);
                                $('.modal-footer input[type=submit]').show(1000);
                            }, 3000);
                        }
                    }, 2000);
                });
                request.fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('The following error occurred: ' + textStatus, errorThrown);
                });
            }
        });

        function continueBtnClick(result) {
            $('.modal .alert-success .btn').on('click', function(event) {
                event.preventDefault();
                event.stopImmediatePropagation();
                if (request) {
                    request.abort();
                }
                var inputs = $('#book-form').find('input.input-sm');

                var emtyfield = false;
                inputs.each(function() {
                    $(this).removeClass('is-invalid');
                    if (!$(this).val()) {
                        emtyfield = true;
                        $(this).addClass('is-invalid');
                    }
                });
                if (emtyfield) {
                    $('.modal .alert-danger').show(1000);
                    $(this).addClass('is_invalid');
                } else {
                    action = 'createAccount';
                    console.log(JSON.parse($.cookie('result')));
                    var result = JSON.parse($.cookie('result'));
                    serData =
                        'begDate=' +
                        result.begDate +
                        '&endDate=' +
                        result.endDate +
                        '&free_roomID=' +
                        result.free_roomID +
                        '&' +
                        $('#book-form').serialize();

                    request = $.ajax({
                        url: 'src/php/ajax.php?action=' + action,
                        type: 'POST',
                        data: serData,
                    });
                    request.done(function(response) {
                        console.log(response);
                        request = false;
                        $('.modal .close').trigger('click');
                        $('.booked').show(500);
                        setTimeout(() => {
                            $('.booked').hide(500);
                        }, 3000);
                    });
                    request.fail(function(jqXHR, textStatus, errorThrown) {
                        console.error('The following error occurred: ' + textStatus, errorThrown);
                    });
                }
            });
            $.removeCookie('result');
        }
    });
})(jQuery);
