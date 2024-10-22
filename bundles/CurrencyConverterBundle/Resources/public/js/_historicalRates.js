$(document).ready(function () {
    $('form[data-historical-url]').on('submit', function (e) {
        e.preventDefault();

        let formData = $(this).serialize();
        let url = $(this).data('historical-url');
        let historicalRatesTableContainer = $('#historicalRatesTableContainer');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            beforeSend: function () {
                historicalRatesTableContainer.slideUp(300);
            },
            success: function (response) {
                if (response.success) {
                    historicalRatesTableContainer.html(response.html).slideDown(1000);
                } else {
                    loadErrorMessage(response.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                loadErrorMessage('Произошла ошибка при запросе данных. Попробуйте снова.');
            }
        });
    });
});
