<script language="javascript">$("document").ready(function() {
    $("#btnSendMeesage").click(function(){
        var message = $("#areaDBFMessageBody").val();
        var title   = $("#areaDBFMessageBody").attr('title');
        if ( title != message ) {
            $('#formMessageSend').ajaxSubmit({
                dataType: 'json',
                success : showResponse
            });
        } else {
            alert("Поле сообщение пустое.");
        }
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
    $('#areaDBFMessageBody').hint();
});
function showResponse(data)
{
    if ('success' == data.result) {
        $("#divDbfMessageSendForm").text('Ваше сообщение отправлено.');
    } else if ('errorCaptcha' == data.result) {
        $("#divDbfMessageError").text('Введен неверный код.');
        $("#yw0_button").click();
    } else {
        alert('При сохранении обнаружены ошибки.');
    }
}
</script>
<div id="divDbfMessageSendForm"><form id="formMessageSend" name="formMessageSend" action="/cab/sendMessage" method="post">
    <input type="hidden" name="pID" value="<?=$pageID?>" /> 
    <div id="divDbfMessageError" style="color:red;"></div>
    Текст сообщения (<span id="spCntLetters"><?=$maxLetters?></span>):
    <div id="divDbfMessageBox">
        <textarea id="areaDBFMessageBody" name="messageBody" title="Оставьте обратный адрес в тексте сообщения."></textarea>
    </div>
    <?$this->widget('CCaptcha', array(
        'id'                => 'captchaDbfMessage',
        #'buttonLabel'=>'Другой код',
        #'buttonOptions'=> array('style'=>'float:right'),
        'imageOptions'      => array('style'=>'float:right'),
        'clickableImage'    => true,
        'showRefreshButton' => false,
    ));?>
    <?=CHtml::activeTextField($model,'verifyCode');?>
    <br class="clear" />
    <div class="divFormSend">
        <a id="btnSendMeesage" class="aFormSend">Отправить</a>
    </div>
    <!--
    <table cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="2">Текст сообщения (<span id="spCntLetters"><?=$maxLetters?></span>):</td>
        </tr>
        <tr>
            <td colspan="2"><textarea id="_areaBody" name="messageBody" title="Оставьте обратный адрес в тексте сообщения.">
            Pellentesque ultrices dignissim libero eu imperdiet. Nam interdum quam at erat rutrum nec dictum ante cursus. Nam quis urna ut dolor pulvinar posuere. Praesent vitae erat risus, vitae fermentum neque. Aenean sodales turpis id odio tincidunt tincidunt. Praesent vulputate ligula sit amet urna dignissim id pharetra nunc porta. Donec est dui, viverra ac varius sed, adipiscing eget nunc. Aliquam metus. 
            </textarea></td>
        </tr>
        <tr>
            <td colspan="2">
                <?$this->widget('CCaptcha', array('buttonLabel'=>'Другой код'));?>
                <?#=CHtml::activeTextField(Messages::model(), 'verifyCode')?>
                <?=CHtml::activeTextField($model,'verifyCode');?>
            </td>
        </tr>
        <tr>
            <td><input type="hidden" name="pID" value="<?=$pageID?>" /></td>
            <td><input type="submit" value="отправить" id="btnSendMeesage" /></td>
        </tr>
    </table>-->
</form></div>