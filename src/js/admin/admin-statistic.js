function statistic() {
    if ($('.statistic-content').length) {
        $('.statistic-content .jumbotron .close').click(function(e) {
            e.preventDefault();
            $('div.statistic-content .btn').removeClass('active');
            $('.statistic-content .jumbotron').hide();
            $('.graph .btn-group').remove();
        });

        $('div.statistic-content .btn').click(function(e) {
            e.preventDefault();
            $('.statistic-content .jumbotron ').show();
        });
        var ctx = $('#chart');
        var chart = new Chart(ctx, {
            type: 'bar',

            data: {
                labels: [],
                datasets: [
                    {
                        label: '',
                        borderWidth: 3,
                        backgroundColor: 'rgb(255, 255, 255, 0.6)',
                        borderColor: 'rgb(0, 0, 0)',
                        data: [],
                    },
                ],
            },
            options: { responsive: false },
        });

        var drawGraph = function(dataset, graphName) {
            let labels = [];
            let dataVals = [];
            dataset.forEach(element => {
                labels.push(element.Name);
                dataVals.push(element.Count);
            });
            chart.data.labels = labels;
            chart.data.datasets[0].label = graphName;
            chart.data.datasets[0].data = dataVals;
            chart.update();
        };

        var getTop = function(interval, what_top) {
            $.ajax({
                type: 'GET',
                url:
                    'src/php/ajax.php?action=getTop&interval=' + interval + '&what_top=' + what_top,
                dataType: 'json',
                success: function(response) {
                    drawGraph(response, 'Топ ' + what_top);
                },
            });
        };

        var drowTopServiceGraph = function() {
            $('.statistic-content')
                .find('.top-service-btn')
                .on('click', function() {
                    getTop('year', 'services');
                    ctx.before(`<div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-primary btn-sm">
                        <input type="radio" name="options" id="day" autocomplete="off"> неделя
                        </label>
                        <label class="btn btn-primary btn-sm">
                        <input type="radio" name="options" id="month" autocomplete="off"> месяц
                        </label>
                        <label class="btn btn-primary active btn-sm">
                        <input type="radio" name="options" id="year" autocomplete="off" checked=""> год
                        </label>
                        </div>`);
                });

            $('.statistic-content')
                .find('.btn-group-toggle .btn')
                .on('click', function() {
                    getTop(
                        $(this)
                            .find('input')
                            .attr('id'),
                        'services'
                    );
                });
        };

        var drowTopNumbersGraph = function() {
            $('.statistic-content')
                .find('.top-number-btn')
                .on('click', function() {
                    getTop('year', 'numbers');
                });

            $('.statistic-content')
                .find('.btn-group-toggle .btn')
                .on('click', function() {
                    event.stopPropagation();
                    getTop(
                        $(this)
                            .find('input')
                            .attr('id'),
                        'numbers'
                    );
                });
        };
        drowTopNumbersGraph();
        drowTopServiceGraph();
    }
}
module.exports = {
    main: statistic,
};
