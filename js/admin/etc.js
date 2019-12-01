
////////////////////////////
// 새로운 challenge 만들때
$(".btn-outline-success.config-challenge-new").click(function(){
    var mode = $(this).val();
    
    if(mode == "new"){
        $(this).val("submit");
        $(this).html("Submit");
        $(this).parent().parent().parent().find(".config-content").slideDown();
    }
    else if(mode == "submit"){
        var title = $("input[name=config-challenge-new-title]").val();
        var contents = $("textarea[name=config-challenge-new-contents]").val();
        var flag = $("input[name=config-challenge-new-flag]").val();
        var points = $("input[name=config-challenge-new-points]").val();
        var bonus = $("input[name=config-challenge-new-bonus]").val();
        var decrease = $("input[name=config-challenge-new-decrease]").val();
        var category = $("select[name=config-challenge-new-category]").val();
        var level = $("select[name=config-challenge-new-level]").val();
        var $result = $(this).parent().find(".config-challenge-request-result");
        
        if (title.length * contents.length * flag.length * points.length * bonus.length * decrease.length * category.length * level.length == 0){
            var html = `<font color="red">You need to full input box all.</font>`;
            $result.html(html);
            
            setTimeout(function(){
                $result.html("");
            },2000)
        }   
        else{
            createNewChallenge(title, contents, flag, points, bonus, decrease, category, level, $result);
            
            
            $(this).val("new");
            $(this).html("Create New Challenge");
            $(this).parent().parent().parent().find(".config-content").slideUp();
            $("input[name=config-challenge-new-title]").val("");
            $("textarea[name=config-challenge-new-contents]").val("");
            $("input[name=config-challenge-new-flag]").val("");
            $("input[name=config-challenge-new-points]").val("");
            $("input[name=config-challenge-new-bonus]").val("");
            $("input[name=config-challenge-new-decrease]").val("");
        }
    }
})


// challenge 수정 페이지에서 edit challenge 버튼 이벤트
$(".btn-outline-success").click(function(){
    var pageCheck = $(location).attr('search').indexOf("challenge");
    
    if(pageCheck == -1){
        return;
    }
    
    var idx = $(this).attr("id");
    var radio = $(this).parent();
    var challenge = $(".config-content").find("#idx"+idx);
    var list = ['#challenge-title', "#challenge-flag", "#challenge-content", "#challenge-points",
        "#challenge-bonus", "#challenge-decrease", "#challenge-category", "#challenge-hint", "input[type=radio]", ".config-challenge-level"];
    var $result = $(this).parent().find(".config-challenge-request-result");
    
    // viewer => edit 모드로 전환시
    if($(this).val() == "viewer"){
        //button
        $(this).html("Submit");
        $(this).val("edit");
        
        for (var i in list){
            if(list[i] == "#challenge-category" || list[i] == ".config-challenge-level"){
                challenge.find(list[i]).removeAttr("disabled");
            }
            else if(list[i] == "input[type=radio]"){
                radio.find(list[i]).removeAttr("disabled");
            }
            else{
                challenge.find(list[i]).removeAttr("readonly");
            }
        }
    }
    // edit => viewer 모드로 전환시
    else if($(this).val() == "edit"){
        var isVisible = radio.find("input[type=radio]").not(":checked").val();
        var isVisible = (isVisible == "visible" ? 0 : (isVisible == "hidden" ? 1 : false));
        var title = challenge.find(list[0]).val();
        var content = challenge.find(list[2]).val();
        var points = challenge.find(list[3]).val();
        var flag = challenge.find(list[1]).val();
        var bonus = challenge.find(list[4]).val();
        var decrease = challenge.find(list[5]).val();
        var category = challenge.find(list[6]).find(":selected").val();
        var level = challenge.find(list[9]).find(":selected").val();
        
        //button
        $(this).html("Edit challenge");
        $(this).val("viewer");
        
        for (var i in list){
            if(list[i] == "#challenge-category" || list[i] == ".config-challenge-level"){
                challenge.find(list[i]).attr("disabled", true);
            }
            else if(list[i] == "input[type=radio]"){
                radio.find(list[i]).attr("disabled", true);
            }
            else{
                challenge.find(list[i]).attr("readonly", true);
                
            }
        }
        requestChallenge(idx, title, content, flag, points, bonus, decrease, category, isVisible, level, $result);
    }
    
})

// User 텝에서 user 정보를 수정하려고 할때
$(".btn-outline-success").click(function(){
    var pageCheck = $(location).attr('search').indexOf("user");
    if(pageCheck == -1){
        return;
    } 

    var button = $(this).val();
    var $challenge = $(this).parent().parent().parent().find(".config-content");
    var $radio = $challenge.find("input[type=radio]");
    
    if(button == "viewer"){
        // $challenge.find("#user-id").removeAttr("disabled");
        $challenge.find("#user-admin").removeAttr("disabled");
        $challenge.find("#user-common").removeAttr("disabled");
        // $challenge.find("#user-nickname").removeAttr("disabled");
        $challenge.find("#user-points").removeAttr("disabled");
        $challenge.find("#user-reset-password").removeAttr("disabled");
        $challenge.find("#user-confirm-password").removeAttr("disabled");
        $radio.removeAttr("disabled");
        
        $(this).val("edit");
        $(this).html("Submit");
    }
    else if(button == "edit"){
        var pw1 = $challenge.find("#user-reset-password").val();
        var pw2 = $challenge.find("#user-confirm-password").val();
        var isVisible = $challenge.find(".user-visible").find("input[type=radio]:checked").val();
        isVisible = isVisible == "visible" ? 1 : (isVisible == "hidden" ? 0 : false);
        console.log(isVisible);
        var auth = $challenge.find(".user-auth").find("input[type=radio]:checked").val(); 
        auth = auth == "admin" ? 1 : (auth == "common" ? 0 : false);
        // var nickname = $challenge.find("#user-nickname").val();
        var points = $challenge.find("#user-points").val();
        var userid = $challenge.find("#user-id").val();
        var $result = $(this).parent().find(".config-challenge-request-result");
        
        if(!(pw1.length == 0 && pw2.length == 0)){
            if(pw1 != pw2){
                alert("입력한 패스워드가 같지 않습니다.");
                return;
            }
            else if(!(8 <= pw1.length && pw1.length <=30)){
                alert("패스워드 길이는 8 ~ 30글자 여야 합니다.");
                return;
            }   
        }
        if (isVisible === false){
            return ;
        }
        
        // $challenge.find("#user-id").attr("disabled", true);
        $challenge.find("#user-admin").attr("disabled", true);
        $challenge.find("#user-common").attr("disabled", true);
        // $challenge.find("#user-nickname").attr("disabled", true);
        $challenge.find("#user-points").attr("disabled", true);
        $challenge.find("#user-reset-password").attr("disabled",true);
        $challenge.find("#user-confirm-password").attr("disabled",true);
        $radio.attr("disabled", true);
        $challenge.find("#user-reset-password").val("");
        $challenge.find("#user-confirm-password").val("");
        
        $(this).val("viewer");
        $(this).html("Edit User Info");
        
        requestUserInfo(userid, points, pw1, pw2, isVisible, auth, $result);
    }
    
})


$(".config-log-input").keyup(function(){
    var nickname = $(this).val();
    $(".config-announcement-box").hide()
    
    var findUser = $(".config-log-nickname:contains('"+nickname+"')");
    $(findUser).parent().parent().parent().show();
    
    // if(nickname == ""){
    //     // log backup
    //     $(".config-log").css("display", "");
    //     $(".config-log-result").css("display", "none");
    // }
    // else{
    //     $(".config-log").css("display", "none");
    //     $(".config-log-result").css("display", "");
    //     $(".config-log-result").html($(".config-log").find(".config-log-"+nickname));
    // }
})


//////////////////////
// 마우스 모양 바꾸기
$(document).ready(function(){
  $(".config-category-result-btn").css('cursor', 'pointer');  
})

