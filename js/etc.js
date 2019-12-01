
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