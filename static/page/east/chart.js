$(function () {
    $('#main_mask').click(function () {
        $(this).hide();
        $('#show iframe').attr('src', './null.html');
    });

    $('.chart_mask').click(function () {
        var symb = $(this).parent().attr('id').substr(16);
        var url = './?a=sina&m=clear_chart&type=&symbol=' + symb;
        $('#show iframe').attr('src', url);
        $('#show span').text(names[symb]);
        $('#main_mask').show();
    });

    freshChart();
});

function freshChart() {
    for (var symb in names) {
        $('#chart_container_' + symb + ' iframe').attr('src', '');
        $('#chart_container_' + symb + ' span').text(names[symb] + '    ' + symb);
        var url = './?a=sina&m=clear_chart&type=simple&symbol=' + symb;
        setUrl(symb, url);
    }
}

function setUrl(symb, url) {
    $('#chart_container_' + symb + ' iframe').attr('src', url);
}
