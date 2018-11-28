import $ from 'jquery';

$(document).ready(() => {
        let oldText, newText;
        const editable = $('.editable');
        const height = editable.height();
        const editButton = $('.editButton');

        editButton.on('click', () => replaceHTML());

        $('.btnSave').on('click', () => {
                newText = $('.textBox').val().replace(/"/g, '"');
                editable.html(newText);
                editButton.on('click', () => replaceHTML());
                $('.btnSave, .btnDiscard').css({'opacity': 0});
            }
        );

        $(".btnDiscard").on('click', () => {
                editable.html(oldText);
                editButton.on('click', () => replaceHTML());
                $('.btnSave, .btnDiscard').css({'opacity': 0});
            }
        );

        const replaceHTML = () => {
            oldText = editable.html().replace(/"/g, '"');
            editable.html("").html(`
                        <form class='editBox'>
                            <textarea style=${'height:' + height + 'px'} class='textBox'>${oldText}</textarea>
                        </form>`);
            $('.btnSave, .btnDiscard').css({'opacity': 1});
            editButton.off('click');
        }
    }
);