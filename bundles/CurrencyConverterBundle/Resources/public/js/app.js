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

function loadErrorMessage(message) {
    let $errorContainer = $('#ajax-error-message');

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