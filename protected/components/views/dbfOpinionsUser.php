<script language="javascript">$(document).ready(function() {
    opinionType($("#areaDbfOpinionsBody"));
    
    $("#areaDbfOpinionsBody").bind("click keyup", function(event){ // Слушаем нажатие клавиш и клики мыши (для предотвращения вставки)
        opinionType($(this));
    });
    $("#aDbfOpinionNew").click(function() {
        $("#divDbfOpinionsSendForm").toggle();
        return false;
    });
        
});
function opinionType(ob)
{
    var text_area = ob.val();                    // Получить текст
    var max_letters = <?=$maxLetters?>;          // Максимально допустимое кол-во символов
    var remain = max_letters - text_area.length; // Получить остаток символов
    if (text_area.length <= max_letters) {       // Меньше допустимой длины
        $("#spDbfOpinionsCntLetters").text(remain);  // Изменяем счетчик оставшихся символов
    } else {                                     // Больше допустимой длины
        ob.val(text_area.substr(0,max_letters)); // Обрезаем лишние символы 
        $("#spDbfOpinionsCntLetters").text('0');     // Изменяем счетчик на 0 оставшихся символов  
    }
}
function showResponse(data)
{
    if ('success' == data.result) {
        $("#divDbfOpinionsSendForm").text('Ваш отзыв сохранен.');
    } else {
        alert('При сохранении обнаружены ошибки.');
    }
}
</script>
<?=$widgetContent?>
<?/*<a href="" id="aDbfOpinionNew">оставить отзыв</a><br class="clear" />*/?>

<div id="divDbfOpinionsSendForm">
    <form id="formOpinionsSend" name="formOpinionsSend" action="/cab/send" method="post">
        <div id="divDbfOpinionsError" style="color:red;"></div>
        Количество символов (<span id="spDbfOpinionsCntLetters"><?=$maxLetters?></span>):
        <div id="divDbfOpinionsSendBox">
            <textarea id="areaDbfOpinionsBody" name="messageBody"><?=$opinion['body']?></textarea>
        </div>
        <input type="hidden" name="pID" value="<?=$pageID?>" />
        <input type="hidden" name="sendType" value="opinions" />
    </form>
    <div class="divFormSend">
        <a id="btnDbfOpinionsSend" class="aFormSend">Отправить</a>
    </div>
</div>


<?/*
<div id="divDbfOpinionsSendForm"><form id="formOpinionSend" action="/cab/send" method="post">
    <table cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="2">Текст сообщения (<span id="spOpinionCntLetters"><?=$maxLetters?></span>):</td>
        </tr>
        <tr>
            <td colspan="2"><textarea id="areaOpinionBody" name="messageBody"><?=$opinion['body']?></textarea></td>
        </tr>
        <tr>
            <td><input type="hidden" name="pID" value="<?=$pageID?>" /><input type="hidden" name="sendType" value="opinions" /></td>
            <td><input type="submit" value="сохранить" id="btnSendOpinion" /></td>
        </tr>
    </table>
</form></div>*/?>