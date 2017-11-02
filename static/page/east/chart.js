$(function () {
    $('#main_mask').click(function () {
        $(this).hide();
        $('#show iframe').attr('src', '/null.html');
    });

    $('.chart_mask').click(function () {
        var symb = $(this).parent().attr('id').substr(16);
        $('#show iframe').attr('src', iframe_url_common + symb.substr(2));
        $('#show span').text(names[symb]);
        $('#main_mask').show();
    });

    freshChart();
    setInterval('freshChart();', refresh_time * 1000);
});

function freshChart() {
    for (var symb in names) {
        symb = '' + symb;
        $('#chart_container_' + symb + ' iframe').attr('src', '');
        $('#chart_container_' + symb + ' span').text(names[symb]);
        var url = iframe_url_common + symb.substr(2);
        setTimeout("setUrl('" + symb + "', '" + url + "');", 50);
    }
}

function setUrl(symb, url) {
    $('#chart_container_' + symb + ' iframe').attr('src', url);
}
