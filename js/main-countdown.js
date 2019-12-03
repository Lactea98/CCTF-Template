const _second = 1000,
      _minute = _second * 60,
      _hour = _minute * 60,
      _day = _hour * 24;

var end_time = $(".countDown").val();
var year = end_time.substring(0,4);
var month = end_time.substring(5,7);
var day = end_time.substring(8,10);
var hour = end_time.substring(11,13);
var minutes= end_time.substring(14,16);
var seconds = end_time.substring(17,19);
var endTime = new Date(year, month, day, hour, minutes, seconds);
console.log(endTime.getTime());
// let countDown = new Date('Sep 30, 2020 00:00:00').getTime(),
let countDown = endTime.getTime(),
    x = setInterval(function() {

      let now = new Date().getTime(),
          distance = countDown - now;

      document.getElementById('days').innerText = zeroPadding(Math.floor(distance / (_day) - 31),2),
      document.getElementById('hours').innerText = zeroPadding(Math.floor((distance % (_day)) / (_hour)),2),
      document.getElementById('minutes').innerText = zeroPadding(Math.floor((distance % (_hour)) / (_minute)),2),
      document.getElementById('seconds').innerText = zeroPadding(Math.floor((distance % (_minute)) / _second),2);
      
      if (Math.floor(distance / (_day) - 31) == 0 && Math.floor((distance % (_day)) / (_hour)) == 0 && Math.floor((distance % (_hour)) / (_minute)) == 0 && Math.floor((distance % (_minute)) / _second) == 0) {
        clearInterval(x);
        location.reload();
      }
      else if (Math.floor(distance / (_day) - 31) < 0){
        clearInterval(x);
        document.getElementById('days').innerText = "00";
        document.getElementById('hours').innerText = "00";
        document.getElementById('minutes').innerText = "00";
        document.getElementById('seconds').innerText = "00";
        $("#container > div > div > div > center > h1").append("<h3>Open soon. Server is setting...</h3>");
        setTimeout(function(){
          location.reload();
        },2000) // 12초??
      }

    }, _second)

function zeroPadding(num, digit) {
    var zero = '';
    for(var i = 0; i < digit; i++) {
        zero += '0';
    }
    return (zero + num).slice(-digit);
}