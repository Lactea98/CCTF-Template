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



