$("document").ready(function () {

    /*$("#a-components").click(function () {
        if ($("#ul-components:first").is(":hidden")) {
            $("#ul-components").slideDown("fast");
        } else {
            $("#ul-components").slideUp("fast");
        }

        return false;
    });

    $("#a-components").toggle(
        function () {$("#ul-components").slideDown("fast")},
        function () {$("#ul-components").slideUp("fast")});

        return false;
    });*/

    $(".divTabs").click(function () {
        $('#divCabContent').load($(this).attr('url'));
        return false;
    });

    $("#inpLogo, #Profile_image").change(function() {
        $("#formLogo").ajaxSubmit({
            dataType: 'json',
            success : showResponse
        });
        return false;
    });
    
//    $('#divLogo')
//    	.ajaxStart(function () {$(this).find('#ajax-loader').show();})
//	    .ajaxStop(function () {$(this).find('#ajax-loader').hide();});
});
function showResponse(data)
{
    if ('success' == data.result) {
        //alert(data.logo);
        $("#divLogoMiddle").css('background-image', 'url("'+data.logo+'")');
    } else {
        alert('При сохранении обнаружены ошибки.');
    }
}
function SetRating(value, recipient_id) {
    $.ajax({
        url:"/cab/setRating",
        type:'GET',
        data: "recipient_id="+recipient_id+"&rate=" + value,
        "success" : function(data) {
            if("success" == data) {
                alert('Ваше мнение учтено.');
                $("#rating > input").rating("readOnly", true);
            } else {
                alert('Ошибка...');
            }                
        },
        "cache" : false,
    });
}