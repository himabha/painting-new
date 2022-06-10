jQuery(document).ready(function() {
    jQuery(".owl-carousel").owlCarousel({
        items: 1,
        autoplay: true
    });

    jQuery('.carousel').slick({
        dots: true,
        autoplay: true,
        autoplaySpeed: 7000,
        prevArrow: '<button class="previous-button is-control">' +
                   '  <span class="fas fa-angle-left" aria-hidden="true"></span>' +
                   '  <span class="sr-only">Previous slide</span>' +
                   '</button>',
        nextArrow: '<button class="next-button is-control">' +
                   '  <span class="fas fa-angle-right" aria-hidden="true"></span>' +
                   '  <span class="sr-only">Next slide</span>' +
                   '</button>'
    });

    // var Vue = require('vue');
    // Vue.use(VueAgile);
    // app = new Vue({
    //     el: '#app',
    //     components: {
    //         agile: VueAgile } 
    // });
});
