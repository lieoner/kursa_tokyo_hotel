import jQuery from 'jquery';
import popper from 'popper.js';
import 'bootstrap/dist/css/bootstrap.min.css';
import '../css/style.css';

(function($) {
    $(document).ready(function() {
        $('.small-grid-image').click(function() {
            $('.small-grid-image.active').removeClass('active');
            $('.big-grid-image.active').removeClass('active');
            $('.small-grid-image[num=' + $(this).attr('num') + ']').addClass('active');
            $('.big-grid-image[num=' + $(this).attr('num') + ']').addClass('active');
        });
    });
})(jQuery);

function setMain() {
    console.log(1);
}
