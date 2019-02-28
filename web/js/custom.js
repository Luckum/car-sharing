$(document).ready(function () {
    clock();
});

function clock()
{
    var dayarray = new Array("ВС", "ПН", "ВТ", "СР", "ЧТ", "ПТ", "СБ"),
        montharray = new Array("января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря"),
        ndata = new Date(),
        day = dayarray[ndata.getDay()],
        month = montharray[ndata.getMonth()],
        date = ndata.getDate(),
        year = ndata.getFullYear(),
        hours = ndata.getHours(),
        mins = ndata.getMinutes();
        
    if (date < 10) {
        date = "0" + date;
    }
    if (hours < 10) {
        hours = "0" + hours;
    }
    if (mins < 10) {
        mins = "0" + mins;
    }
    
    $(".system-date").html(date + " " + month + " " + year);
    $(".system-time").html(day + ", " + hours + ":" + mins);
    t = setTimeout('clock()', 1000);
}