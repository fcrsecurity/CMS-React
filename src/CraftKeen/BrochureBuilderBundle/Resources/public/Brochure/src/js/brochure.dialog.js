$(function () {
    $('[data-toggle="modal"]').on('click', function () {
        var link = $(this);
        var form = $(link.data('target') + ' form');
        form.attr('action', $(this).data('path'));
    });
});
