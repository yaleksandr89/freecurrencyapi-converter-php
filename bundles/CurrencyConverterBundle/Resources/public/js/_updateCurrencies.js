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
                beforeSend: function () {
                    $el.css('pointer-events', 'none'); // Блокируем указатель перед отправкой
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
                    loadErrorMessage('Ошибка при загрузке данных для валют.');
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

