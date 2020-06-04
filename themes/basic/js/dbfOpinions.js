$("document").ready(function() {
    $("#divDbfOpinions").easySlider({
        userHref: true,
        userNextId: "aDbfOpinionsNext",
        userPrevId: "aDbfOpinionsPrev",
        speed: 300,
		continuous: true
	});
    $("#aDbfOpinionsAll").click(function(){
        $("#tabcontent").load('/cab/get', {
            pID: this.name,
            url: 'opinions'
        });
        
        return false;
    });
    $("#btnDbfOpinionsSend").click(function(){
        $('#formOpinionsSend').ajaxSubmit({
            dataType: 'json',
            success : showResponse
        });
        return false;
    });
});
