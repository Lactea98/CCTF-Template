var clock = new Vue({
    el: '#clock',
    data: {
        time: '',
        date: ''
    }
});


// Reference 
// https://riucc.tistory.com/557
start();
function start(){
    var end_time = $(".countDown").val();
    
    if (end_time == 0){
        clock.time = "00 h 00 m 00 s ";
        clock.date = "0" ;
        return;
    } 
    
    var year = end_time.substring(0,4);
    var month = end_time.substring(5,7);
    var day = end_time.substring(8,10);
    var hour = end_time.substring(11,13);
    var minutes= end_time.substring(14,16);
    var seconds = end_time.substring(17,19);
    var endTime = new Date(year, month, day, hour, minutes, seconds);
    
    var _second = 1000;
    var _minute = _second * 60;
    var _hour = _minute * 60;
    var _day = _hour * 24;
    var timer;
    
    function showRemaining() {
        var now = new Date();
        var distance = endTime - now;
        
        var days = Math.floor(distance / _day - 31);
        var hours = Math.floor((distance % _day) / _hour);
        var minutes = Math.floor((distance % _hour) / _minute);
        var seconds = Math.floor((distance % _minute) / _second);
        
        clock.time = zeroPadding(hours, 2) + ' h ' + zeroPadding(minutes, 2) + ' m ' + zeroPadding(seconds, 2) + ' s ';
        clock.date = days;
        
        if(days == 0 && hours == 0 && minutes == 0 && seconds == 0 || days < 0){
            clock.time = "00 h 00 m 00 s ";
            clock.date = "0" ;
            clearInterval(timer);
            // alert("Timeset");
            return;
        }
    }
    timer = setInterval(showRemaining, 1000);
}


// var week = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
// var timerID = setInterval(updateTime, 1000);
// updateTime();
// function updateTime() {
//     var cd = new Date();
//     clock.time = zeroPadding(cd.getHours(), 2) + ':' + zeroPadding(cd.getMinutes(), 2) + ':' + zeroPadding(cd.getSeconds(), 2);
//     clock.date = zeroPadding(cd.getFullYear(), 4) + '-' + zeroPadding(cd.getMonth()+1, 2) + '-' + zeroPadding(cd.getDate(), 2) + ' ' + week[cd.getDay()];
// };

function zeroPadding(num, digit) {
    var zero = '';
    for(var i = 0; i < digit; i++) {
        zero += '0';
    }
    return (zero + num).slice(-digit);
}