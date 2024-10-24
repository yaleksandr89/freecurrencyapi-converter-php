$(document).ready(function () {
    $('form[data-convert-url]').on('submit', function (e) {
        e.preventDefault();

        let formData = $(this).serialize();
        let url = $(this).data('convert-url');
        let conversionResultContainer = $('#conversionResultContainer');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            beforeSend: function () {
                conversionResultContainer.slideUp(300);
            },
            success: function (response) {
                if (response.success) {
                    conversionResultContainer.html(response.html).slideDown(1000);
                } else {
                    loadErrorMessage(response.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                loadErrorMessage(jqXHR.responseJSON);
            }
        });
    });
});