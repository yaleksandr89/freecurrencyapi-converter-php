$(document).ready(function () {
    switch (true) {
        case window.location.pathname === '/currencies':
            $('#dashboard').closest('li').addClass('active');
            break;
        case window.location.pathname.startsWith('/currencies/list'):
            let currencies = $('#currencies');
            currencies.removeClass('collapsed');
            $('#collapseCurrencies').addClass('show');
            currencies.closest('li').addClass('active');
            $('a[href="/currencies/list"]').addClass('active');
            break;
        case window.location.pathname.startsWith('/currencies/historical'):
            let currenciesHist = $('#currencies');
            currenciesHist.removeClass('collapsed');
            $('#collapseCurrencies').addClass('show');
            currenciesHist.closest('li').addClass('active');
            $('a[href="/currencies/historical"]').addClass('active');
            break;
        default:
            break;
    }
});