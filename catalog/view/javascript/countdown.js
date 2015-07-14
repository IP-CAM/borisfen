function counter(container, end_date){
    var global_date = end_date;
    var days = end_date / 3600 / 24; days = days - (days%1);
    var hours;
    var minutes;
    var seconds;
    if(days >= 1) end_date = end_date - (3600 * 24 * days);
    hours = end_date / 3600; hours = hours - (hours%1);
    if(hours >= 1)  end_date = end_date - (3600 * hours);
    minutes = end_date / 60; minutes = minutes - (minutes%1);
    if(minutes >= 1) end_date = end_date - (60 * minutes);
    seconds = end_date;
    var html = LANGS.default.counter_text_prepend;
    if(days > 0) html += "<span class='days'>" + days + "</span>";
    if(hours.toString().length == 1) hours = "0" + hours.toString();
    if(minutes.toString().length == 1) minutes = "0" + minutes.toString();
    if(seconds.toString().length == 1) seconds = "0" + seconds.toString();
    html += "<span class='hours'>" + hours + "</span><span class='minutes'>" + minutes + "</span><span class='seconds'>" + seconds + "</span>";
    container.html(html);
    setTimeout(function(){
        if(global_date > 0){
            global_date--;
            counter(container, global_date);
        } else {
            container.html(LANGS.default.counter_text_timeup);
        }
    }, 1000);
}