function statistic() {
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
        // The type of chart we want to create
        type: 'line',

        // The data for our dataset
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [
                {
                    label: 'My First dataset',
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: [0, 10, 5, 2, 20, 30, 45],
                },
            ],
        },

        // Configuration options go here
        options: { responsive: false },
    });
}
module.exports = {
    main: statistic,
};
