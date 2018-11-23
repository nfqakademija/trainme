import $ from 'jquery';

const toggle = $('.navbar__arrow');
const menu = $('.navbar__menu');
let state = false;

toggle.on('click', () => {
    if (state) {
        menu.animate({
            opacity: 0
        }, 200, () => {
            state = false;
        });

        toggle.css({
            transform: 'rotate(0deg)',
            transition: '0.3s'
        })
    } else {
        menu.animate({
            opacity: 1
        }, 200, () => state = true);

        toggle.css({
            transform: 'rotate(180deg)',
            transition: '0.3s'
        })
    }
});