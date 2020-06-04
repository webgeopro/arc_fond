<?

    $("#_areaBody").keypress(function(event){
        var cnt = parseInt($("#spCntLetters").text());
        var mess = $("#areaBody").text();
        //alert('|'+event.which+'|');
        if (event.which == 32 || 1105 == event.which || 1025 == event.which
                || (65 <= event.which && event.which <= 65 + 25) || (97 <= event.which && event.which <= 97 + 25)
                || (1040 <= event.which && event.which <= 1040 + 31) || (1072 <= event.which && event.which <= 1072 + 31)) {
            if ( cnt-- ) $("#spCntLetters").text(cnt);
            else return false;
        } else if (event.keyCode == 8 || event.keyCode == 46) {
            if (<?=$maxLetters?> <= cnt++) return false;
            $("#spCntLetters").text(mess.length); 
        }
    });