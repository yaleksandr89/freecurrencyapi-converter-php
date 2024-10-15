import '/../../../bundles/currencyconverter/js/jquery.js';
import '/../../../bundles/currencyconverter/js/bootstrap.bundle.js';
import '/../../../bundles/currencyconverter/js/jquery.easing.js';
import '/../../../bundles/currencyconverter/js/sb-admin-2.js';

$(document).ready(function () {
    // Получаем текущий URL
    let currentUrl = window.location.pathname;

    // Проверяем URL и добавляем класс active к соответствующему пункту меню
    if (currentUrl === '/currencies') {
        // Добавляем класс active к пункту "dashboard"
        $('#dashboard').closest('li').addClass('active');
    } else if (currentUrl === '/currencies/list' || currentUrl === '/currencies/add') {
        // Убираем класс collapsed и добавляем show для раскрытия меню
        $('#currencies').removeClass('collapsed');
        $('#collapseCurrencies').addClass('show');

        // Подсвечиваем текущий выбранный пункт в разделе "currencies"
        $('#currencies').closest('li').addClass('active');

        // Дополнительно можно выделить активный подпункт
        if (currentUrl === '/currencies/list') {
            $('a[href="/currencies/list"]').addClass('active');
        } else if (currentUrl === '/currencies/add') {
            $('a[href="/currencies/add"]').addClass('active');
        }
    }
});
