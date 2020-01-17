$(".submit-flag").click(function(){
    var input_control = $(this).parent().find(".form-control");
    
    var flag = input_control[0].value;
    var idx = $(this)[0].value;
    var csrf_token = input_control.parent().find(".csrf_token").val();
    var isCorrect = 0;
    
    // Empty value
    input_control.val("");
    
    // Ajax start
    $.ajax({
        url: "./checkFlag.php",
        async: false,
        type: "POST",
        dataType: "json",
        data: {
            idx: idx,
            flag: flag,
            csrf_token: csrf_token
        },
        
        // Before send request
        beforeSend: function(result){
            if(flag.length == 0){
                result.abort();
            }
        }
    }).done(function(data){
        // Flag is correct.
        if(data['result'] == "success"){
            input_control.css("background-color", "rgba(43, 167, 40, 0.55)");
            input_control.attr("placeholder","Correct!!");
            input_control.css("font-weight", "bold");
            
            find_id = "#idx_" + idx; 
            
            $(find_id).css("background", "green");
            
            isCorrect = 1;
        }
        
        // User already solved challenge.
        else if(data['result'] == "Already solved"){
            input_control.css("background-color", "#effb8e");
            input_control.attr("placeholder","Already solved");
            input_control.css("font-weight", "bold");
        }
        
        // Flag is wrong.
        else if(data['result'] == "wrong_flag"){
            input_control.css("background-color", "rgba(247, 135, 135, 0.76)");
            input_control.attr("placeholder","Wrong...");
            input_control.css("font-weight", "bold");
        }
        
        // Defense CSRF attack.
        else if(data['result'] == "wrong_csrf_token"){
            input_control.css("background-color", "rgba(247, 135, 135, 0.76)");
            input_control.attr("placeholder","Your csrf_token error, please refresh.");
            input_control.css("font-weight", "bold");
        }
        
        // Unexpected error.
        else if(data['result'] == "error"){
            input_control.css("background-color", "rgba(247, 135, 135, 0.76)");
            input_control.attr("placeholder","Your request caused error.");
            input_control.css("font-weight", "bold");
        }
        else if(data['result'] == "CCTF is not started." || data['result'] == "CCTF is ended."){
            input_control.css("background-color", "#effb8e");
            input_control.attr("placeholder",data['result']);
            input_control.css("font-weight", "bold");
        }
    });
    
    // Init input style after 1 sec
    if(isCorrect){
        setTimeout(function(){
            input_control.parent().parent().find(".close").click();
            input_control.css("background-color","#fff");
            input_control.css("font-weight","400");
            input_control.attr("placeholder","Input Flag");
        },1000);
        setTimeout(function(){
            location.reload();
        },1500);
    }
    else{
        setTimeout(function(){
            input_control.css("background-color","#fff");
            input_control.css("font-weight","400");
            input_control.attr("placeholder","Input Flag");
        },1000);
    }
})