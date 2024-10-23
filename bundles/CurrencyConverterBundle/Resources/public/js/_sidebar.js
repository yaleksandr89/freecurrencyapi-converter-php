$(document).ready(function () {
    function activateMenuItem(selector, link) {
        let menuItem = $(selector);
        menuItem.removeClass('collapsed');
        $('#collapseCurrencies').addClass('show');
        menuItem.closest('li').addClass('active');
        $(`a[href="${link}"]`).addClass('active');
    }

    switch (true) {
        case window.location.pathname === '/currencies':
            $('#dashboard').closest('li').addClass('active');
            break;
        case window.location.pathname.startsWith('/currencies/list'):
            activateMenuItem('#currencies', '/currencies/list');
            break;
        case window.location.pathname.startsWith('/currencies/historical'):
            activateMenuItem('#currencies', '/currencies/historical');
            break;
        case window.location.pathname.startsWith('/currencies/convert'):
            activateMenuItem('#currencies', '/currencies/convert');
            break;
        default:
            break;
    }
});
