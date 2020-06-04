<script language="javascript">$("document").ready(function() {
    var date = new Date('<?=date("Y-m-d")?>');
     $.datepicker.regional['ru'] = {
        closeText: 'Закрыть',
        prevText: '&#x3c;Пред',
        nextText: 'След&#x3e;',
        currentText: 'Сегодня',
        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
            'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
        monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
            'Июл','Авг','Сен','Окт','Ноя','Дек'],
        dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
        dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
        dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
        dateFormat: 'yy-mm-dd',
        defaultDate: date,
        firstDay: 1,
        isRTL: false
    };
    $.datepicker.setDefaults( $.datepicker.regional["ru"] );
    $("#divAppointmentCalendar").datepicker({
        /*dateFormat: 'yy-mm-dd',
        defaultDate: date,
        dayNamesMin: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'],*/
        onSelect: function (dateText, inst){alert(dateText);}
    });
    
});
function dbfAppointmentSelect(dateText, inst) // Не используется
{
    alert(dateText + '---' + inst);
}
</script>
<div id="divAppointmentCalendar"></div>
<div class="<?#ui-datepicker-header ui-widget-header ?>ui-helper-clearfix ui-corner-all divAppointmentCalendarDay" id="divAppointmentDay">
    <table cellpadding="0" cellspacing="0" style="padding: 0;margin: 0;" id="divAppointmentTableDay">
        <tr>
            <td>9:00</td>
            <td>15:00</td>
        </tr>
        <tr>
            <td class="dbfAppointmentFree">10:00</td>
            <td>16:00</td>
        </tr>
        <tr>
            <td>11:00</td>
            <td class="dbfAppointmentAppointed">17:00</td>
        </tr>
        <tr>
            <td>12:00</td>
            <td class="dbfAppointmentOff">18:00</td>
        </tr>
        <tr>
            <td>13:00</td>
            <td class="dbfAppointmentReserved">19:00</td>
        </tr>
        <tr>
            <td>14:00</td>
            <td>20:00</td>
        </tr>
    </table>
    <!--<?if (is_array($currentDay)):?>
    <table cellpadding="0" cellspacing="0"> 
        <?foreach ($currentDay as $key=>$hour):?>
            <tr><td class="<?=$hr["color"]?'dbfAppointmentColor1':'dbfAppointmentColor2'?>"><?=$key?></td></tr>
            <?foreach ($hour as $hr):?>
            <tr><td class="<?=$hr["status"]?>"><a><?=$hr["min"]?></a></td></tr>
        <?endforeach;endforeach;?>
    </table>-->    
    <?endif;?>
</div>
<br class="clear" style="" />
    <table style="width: 100%;font-size: 12px; height: 20px;"><tr>
        <td class="dbfAppointmentReserved">00<br />мин.</td>
        <td class="dbfAppointmentFree">15<br />мин.</td>
        <td class="dbfAppointmentAppointed">45<br />мин.</td>
        <td class="dbfAppointmentOff">60<br />мин.</td>
    </tr></table>
