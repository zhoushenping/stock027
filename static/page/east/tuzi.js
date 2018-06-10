$(function () {
    var lastTop = 0;

    $('table.tablesorter').tablesorter({
        widgets: ['zebra', 'columns'],
        usNumberFormat: true,
        sortReset: true,
        sortRestart: true
    });

    $('.viewChart').click(function () {
        $('.chartContainer').remove();


        lastTop = $(window).scrollTop();
        x = $(this).parent().parent().offset();
        $(window).scrollTop(x.top);

        $(this).parent().parent().after("<tr class='chartContainer'><td colspan='9'></td></tr>");
        $('.chartContainer td').append('<iframe src="./null.html" width="570" height="500" marginheight="0" marginwidth="0" frameborder="0" scrolling="no"></iframe>');
        $('.chartContainer iframe').attr('src', './?a=sina&m=clear_chart&type=simple&symbol=' + $(this).parent().parent().find('td:eq(0) a').text());
    });

    $('.chartContainer td').live('click', function () {
        $(this).parent().remove();
        $(window).scrollTop(lastTop);
    });
});

