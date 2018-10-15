$(document).on('change', '#news-sectionlist', function (e) {
    let val = $(this).val();
    let _selectContainer = $('#news-taglist');
    let selectedVals = _selectContainer.val();
    let selected = '';
    _selectContainer.html('');

    $.post('/tag/get-list', {'section': val}, function (result) {
        if (result) {
            $.each(result, function (index, value) {
                $.each(selectedVals, function (i, v) {
                    if (v == value.id) {
                        selected = 'selected';
                        return false;
                    } else {
                        selected = '';
                    }
                });

                _selectContainer.append('<option ' + selected + ' value="' + value.id + '">' + value.title + '</option>');
            });
            $('#news-taglist').trigger('change.select2');
        }
    });
});

$(document).on('change', '.image-input-container .image-select', function () {
    let val = $(this).val();
    let _parent = $(this).parents('.image-input-container');
    $('.selected-image', _parent).attr('src', val);
    $('input[type=hidden]', _parent).val(val);
    $('.remove-image', _parent).show();
});

$(document).on('click', '.image-input-container a.remove-image', function () {
    let _parent = $(this).parents('.image-input-container');
    $('.selected-image', _parent).attr('src', 'no-image.jpg');
    $('input[type=hidden]', _parent).val('');
    $(this).hide();
    return false;
});
