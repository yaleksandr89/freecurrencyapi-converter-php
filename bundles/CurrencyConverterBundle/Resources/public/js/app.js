import '/../../../bundles/currencyconverter/js/jquery.js';
import '/../../../bundles/currencyconverter/js/bootstrap.bundle.js';
import '/../../../bundles/currencyconverter/js/jquery.easing.js';
import '/../../../bundles/currencyconverter/js/sb-admin-2.js';


// 1. Скрипт, который переключает sidebar
$(document).ready(function () {
    switch (true) {
        case window.location.pathname === '/currencies':
            // Добавляем класс active к пункту "dashboard"
            $('#dashboard').closest('li').addClass('active');
            break;
        case window.location.pathname.startsWith('/currencies/list'):
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
});


// 2. Обработка кнопки: "Загрузить / Обновить валюты"
$(document).ready(function () {
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
                'font': $el.css('font')
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

        let currentPage = window.location.pathname.split('/').pop();
        currentPage = isNaN(currentPage) ? 1 : parseInt(currentPage);

        setTimeout(function () {
            $.ajax({
                url: $el.data('url'),
                method: 'POST',
                data: {
                    page: currentPage,
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    let $tableBody = $('#main_table tbody');
                    $tableBody.empty();

                    if (response.success) {
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

                        // Обновляем пагинацию
                        $('div#pagination').html(response.pagination);
                    } else {
                        console.log('Ошибка:', response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //console.error('Error during the request:', textStatus, errorThrown);
                    console.error('Ошибка при загрузке данных для пагинации');
                },
                complete: function () {
                    // Плавная смена текста обратно на оригинальный
                    $el.animate({opacity: 0.5}, 300, function () {
                        $el.text(originalText).animate({opacity: 1}, 300);
                        $el.css('pointer-events', 'auto'); // Включаем указатель
                    });
                }
            });
        }, 250);
    });
});


// 3. Обработка пагинации
$(document).ready(function () {
    $('div#pagination').on('click', '.page-link', function (e) {
        e.preventDefault();
        const url = $(this).data('route');

        let $tableBody = $('#main_table tbody');
        $tableBody.html('<tr><td colspan="8" class="text-center"><div class="spinner-border" role="status"></div></td></tr>');

        setTimeout(function () {
            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
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

                    // Обновляем элементы пагинации
                    $('div#pagination').html(response.pagination);

                    // Обновляем URL в адресной строке
                    window.history.pushState({}, '', url);
                },
                error: function (xhr, textStatus, errorThrown) {
                    //console.error('Ошибка при загрузке данных для пагинации:', textStatus, errorThrown);
                    $tableBody.html('<tr><td colspan="8" class="text-center">Ошибка при загрузке данных. Попробуйте еще раз.</td></tr>');
                }
            });
        }, 250);
    });
});
