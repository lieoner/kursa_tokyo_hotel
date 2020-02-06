function statistic() {
    if ($('.statistic-content').length) {
        $('.statistic-content .jumbotron .close').click(function(e) {
            e.preventDefault();
            $('div.statistic-content .btn').removeClass('active');
            $('.statistic-content .jumbotron').hide();
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

        var drawGraph = function(dataset, graphName, graphType) {
            chart.clear();

            let labels = [];
            let dataVals = [];
            dataset.forEach(element => {
                labels.push(element.Name);
                dataVals.push(element.Count);
            });
            chart.data.datasets[0].type = graphType;
            chart.data.labels = labels;
            chart.data.datasets[0].label = graphName;
            chart.data.datasets[0].data = dataVals;
            chart.update();
        };

        var getTop = function(interval, what_top, graph_type) {
            $.ajax({
                type: 'GET',
                url:
                    'src/php/ajax.php?action=getTop&interval=' + interval + '&what_top=' + what_top,
                dataType: 'json',
                success: function(response) {
                    drawGraph(response, 'Топ ' + what_top, graph_type);
                },
            });
        };

        var drowTopServiceGraph = function() {
            $('.statistic-content')
                .find('.top-service-btn')
                .on('click', function() {
                    getTop('year', 'services', 'bar');
                    $('.statistic-content')
                        .find('.btn-group-toggle .btn')
                        .off('click');
                    $('.statistic-content')
                        .find('.btn-group-toggle .btn')
                        .on('click', function() {
                            event.stopPropagation();
                            event.preventDefault();
                            getTop(
                                $(this)
                                    .find('input')
                                    .attr('id'),
                                'services',
                                'bar'
                            );
                        });
                });
        };

        var drowTopNumbersGraph = function() {
            $('.statistic-content')
                .find('.top-number-btn')
                .on('click', function() {
                    getTop('year', 'numbers', 'bar');
                    $('.statistic-content')
                        .find('.btn-group-toggle .btn')
                        .off('click');
                    $('.statistic-content')
                        .find('.btn-group-toggle .btn')
                        .on('click', function() {
                            event.stopPropagation();
                            event.preventDefault();
                            getTop(
                                $(this)
                                    .find('input')
                                    .attr('id'),
                                'numbers',
                                'bar'
                            );
                        });
                });
        };
        var drowProfitGraph = function() {
            $('.statistic-content')
                .find('.profit-btn')
                .on('click', function() {
                    getTop('year', 'profit', 'line');
                    $('.statistic-content')
                        .find('.btn-group-toggle .btn')
                        .off('click');
                    $('.statistic-content')
                        .find('.btn-group-toggle .btn')
                        .on('click', function() {
                            event.stopPropagation();
                            event.preventDefault();
                            getTop(
                                $(this)
                                    .find('input')
                                    .attr('id'),
                                'profit',
                                'line'
                            );
                        });
                });
        };
        drowTopNumbersGraph();
        drowTopServiceGraph();
        drowProfitGraph();
    }
}
module.exports = {
    main: statistic,
};
