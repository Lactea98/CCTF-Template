// Timeseries 예
// https://embed.plnkr.co/JOI1fpgWIS0lvTeLUxUp/


function getRandomColor() {
  var letters = '0123456789ABCDEF';
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}


////////////////////////////////////
// socreboard 유저 데이터 가져오기
var userDataSets = [];
var start_time;
var end_time;

$.ajax({
    url: "./scoreboard.php",
    async: false,
    type: "GET",
    data: {
        getData: 1
    },
    dataType: "json"
}).done(function(json){
    var result = json["userHistory"];
    start_time = json["time"]["begin_timer"];
    end_time = json["time"]["end_timer"];
    
    
    for(var i=0; i<result.length; i++){
        var history = result[i]["history"];
        history = history.split(",")
        userDataSets[i] = {};
        userDataSets[i]["label"] = result[i]["nickname"];
        userDataSets[i]["data"] = [];
        
        userDataSets[i]["data"][0] = {x: start_time, y:0};
        for (var j=0; j<history.length; j++){
            if(history[j].length == 1){
                continue;
            }
            var tmp = history[j].split(" => ");
            userDataSets[i]["data"][j+1] = {x: $.trim(tmp[0]), y: $.trim(tmp[1])};
        }
        userDataSets[i]["lineTension"] = 0;
        userDataSets[i]["fill"] = "false";
        userDataSets[i]["borderColor"] = getRandomColor();
        userDataSets[i]["scaleShowGridLines"] = "true"
    }
})
console.log(userDataSets[0]["data"])
console.log(start_time, end_time);
/////////////////////////////////////




//////////////////////
// 그래프 render
var myLineChart = new Chart($("#socreboardChart"), {
    type:    'line',
        data:    {
            labels: [start_time, "2020-01-14 00:00:00", end_time],
            datasets: userDataSets
        },
        options: {
            scaleGridLineColor : "rgba(0,0,0,0.05)",
            scaleShowGridLines : true,
            responsive: true,
            // title:      {
            //     display: true,
            //     text:    "SocreBoard"
            // },
            scales:     {
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: 'day',
                        unitStepSize: 1,
                        min: start_time,
                        max: end_time,
                        displayFormats: {
                            minute: 'h:mm a'
                        }
                    },
                    // distribution : "series",
                    scaleLabel: {
                        display:     true,
                        labelString: 'Date'
                    }
                }],
                yAxes: [{
                    scaleLabel: {
                        display:     true,
                        labelString: 'Points'
                    }
                }]
            }
        }
});