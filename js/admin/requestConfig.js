// 체크 박스 클릭시 ajax 요청을 보내는 이벤트
$(".custom-control-input").click(function(){
    var option = $(this).val();
    var csrf = $(".csrf_token").val();
    var result = $(this).parent().parent().find(".config-request-result");
    var pageCheck = $(location).attr('search').indexOf("configuration");
    
    if(pageCheck == -1){
        return;
    }
    
    $.ajax({
        url: "./config.php",
        async: false,
        type: "POST",
        data: {
            csrf_token: csrf,
            option: option
        },
        dataType: "json"
    }).done(function(data){
        if(data['result'] == "success"){
            result.html("<font color='green'><strong>"+data['message']+"</strong></font>");
            setTimeout(function(){
                result.html("");
            },2000);
        }
        else if(data['result'] == "error"){
            result.html("<font color='red'><strong>"+data['message']+"</strong></font>"); 
            // setTimeout(function(){
            //     result.html("");
            // },2000);
        }
    });
})

// 시작시간, 종료시간에 대한 이벤트
$("#config-timer").click(function(){
    var begin_datetime = $("#begin_datetime").val();
    var end_datetime = $("#end_datetime").val();
    var csrf = $(".csrf_token").val();
    var result = $(this).parent().parent().find(".config-request-result");
    
    $.ajax({
        url: "./config.php",
        async: false,
        type: "POST",
        data: {
            csrf_token: csrf,
            option: "timer",
            begin_datetime: begin_datetime,
            end_datetime: end_datetime
        },
        dataType: "json"
    }).done(function(data){
        if(data['result'] == "success"){
            result.html("<font color='green'><strong>"+data['message']+"</strong></font>");
            setTimeout(function(){
                result.html("");
            },2000);
        }
        else if(data['result'] == "error"){
            result.html("<font color='red'><strong>"+data['message']+"</strong></font>"); 
            // setTimeout(function(){
            //     result.html("");
            // },2000);
        }
    })
})

// 공지글 작성 이벤트
$("#config-announcement").click(function(){
    var userValue = $(".form-control").val();
    var csrf = $(".csrf_token").val();
    
    $.ajax({
        url: "./config.php",
        async: false,
        type: "POST",
        data: {
            csrf_token: csrf,
            option: "announcement-add",
            value: userValue
        },
        dataType: "json",
        
        beforeSend: function(result){
            if(userValue.length == 0){
                result.abort();
            }
        }
    }).done(function(data){
        if(data['result'] == "success"){
            var html_code = '<div class="config-announcement-result">\
                                <div class="config-announcement-date">\
                                    '+data['value']+'\
                                </div>\
                                <div class="config-announcement-content">\
                                    '+userValue+'\
                                </div>\
                                <div class="config-announcement-result-btn">\
                                    Delete\
                                </div>\
                            </div>\
                            <div class="config-hr"></div>';
            $(".config-announcement").before(html_code);
            $(".config-request-result").html("<font color='green'><strong>"+data['message']+"</strong></font>");
            $(".form-control").val("");
            setTimeout(function(){
                $(".config-request-result").html("");
            },2000);
        }
        else if(data['result'] == "error"){
            $(".config-request-result").html("<font color='red'><strong>"+data['message']+"</strong></font>");
        }
    })
})

//공지글 삭제 이벤트
$(".config-announcement-result-btn").click(function(){
    var idx = $(this).parent().attr("id");
    var csrf = $(".csrf_token").val();
    var result_content = $(this).parent().find(".config-announcement-content");
    var delete_announce = $(this).parent().parent();
    
    $.ajax({
        url: "./config.php",
        type: "POST",
        data: {
            value: idx,
            csrf_token: csrf,
            option: "announcement-delete"
        },
        dataType: "json"
    }).done(function(data){
        if(data['result'] == "success"){
            var html_code = "<font color='green'><strong>"+data['message']+"</strong></font>";
            result_content.html(html_code);
            
            setTimeout(function(){
                delete_announce.remove();
            },1000);
        }
    })
})

// 문제 수정 후 제출 할때의 이벤트
function requestChallenge(idx, title, content, flag, points, bonus, decrease, category, isVisble, level, $result){
    var csrf = $(".csrf_token").val();
    
    $.ajax({
        url: "./config.php",
        type: "POST",
        data:{
            option: "challenge",
            csrf_token: csrf,
            idx: idx,
            title: title,
            content: content,
            flag: flag,
            points: points,
            bonus: bonus,
            decrease: decrease,
            category: category,
            visible: isVisble,
            level: level
        },
        dataType: "json"
    }).done(function(data){
        if(data['result'] == "success"){
            $result.html("<font color='green'><strong>"+data['message']+"</strong></font>");
            setTimeout(function(){
                $result.html("");
            },2000);
        }
        else if(data['result'] == "error"){
            $result.html("<font color='red'><strong>"+data['message']+"</strong></font>"); 
            // setTimeout(function(){
            //     result.html("");
            // },2000);
        }
    })
}

function requestUserInfo(userid, points, pw1, pw2, visible, auth, $result){
    var csrf = $(".csrf_token").val();
    
    $.ajax({
        url: "./config.php",
        type: "POST",
        data:{
            csrf_token: csrf,
            option: "user",
            userid: userid,
            points: points,
            pw1: pw1,
            pw2: pw2,
            visible: visible,
            auth: auth
        },
        datatype: "json"
    }).done(function(data){
        if(data['result'] == "success"){
            $result.html("<font color='green'><strong>"+data['message']+"</strong></font>");
            setTimeout(function(){
                $result.html("");
            },2000);
        }
        else if(data['result'] == "error"){
            $result.html("<font color='red'><strong>"+data['message']+"</strong></font>"); 
            // setTimeout(function(){
            //     result.html("");
            // },2000);
        }
    })
}


// CCTF 리셋 버튼 눌렸을 때 이벤트
$(".config-reset").click(function(){
    var csrf = $(".csrf_token").val();
    var $result = $(this).parent().find(".config-request-result");
    
    $.ajax({
        url: "./config.php",
        type: "POST",
        data:{
            csrf_token: csrf,
            option:"reset",
        },
        dataType:"json"
    }).done(function(data){
        if(data['result'] == "success"){
            $result.html("<font color='green'><strong>"+data['message']+"</strong></font>");
            setTimeout(function(){
                $result.html("");
            },2000);
        }
        else if(data['result'] == "error"){
            $result.html("<font color='red'><strong>"+data['message']+"</strong></font>"); 
            // setTimeout(function(){
            //     result.html("");
            // },2000);
        }
    })
})


//////////////////////////////////////
//  카테고리 관련
$(".btn-outline-success").click(function(){
    if($(location).attr('search').indexOf("category") == -1){
        return;
    }
    
    var userValue = $(".form-control").val();
    var csrf = $(".csrf_token").val();
    
    $.ajax({
        url: "./config.php",
        async: false,
        type: "POST",
        data: {
            csrf_token: csrf,
            option: "category-add",
            value: userValue
        },
        dataType: "json",
        
        beforeSend: function(result){
            if(userValue.length == 0){
                result.abort();
            }
        }
    }).done(function(data){
        if(data['result'] == "success"){
            var html_code = '<div class="config-announcement-result">\
                                <div class="config-announcement-content">\
                                    '+userValue+'\
                                </div>\
                                <div class="config-category-result-btn">\
                                    Delete\
                                </div>\
                            </div>\
                            <div class="config-hr"></div>';
            $(".config-announcement").before(html_code);
            $(".config-request-result").html("<font color='green'><strong>"+data['message']+"</strong></font>");
            $(".form-control").val("");
            setTimeout(function(){
                $(".config-request-result").html("");
                location.reload();
            },2000);
        }
        else if(data['result'] == "error"){
            $(".config-request-result").html("<font color='red'><strong>"+data['message']+"</strong></font>");
        }
    })
})

$(".config-category-result-btn").on("click", function(){
    var csrf = $(".csrf_token").val();
    var result_content = $(this).parent().find(".config-announcement-content");
    var delete_announce = $(this).parent().parent();
    
    $.ajax({
        url: "./config.php",
        type: "POST",
        data: {
            csrf_token: csrf,
            option: "category-delete",
            category_name: result_content[0].innerText
        },
        dataType: "json"
    }).done(function(data){
        if(data['result'] == "success"){
            var html_code = "<font color='green'><strong>"+data['message']+"</strong></font>";
            result_content.html(html_code);
            
            setTimeout(function(){
                delete_announce.remove();
            },1000);
        }
    })
})

function createNewChallenge(title, contents, flag, points, bonus, decrease, category, level, $result){
    var csrf = $(".csrf_token").val();
    var idx = $(".config-box").length; 
    
    $.ajax({
        url: "./config.php",
        type: "POST",
        data: {
            csrf_token: csrf,
            option: "challenge-create",
            title: title,
            contents: contents,
            flag: flag,
            points: points,
            bonus: bonus,
            decrease: decrease,
            category: category,
            level: level
        },
        dataType: "json"
    }).done(function(result){
        if(result['result'] == "success"){
            var html_code = "<font color='green'><strong>"+result['message']+"</strong></font>";
            $result.html(html_code);
            
            setTimeout(function(){
                $result.html("");
                location.reload();
            },2000);
        }
        else{
            var html_code = "<font color='red'><strong>"+result['message']+"</strong></font>";
            $result.html(html_code);
            
            setTimeout(function(){
                $result.html("");
            },2000);
        }
    })
}