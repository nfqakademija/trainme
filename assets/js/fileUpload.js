import $ from 'jquery';

const file = $('#form_imageFile_file');
const saveButton = $('.upload-submit');
const fileName = $('.trainerInfo__filename');
const cancelButton = $('.upload-cancel');

file.on('change', () => {
    if (file[0].files[0]) {
        saveButton.css({display: 'inline-block'});
        cancelButton.css({display: 'inline-block'});
        fileName.text(file[0].files[0].name).css({display: 'inline-block'});
    }
});

cancelButton.on('click', () => {
    saveButton.css({display: 'none'});
    cancelButton.css({display: 'none'});
    fileName.text('').css({display: 'none'});
});

saveButton.on('click', () => {
    saveButton.text('Saving...');
    cancelButton.css({display: 'none'});
});