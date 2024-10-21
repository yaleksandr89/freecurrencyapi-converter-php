import '/../../../bundles/currencyconverter/js/jquery.js';
import '/../../../bundles/currencyconverter/js/bootstrap.bundle.js';
import '/../../../bundles/currencyconverter/js/jquery.easing.js';
import '/../../../bundles/currencyconverter/js/sb-admin-2.js';

$(document).ready(function () {
    let currentUrl = window.location.pathname; // текущий URL

    // Проверяем URL и добавляем класс active к соответствующему пункту меню
    switch (true) {
        case currentUrl === '/currencies':
            // Добавляем класс active к пункту "dashboard"
            $('#dashboard').closest('li').addClass('active');
            break;
        case currentUrl.startsWith('/currencies/list'):
            let currencies = $('#currencies');
            // Убираем класс collapsed и добавляем show для раскрытия меню
            currencies.removeClass('collapsed');
            $('#collapseCurrencies').addClass('show');
            // Подсвечиваем текущий выбранный пункт в разделе "currencies"
            currencies.closest('li').addClass('active');
            // Подсвечиваем активный подпункт
            $('a[href="/currencies/list"]').addClass('active');
            break;
        default:
            break;
    }

    // Обработка нажатия на кнопку обновления валют
    $('a[data-action="update-currencies"]').on('click', function (e) {
        e.preventDefault();
        let $el = $(this);
        let originalText = $el.data('default-text');
        let loadingText = $el.data('loading-text');

        // Создаем временный элемент для измерения ширины текста
        let $tempEl = $('<span>')
            .text(originalText)
            .css({
                'visibility': 'hidden',
                'position': 'absolute',
                'white-space': 'nowrap',
                'font': $el.css('font') // копируем стиль шрифта для точного вычисления
            })
            .appendTo('body');

        // Получаем ширину самого длинного текста
        let maxWidth = Math.max(
            $tempEl.width(),
            $('<span>').text(loadingText).css({
                'visibility': 'hidden',
                'position': 'absolute',
                'white-space': 'nowrap',
                'font': $el.css('font')
            }).appendTo('body').width()
        );

        // Устанавливаем минимальную ширину элемента ссылки
        $el.css('min-width', maxWidth + 'px');
        $tempEl.remove(); // Удаляем временный элемент

        // Блокируем указатель
        $el.css('pointer-events', 'none');

        // Плавная смена текста на загрузочный
        $el.animate({opacity: 0.5}, 300, function () {
            $el.text(loadingText).animate({opacity: 1}, 300);
        });

        // Выполняем AJAX запрос
        $.ajax({
            url: $el.data('url'),
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function (response) {
                let editText = $('[data-trans-edit-btn]').data('trans-edit-btn');
                let deleteText = $('[data-trans-delete-btn]').data('trans-delete-btn');

                // Очищаем старые строки таблицы
                let $tableBody = $('#main_table tbody');
                $tableBody.empty();

                if (response.success) {
                    //console.log('Валюты успешно обновлены!', response);

                    // Очищаем старые строки таблицы
                    let $tableBody = $('#main_table tbody');
                    $tableBody.empty();

                    response.currencies.forEach(function (currency) {
                        let row = `
                            <tr ${currency.code === 'USD' ? 'class="table-secondary"' : ''}>
                                <td class="align-middle">${currency.id}</td>
                                <td class="align-middle"><code>${currency.code}</code></td>
                                <td class="align-middle">${currency.title}</td>
                                <td class="align-middle">${currency.namePlural}</td>
                                <td class="align-middle"><code>${currency.symbol}</code></td>
                                <td class="align-middle">${currency.rate}</td>
                                <td class="align-middle">${currency.createdAt}</td>
                                <td class="align-middle">${currency.updatedAt}</td>
                            </tr>`;
                        $tableBody.append(row);
                    });
                } else {
                    console.log('Ошибка:', response.message);
                }
            },
            error: function () {
                console.log('Произошла ошибка при обновлении валют. Попробуйте еще раз.');
            },
            complete: function () {
                // Плавная смена текста обратно на оригинальный
                $el.animate({opacity: 0.5}, 300, function () {
                    $el.text(originalText).animate({opacity: 1}, 300);
                    $el.css('pointer-events', 'auto'); // Включаем указатель
                });
            }
        });
    });
});
