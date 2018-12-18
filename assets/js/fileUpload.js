import $ from 'jquery';

const edit = $('.edit-image');
const form = $('.trainerInfo__imageEdit');

edit.on('click', () => {
    form.css({transform: 'scaleY(1)'});
    edit.css({display: 'none'});
    $('.trainerInfo__imageEdit').css({margin: '10px 0'});
});
