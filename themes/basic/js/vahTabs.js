$(document).ready(function(){

    $("#preloader").hide();

    function loadTab(id){
	if (pageUrl[id].length > 0){ 
		$("#preloader").show();
		$.ajax({
			type: "POST",
            data: "pID=<?=$pageID?>",
            url: pageUrl[id], 
			cache: false,
			success: function(message){			            	
				$("#tabcontent").empty().append(message);
				$("#preloader").hide();             
			}
		});			        
	}
    }
    $("#tabsJ li a").click(function () { //alert(this.id); return false;
        if ( 0 < $(this).attr("pageUrl").length ) { 
            $("#preloader").show();
            var options = {
                type: "POST",
                data: "pID="+$("#pID").val()+"&url="+$(this).attr("pageUrl"),
                url: "/cab/get", 
                cache: false,
                success: function(message) {
				    $("#tabcontent").empty().append(message);
				    $("#preloader").hide();             
                }
            }
            if ('tabs' == $(this).attr("pageUrl")) {
                options.data = "pID="+$("#pID").val()+"&url="+$(this).attr("userTabID")+'&field=tabs';
            }
            $.ajax(options);			    
            $(this).addClass('selected').parent('li').siblings('li').children('a').removeClass('selected');
        }
    return false;
    });
    
    $("#aLeft").click(function () { 
        cntUl = $("ul.item_selected").attr("cntUl");
        cntPrev = parseInt(cntUl) - 1;
        cntNext = parseInt(cntUl) + 1;
        
        if ( cntUl > $("#ulMin").val() ) {
            $("#ul_"+cntUl).addClass('item_hidden').removeClass('item_selected');
            $("#ul_"+cntPrev).removeClass('item_hidden').addClass('item_selected');
            $("#aRight").removeClass('a_disabled');
        }
        if (cntNext >= $("#ulMin").val()) {
            $("#aLeft").addClass('a_disabled');
        }
        return false;
    });
    
    $("#aRight").click(function () {
        cntUl = $("ul.item_selected").attr("cntUl");
        cntNext = parseInt(cntUl) + 1;
        cntPrev = parseInt(cntUl) - 1;
        
        if ( cntUl < $("#ulMax").val() ) {
            $("#ul_"+cntUl).addClass('item_hidden').removeClass('item_selected');
            $("#ul_"+cntNext).removeClass('item_hidden').addClass('item_selected');
            $("#aLeft").removeClass('a_disabled');
        }
        if (cntPrev <= $("#ulMax").val()) {
            $("#aRight").addClass('a_disabled');
        }
        return false;
    });
    
    $("#aNewTab, #aNewTabCancel").click(function () {
        $("#divNewTab").toggle();
        return false;
    });
    
    $("#aNewTabAdd").click(function () {
        if ( !$("#inpNewTab").val() )
            alert('Заполните имя новой закладки!');
        else {
            var options = {
                type: "POST",
                data: "type_id=tabs&name="+$("#inpNewTab").val(),
                url: "/cab/add", 
                cache: false,
                success: function(message) {			            	
				    if ('success' == message) {
				        alert('Закладка сохранена!');
                        location.reload(true);
				    } else if ('success' == message) {
				        alert('Некорректное имя закладки!');
				    }             
                }
                
            }
            $.ajax(options);
        }
        return false;
    });
    
    
});
    function fTabDelete(ob)
    { 
        if (confirm('Вы действительно хотите удалить закладку?')) {
            var options = {
                type: "POST",
                data: "element_id=tabs::" + ob.name,
                url: "/cab/delete", 
                success: function(message) {			            	
				    if ('success' == message) {
                        $("#tabs_"+ob.name).remove();
                    }
                }
            }
            $.ajax(options);
        }
        return false;
    }