import $ from 'jquery';

const toggle = $('.navbar__arrow');
const menu = $('.navbar__menu');
let state = false;

$(window).resize(() => {
    if ($(window).width() >= 480) {
        menu.css({opacity: 1});
    }
});

if ($(window).width() < 480) {
    $('.login__list').css({position: 'relative'});

    $('#menu-expand').hover(() => {
        $('.navbar__menu').css({minHeight: 235});
    }, () => {
        $('.navbar__menu').css({minHeight: 160});
    })
}

toggle.on('click', () => {
    if (state) {
        menu.animate({
            top: '-160px',
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
            top: '60px',
            opacity: 1
        }, 200, () => state = true);

        toggle.css({
            transform: 'rotate(180deg)',
            transition: '0.3s'
        })
    }
});