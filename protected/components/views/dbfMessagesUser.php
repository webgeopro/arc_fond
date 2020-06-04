<script language="javascript">$("document").ready(function() {
    $("#btnSendMeesage").click(function(){
        $('#formMessageSend').ajaxSubmit({
            dataType: 'json',
            success : showResponse
        });
        return false;
    });
    $("#areaDBFMessageBody").bind("click keyup", function(event){ // Слушаем нажатие клавиш и клики мыши (для предотвращения вставки)
        var text_area = $(this).val();               // Получить текст
        var max_letters = <?=$maxLetters?>;          // Максимально допустимое кол-во символов
        var remain = max_letters - text_area.length; // Получить остаток символов
        if (text_area.length <= max_letters) {       // Меньше допустимой длины
            $("#spCntLetters").text(remain);         // Изменяем счетчик оставшихся символов
        } else {                                     // Больше допустимой длины
            $(this).val(text_area.substr(0,max_letters)); // Обрезаем лишние символы 
            $("#spCntLetters").text('0');                 // Изменяем счетчик на 0 оставшихся символов  
        }
    });
    //$('#areaDBFMessageBody').hint();
});
function showResponse(data)
{
    if ('success' == data.result) {
        $("#divDbfMessageSendForm").text('Ваше сообщение отправлено.');
    } else {
        alert('При сохранении обнаружены ошибки.');
    }
}
</script>

<div id="divDbfMessageSendForm">
    <form id="formMessageSend" name="formMessageSend" action="/cab/sendMessage" method="post">
        <div id="divDbfMessageError" style="color:red;"></div>
        Количество символов (<span id="spCntLetters"><?=$maxLetters?></span>):
        <div id="divDbfMessageBox">
            <textarea id="areaDBFMessageBody" name="messageBody" <?#title="Оставьте обратный адрес в тексте сообщения."?>></textarea>
        </div>
        <input type="hidden" name="pID" value="<?=$pageID?>" />
    </form>
    <div class="divFormSend">
        <a id="btnSendMeesage" class="aFormSend">Отправить</a>
    </div>
</div>