import '/../../../bundles/currencyconverter/js/jquery.js';
import '/../../../bundles/currencyconverter/js/bootstrap.bundle.js';
import '/../../../bundles/currencyconverter/js/jquery.easing.js';
import '/../../../bundles/currencyconverter/js/sb-admin-2.js';

// 1. Скрипт, который переключает sidebar
import '/../../../bundles/currencyconverter/js/_sidebar.js';

// 2. Обработка кнопки: "Загрузить/Обновить валюты"
import '/../../../bundles/currencyconverter/js/_updateCurrencies.js';

// 3. Обработка пагинации
import '/../../../bundles/currencyconverter/js/_pagination.js';

// 4. Обработка раздела "Исторические курсы"
import '/../../../bundles/currencyconverter/js/_historicalRates.js';

// 5. Обработка раздела "Конвертер валют"
import '/../../../bundles/currencyconverter/js/_currencyConversion.js';


function loadErrorMessage(response) {
    let $errorContainer = $('#ajax-error-message');
    let message = '';

    // Если есть поле message, то используем его
    if (typeof response.message === 'string') {
        message = response.message;
    }
    // Если есть массив ошибок, объединяем их в одну строку
    else if (Array.isArray(response.errors)) {
        message = response.errors.join('\n');
    }
    // Если формат ошибки неожиданен
    else {
        console.error('Unexpected error format:', response);
        message = 'An unexpected error occurred';
    }

    // Отображаем сообщение об ошибке
    $errorContainer.empty();
    $errorContainer.html(`
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `);
}
window.loadErrorMessage = loadErrorMessage;