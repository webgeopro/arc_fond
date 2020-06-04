$("document").ready(function() {

    $("#aDbfMessagesAll").click(function(){ // Не используется
        $("#divDbfMessagesAll").toggle();
        
        return false;
    });
    $("#aDbfMessagesHide").click(function(){
        $("#divDbfMessagesList").hide();
        
        return false;
    });
    $("#aDbfMessagesUnread, #aDbfMessagesFavorite, #aDbfMessagesAll").click(function(){
        $("#divDbfMessagesList").load("/cab/getMessages", { 
            'messageType': this.name 
        }, function(){}).slideDown("fast");

        /*if ($("#divDbfMessagesList:first").is(":hidden")) {
            $("#divDbfMessagesList").load("/cab/getMessages", { 
                'messageType': this.name 
            }, function(){}).slideDown("fast");
        } else {
            $("#divDbfMessagesList").slideUp("fast");
        }*/
        return false;
    });
});
