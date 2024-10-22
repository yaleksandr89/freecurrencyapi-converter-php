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
                beforeSend: function () {
                    $tableBody.html('<tr><td colspan="8" class="text-center"><div class="spinner-border" role="status"></div></td></tr>'); // Показать спиннер
                },
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
                    loadErrorMessage('Ошибка при загрузке данных. Попробуйте еще раз.');
                }
            });
        }, 250);
    });
});
