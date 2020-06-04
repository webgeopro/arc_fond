$("document").ready(function() {
    $("#divComApp").easySlider({
        userHref: true,
        userNextId: "aComAppNext",
        userPrevId: "aComAppPrev",
        speed: 300,
		continuous: true
	});
    $("#aComAppAll").click(function(){
        $("#tabcontent").load('/cab/get', {
            pID: this.name,
            url: 'comApp'
        });
        
        return false;
    });
});