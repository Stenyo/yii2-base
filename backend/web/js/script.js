$(function () {
    $(document).on('click', '.clickable', function (e) {
        var parent = $(e.target).closest('a');

        if (parent.size() == 0) {
            var url = $(this).closest('.clickable').attr('url');
            window.location.href = url;
        }
    });
});
