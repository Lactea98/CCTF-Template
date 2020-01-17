/////////////////////////////////////////////////
// 카테고리 분류별로 보기
$(".custom-select").change(function(){
    var category = $(".challenge-category-option option:selected").val();
    var option = $(".challenge-view-option option:selected").val();
    
    showProb(category, option);
})

function showProb(category, option){
    $(".prob").hide();
    $(".challenge-category").hide();
    
    var getProb = $(".challenge-search:contains('"+category+"')");
    var getProbTitle = $("")
    $(getProb).parent().parent().parent().show();
    $(getProb).parent().parent().parent().parent().find("h1").show();
}
////////////////////////////////////////////////



/////////////////////////////////////
// 공지글 주기적으로 가져오기
var time = 60; // sec
getAnnouncement = setInterval(function(){
    $.ajax({
        url: "./config.php",
        async: false,
        type: "POST",
        data:{
            option: "getAnnouncement"
        },
        dataType: "json"
    }).done(function(result){
        if(result['result'] == "success"){
            // 현재 Announcement에 있는 공지글의 idx 값 가져오기
            var idx = $(".announcement-date").map((e,i) => $(i).attr("id").substring(9,)).get();
            
            // 새로운 공지가 있는지 개수로 확인
            if(idx.length != result['message'].length){
                for(var i=0; i<result['message'].length; i++){
                    if(idx[0] == result['message'][i]['idx']){
                        break;
                    }
                    // 새로운 공지 등록
                    else{
                        var html = `<div class="announcement-date" id="announce_`+ result['message'][i]['idx'] +`">[` + result['message'][i]['date'] + `]&nbsp;</div>`;
                        html += `<div class="announcement-content">`;
                        
                        if(result['message'][i]['category'] == "admin"){
                            html += `<font color='#d46313'>[Notice]</font>: ` + htmlspecialchars(result['message'][i]['message']);
                        }
                        else if(result['message'][i]['category'] == "captured"){
                            var message = result['message'][i]['message'].split(" || ");
                            html += `<font color='red'>` + htmlspecialchars(message[0]) + `</font> captured <font color='#d46313'>` + htmlspecialchars(message[1]) + `</font>`;
                        }
                        else if(result['message'][i]['category'] == "challenge"){
                            html += `<font color='#d46313'>[Updated]</font>: ` + htmlspecialchars(result['message'][i]['message']);
                        }
                        html += `</div><br>`;
                        $(".realtime-announcment-list").prepend(html);
                    }
                }
                
                if($(".realtime-announcment-list").css("display") == "none"){
                    var exclamation = `<font color="red" class="exclamation">&nbsp;&nbsp;<i class="fas fa-exclamation"></i></font>`;
                    $(".realtime-announcment").append(exclamation);
                }
            }
        }
        
    })
}, time * 1000)

// setTimeout(function(){
//     clearInterval(getAnnouncement);
// },1500);


// xss 방지
function htmlspecialchars(str) {
  return str.replace('&', '&amp;').replace('"', '&quot;').replace("'", '&#039;').replace('<', '&lt;').replace('>', '&gt;');
}





//////////////////////
// 마우스 모양 바꾸기
$(document).ready(function(){
    $(".realtime-content").css('cursor', 'pointer');
})
//////////////////////


///////////////////////
// announcement 테이블 
$(".realtime-announcment").click(function(){
    if($(".realtime-announcment-list").css("display") == "none"){
        $(".realtime-announcment-list").slideDown();
        $(".realtime-announcment").find(".exclamation").remove();
    }
    else{
        $(".realtime-announcment-list").slideUp();
    }
})
//////////////////////


////////////////////////
// chatting 테이블
$(".realtime-chatting").click(function(){
    if($(".realtime-chatting-list").css("display") == "none"){
        $(".realtime-chatting-list").slideDown();
    }
    else{
        $(".realtime-chatting-list").slideUp();
    }
})
///////////////////////


////////////////////////
// Countdown 테이블
$(".realtime-countdown").click(function(){
    if($(".realtime-countdown-list").css("display") == "none"){
        $(".realtime-countdown-list").slideDown();
    }
    else{
        $(".realtime-countdown-list").slideUp();
    }
})
///////////////////////