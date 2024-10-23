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
                    conversionResultContainer.html('<div class="alert alert-success">Converted Amount: ' + response.convertedAmount + '</div>').slideDown(1000);
                } else {
                    if (response.errors) {
                        response.errors.forEach(function (error) {
                            console.error('success_error_1:');
                            console.error(error);
                        });
                    } else {
                        console.error('success_error_2:');
                        console.error(response);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                let response = jqXHR.responseJSON;

                if (response && response.success === false) {
                    loadErrorMessage(response);
                } else {
                    console.error('Error: ' + jqXHR.responseText);
                }
            }
        });
    });
});
