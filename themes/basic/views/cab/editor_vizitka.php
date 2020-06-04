<?if ($boolEdit): #Редактирование визитки ?>
<link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->baseUrl?>/css/timePicker.css" />
<script src="<?=Yii::app()->theme->baseUrl?>/js/jquery.timePicker.js" language="javascript"></script>
<script language="javascript">
function showResponse(data)
{
    var container = $('#formVizitka');
	$('input',container).removeClass('error').next('.b-description_error').remove();
    if ('success' == data.result) {
        location.reload();
        //$("#divVizitka").load('/cab/get',{pID: <?=$user->id?>, url: 'vizitka', viewer: 'true'});
    } else {
        //alert('При сохранении обнаружены ошибки.');
        for(field in data.errors) {
            if(field.toString() != 'requisites') {
	            $('input[name=' + field.toString() + '], textarea[name=' + field.toString() + ']', container)
		            .addClass('error')
	            	.after('<p class="b-description b-description_error">' + data.errors[field][0] + '</p>');
            }
            else {
                for(field_sub in data.errors.requisites) {
		            $('input[name=Requisites\[' + field_sub.toString() + '\]]', container)
		            	.addClass('error')
		            	.after('<p class="b-description b-description_error">' + data.errors.requisites[field_sub][0] + '</p>');
                }
            } 
        }
    }    
}

var Activity = {
	init: function () {
		$('#activityAddNew').bind('change', function(e) {
			var self = $(e.target);
			if(self.is('select')) {
				var	val = self.val();
				if(val > 0) {
					$.get(
						'/cab/activityOptions', 
						{'root_id': val},
						function(data) {
							if(data) {
								self.nextAll().remove();
								$('<select id="activityAddNew-' + val + '" class="activitySelect"></select>')
									.insertAfter(self)
									.html('<option selected="selected">—</option>' + data);
							}
							else {
								self
									.nextAll().remove().end()
									.after('<input type="button" class="button" value="Добавить" />')
									.next('input:button')
									.click(function() {
										var key = self.val(),
											title = self.children(':selected').text();
										Activity.add(key, title)
									});
							}					
						});
				}
			}
		});
		$('#activitySelected').bind('change', function(e) {
			var self = $(e.target);
			if(self.is('input:checkbox')) {
				if(! self.attr('checked')) {
					Activity.remove(self.val());
				}
				else {
					Activity.add(self.val(), '');
				}
			}
		});
		this.render();
	}
	,checked:<?php echo json_encode($activitySelected) ?>
	,add: function (key, title) {
		if(title && ! this.checked.hasOwnProperty(key)) {
			this.checked[key] = {
				'title': title,
				'checked': true
			};
		}
		else if(this.checked.hasOwnProperty(key)) {
			this.checked[key]['checked'] = true;
		}
		this.render(); 		
	}
	,remove: function (key) {
		if(this.checked.hasOwnProperty(key)) {
			this.checked[key]['checked'] = false;
		}
//		$('#activitySelected #acivitySelected-' + key).remove();
//		this.render();
	}
	,render: function () {
		var str = '';
		for(key in this.checked) {
			str += 
				'<input type="checkbox" class="input_chbx" name="activity[]" value="' + 
				key + 
				'" id="activitySelected-' + 
				key + 
				'"' + 
				(this.checked[key]['checked'] ? ' checked="checked"' : '') 
				+ 
				' /><label for="activitySelected-' + 
				key + 
				'">' + 
				this.checked[key]['title'] + 
				'</label><br/>'
		}
		if(! str) {
			str = 'Ничего не добавлено';
		}
		$('#activitySelected .data').empty().append(str);
	}
};

$("document").ready(function () {
	$('.townSelectField').selectableTree({'url':'/cab/townsOptions'});
	
    $("#aSave").click(function() {
        $("#formVizitka").ajaxSubmit({
            dataType:   'json',
            success:       showResponse
        });
        return false;
    });
    $("#aCancel").click(function() {
        $("#divVizitka").load('/cab/get',{pID: <?=$user->id?>, url: 'vizitka', viewer: 'true'});
        return false;
    });
    $(".autocomplete").jSuggest({
        url: "/cab/autocomplete", type: "POST",
        data: "iKeywords",
        minchar: 2,loadingImg: '<?=Yii::app()->theme->baseUrl?>/images/ajax_loader.gif', loadingText: 'Подождите...',
        delay: 500, autoChange: false,
        opacity: 1.0, zindex: 20000
    });

	Activity.init();

    // Массив изначальных значений границ рабочего времени
	var worktime_list = {},
		end_time = '23:59';
	
    $("#tabTimePicker input:text")
        .timePicker({startTime: "00:00", endTime: end_time})
    	.each(function () {
        	worktime_list[$(this).attr('id')] = $.timePicker($(this)).getTime();
        })
		.change(function() {
			var start, end, duration, time = {};
			// Если меняем первую графу - сдвигаем автоматически вторую
	  		if ($(this).is('.input_text_begin')) {
		  		start = $(this);
		  		end = start.siblings('.input_text_end');
	  		}
	  		else if($(this).is('.input_text_end')) {
	  			end = $(this);
		  		start = end.siblings('.input_text_begin');
	  		}
		  	if(end.length > 0 && start.length > 0) {
		  		duration = $.timePicker(end).getTime() - worktime_list[start.attr('id')];
		  		time['start'] = $.timePicker(start).getTime();
		  		time['end'] = $.timePicker(end).getTime();
//		  		if(duration > 0) {
//			 		    $.timePicker(end).setTime(new Date(time.getTime() + duration));
//		  		}
		  		worktime_list[start.attr('id')] = time.start;

		  		if(time.start > time.end) {
		  			start.addClass('error');
		  			end.addClass('error');
	  			}
	  			else {
		  			start.removeClass('error');
		  			end.removeClass('error');
	  			}
				
		  		if(duration > 0) {
			  		$(this).siblings('.input_chbx_dayoff').removeAttr('checked');
		  		}
		  		/*
		  		else {
			  		$(this).siblings('.input_chbx_dayoff').attr('checked', 'checked');
		  		}
		  		*/
		  	}
		})
		.siblings('input:checkbox').change(function () {
			if($(this).is('.input_chbx_dayoff')) {
				if($(this).attr('checked')) {
					$(this).siblings('.input_text').hide();
				}
				else {
					$(this).siblings('.input_text').show();
				} 
	  		}
		});
	
    $('body').click(function (e) {
		if(! $(e.target).is('.time-picker')) {
			$('.time-picker').hide();
		}
	})
    
    $("#aRequisites").click(function() {
        $("#divRequisites").toggle();
        return false;
    });

});</script>
<form method="post" action="/cab/save" id="formVizitka">
	<input type="hidden" name="element_id" value="vizitka::<?=$user->id?>" />
    <table class="table_form">
    	<tr>
    		<th colspan="3">
    			<h3 class="b-title b-title_main">Юридический адрес:</h3>		
    		</th>
    	</tr>
    	<tr>
    		<td style="width:70px">
    			<p class="b-title">Индекс</p>
    			<?=CHtml::textfield('index0', $user->profile["index0"])?>
    		</td>
    		<?php /*?>
    		<td style="width:200px">
    			<p class="b-title">Город</p>
			    <input type="text" name="town0" id="town0" value="<?=$user->profile["fk_town0"]["name"]?>" class="autocomplete" />
    		</td>
    		*/?>
    		<td style="width:200px">
    			<p class="b-title">Город</p>
			    <input type="text" name="town0_title" value="<?=$user->profile["fk_town0"]["name"]?>" class="townSelectField"/>
			    <input type="hidden" name="town0" value="<?=$user->profile['fk_town0']['id']?>" />
    		</td>
    		<td>
    			<p class="b-title">Адрес</p>
			    <?=CHtml::textfield('address0', $user->profile["address0"])?><br />
    		</td>
    	</tr>
    	<tr>
    		<th colspan="3">
    			<h3 class="b-title b-title_main">Фактический адрес:</h3>		
    		</th>
    	</tr>
    	<tr>
    		<td style="width:70px">
    			<p class="b-title">Индекс</p>
    			<?=CHtml::textfield('index', $user->profile["index"])?>
    		</td>
    		<td style="width:200px">
    			<p class="b-title">Город</p>
			    <input type="text" name="town_title" value="<?=$user->profile['fk_town']['name']?>" class="townSelectField" />
			    <input type="hidden" name="town" value="<?=$user->profile['fk_town']['id']?>" />
    		</td>
    		<td>
    			<p class="b-title">Адрес</p>
			    <?=CHtml::textfield('address', $user->profile["address"])?><br />
    		</td>
    	</tr>
    </table>
    <table class="table_form">
    	<tr>
    		<th colspan="3">
    			<h3 class="b-title b-title_main">Контактная информация:</h3>		
    		</th>
    	</tr>
    	<tr>
    		<td style="width:33%">
    			<p class="b-title">Телефон</p>
    			<?=CHtml::textfield('cphone', $user->profile["cphone"])?>
    		</td>
    		<td>
    			<p class="b-title">Факс</p>
			    <?=CHtml::textfield('fax', $user->profile["fax"])?>
    		</td>
    		<td>
    			<p class="b-title">Сайт</p>
			    <?=CHtml::textfield('site', $user->profile["site"])?>
    		</td>
    	</tr>
    	<tr>
    		<th colspan="3">
    			<h3 class="b-title b-title_main">Сфера деятельности:</h3>		
    		</th>
    	</tr>
    	<tr>
    		<td colspan="3">
    			<div id="activitySelected">
	    			<p class="b-title">Выбирайте свои сферы деятельности из списка справа и нажмимайте «Добавить».</p>
    				<h4>Уже добавленные элементы:</h4>
    				<div class="data"></div>
    			</div>
    			<div id="activityAddNew">
	    			<select class="activitySelect">
	    			<?php 
	    			if( !empty($activityItems)) {
						$str = '<option value=\"0\">—</option>';
						foreach($activityItems as $item) {
							$str .= "<option value=\"{$item->id}\">{$dashes} {$item->name}</option>";			
						}
						echo $str;
					}
	    			?>
	    			</select>
    			</div>
    		</td>
    	</tr>
    	<tr>
    		<th colspan="3">
    			<h3 class="b-title b-title_main">Описание деятельности:</h3>		
    		</th>
    	</tr>
    	<tr>
    		<td colspan="3">
    			<?=CHtml::textArea('activities', $user->profile["activities"], array('rows'=>4, 'cols'=>60))?>
    		</td>
    	</tr>
    </table>
    
    <table class="table_form" id="tabTimePicker">
    	<tr>
    		<td colspan="8">
    			<h3 class="b-title b-title_main">Время работы:</h3>
    		</td>
    	</tr>
        <tr class="table_header">
            <td><p class="b-title">Пн.</p></td>
            <td><p class="b-title">Вт.</p></td>
            <td><p class="b-title">Ср.</p></td>
            <td><p class="b-title">Чт.</p></td>
            <td><p class="b-title">Пт.</p></td>
            <td><p class="b-title">Сб.</p></td>
            <td><p class="b-title">Вс.</p></td>
            <td><p class="b-title">Обед</p></td>
        </tr>
        <?php 
        	$worktime = $user->worktime;
        	$days = array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'); 
        ?>
        <tr>
        	<?php foreach($days as $day) {
        		$isDayOff = Worktime::model()->isDayOff($worktime["{$day}1"], $worktime["{$day}2"]);
        	?>
            <td class="time_cell">
            	<?php echo CHtml::checkBox("dayoff[$day]", $isDayOff, array('class'=>'input_chbx input_chbx_dayoff'))?>
        		<?php echo CHtml::label('Выходной',"dayoff_{$day}")?>
            	<?=CHtml::textfield("{$day}1", $worktime["{$day}1"], array('class'=>'input_text input_text_begin', 'style'=> $isDayOff ? 'display:none' : '' ))?>
            	<?=CHtml::textfield("{$day}2", $worktime["{$day}2"], array('class'=>'input_text input_text_end', 'style'=> $isDayOff ? 'display:none' : '' ))?>
            </td>
            <?php } ?>
            <td>
            	<?=CHtml::textfield('din1', $worktime["din1"])?>
            	<?=CHtml::textfield('din2', $worktime["din2"])?>
            </td>
        </tr>
    </table>
    	<table class="table_form">
	    	<tr>
	    		<th colspan="2">
	    			<h3 class="b-title b-title_main">Реквизиты:</h3>		
	    		</th>
	    	</tr>
	    	<tr>
	    		<td colspan="2">
	    			<p class="b-title">Полное название</p>
	    			<?=CHtml::textfield('orgname',$user->profile["orgname"])?>
	    		</td>
	    	</tr>
	    	<tr>
	    		<td colspan="2">
	    			<p class="b-title">Сокращенное название</p>
	    			<?=CHtml::activeTextfield($requisites_model,'name_short')?>
	    		</td>
	    	</tr>
	    	<tr>
	    		<td colspan="2">
	    			<p class="b-title">Основание лица, уполномоченного на подписание договора</p>
	    			<?=CHtml::activeTextfield($requisites_model,'base')?>
	    		</td>
	    	</tr>
	    	<tr>
	    		<td colspan="2">
	    			<p class="b-title">Банк</p>
	    			<?=CHtml::activeTextfield($requisites_model,'bank_account')?>
	    		</td>
	    	</tr>
	    	<tr>
	    		<td colspan="2">
	    			<p class="b-title">БИК</p>
	    			<?=CHtml::activeTextfield($requisites_model,'bik')?>
	    		</td>
    		</tr>
			<tr>
	    		<td>
	    			<p class="b-title">ИНН</p>
	    			<?=CHtml::textfield('inn', $user->profile['inn'])?>
	    		</td>
	    		<td>
	    			<p class="b-title">КПП</p>
	    			<?=CHtml::textfield('kpp', $user->profile['kpp'])?>
	    		</td>
	    	</tr>
	    	<tr>
	    		<td style="width:50%;">
	    			<p class="b-title">Расчётный счёт</p>
	    			<?=CHtml::activeTextfield($requisites_model,'current_account')?>
	    		</td>
	    		<td>
	    			<p class="b-title">Корреспондентский счёт</p>
	    			<?=CHtml::activeTextfield($requisites_model,'correspondent_account')?>
	    		</td>
	    	</tr>
	    	<tr>
	    		<td>
	    			<p class="b-title">ОГРН</p>
	    			<?=CHtml::activeTextfield($requisites_model,'ogrn')?>
	    		</td>
	    		<td>
	    			<p class="b-title">ОКПО</p>
	    			<?=CHtml::activeTextfield($requisites_model,'okpo')?>
	    		</td>
	    	</tr>
	    	<tr>
	    		<td colspan="2">
<a href="" id="aCancel" class="b-button b-button_cancel">отменить</a>
<a href="" id="aSave" class="b-button">сохранить</a>
	    		</td>
	    	</tr>
	    </table>
</form>
<br />&nbsp;
<?else: # Загрузка визитки с последующим редактированием ?>

<script language="javascript">$("document").ready(function () {
    $("#aEdit").click(function() {
        $("#divVizitka").load('/cab/get',{pID: <?=$user->id?>, url: 'vizitka'});
        return false;
    });
    $("#aRequisites").click(function() {
        $("#divRequisites").toggle();
        return false;
    });
});</script>

<div id="divVizitkaL">
	<?php 
       	$worktime = $user->worktime;
       	$days = array('mon'=>'ПН', 'tue'=>'ВТ', 'wed'=>'СР', 'thu'=>'ЧТ', 'fri'=>'ПТ', 'sat'=>'СБ', 'sun'=>'ВС'); 
	?>
    <ul class="ulVizitka">
    	<?php foreach($days as $day=>$day_label) {
        	$isDayOff = Worktime::model()->isDayOff($worktime["{$day}1"], $worktime["{$day}2"]);
        ?>
        <li>
	        <?php echo $day_label ?>:
        	<span class="spVizitka">
        	<?php if($isDayOff) {?>
        	выходной
        	<?php }else {?>
        	<?=$user->worktime["{$day}1"]?> &ndash; <?=$user->worktime["{$day}2"]?>
        	<?php }?>
        	</span>
        </li>
        <?php if($day == 'fri') {?>
			</ul>
		</div>
		<div id="divVizitkaM">
			<ul class="ulVizitka">
        <?php } ?>
        <?php } ?>
        <li>&nbsp;</li>
        <li>&nbsp;</li>
        <?if ( ('00:00' == $user->worktime["din1"]) or ('00:00' == $user->worktime["din2"]) ):?>
            <li>ОБ:<span class="spVizitka">БЕЗ ОБЕДА</span></li>
        <?else:?>
            <li>ОБ:<span class="spVizitka"><?=$user->worktime["din1"]?> &ndash; <?=$user->worktime["din2"]?></span></li>
        <?endif?>
    </ul>
</div>
<?php /*?>
    <ul class="ulVizitka">
        <li>ПН:<span class="spVizitka"><?=$user->worktime["mon1"]?> &ndash; <?=$user->worktime["mon2"]?></span></li>
        <li>ВТ:<span class="spVizitka"><?=$user->worktime["tue1"]?> &ndash; <?=$user->worktime["tue2"]?></span></li>
        <li>СР:<span class="spVizitka"><?=$user->worktime["wed1"]?> &ndash; <?=$user->worktime["wed2"]?></span></li>
        <li>ЧТ:<span class="spVizitka"><?=$user->worktime["thu1"]?> &ndash; <?=$user->worktime["thu2"]?></span></li>
        <li>ПТ:<span class="spVizitka"><?=$user->worktime["fri1"]?> &ndash; <?=$user->worktime["fri2"]?></span></li>
    </ul>
</div>
<div id="divVizitkaM">
    <ul class="ulVizitka">
        <li>СБ:<span class="spVizitka"><?=$user->worktime["sat1"]?> &ndash; <?=$user->worktime["sat2"]?></span></li>
        <li>ВС:<span class="spVizitka"><?=$user->worktime["sun1"]?> &ndash; <?=$user->worktime["sun2"]?></span></li>
        <li>&nbsp;</li>
        <li>&nbsp;</li>
        <?if ( ('00:00' == $user->worktime["din1"]) or ('00:00' == $user->worktime["din2"]) ):?>
            <li>ОБ:<span class="spVizitka">БЕЗ ОБЕДА</span></li>
        <?else:?>
            <li>ОБ:<span class="spVizitka"><?=$user->worktime["din1"]?> &ndash; <?=$user->worktime["din2"]?></span></li>
        <?endif?>
        
    </ul>
</div>
*/?>
<div id="divVizitkaR"><?=$user->profile["activities"]?></div>
<br class="clear" />
<hr class="hrVizitka" />
<div id="divVizitkaName">
    <span id="spVizitkaName"><?=$user->profile["orgname"]?></span><br class="clear" />
    <?=$this->fieldUnion(array($user->profile['index'], $user->profile['fk_town']['name'], $user->profile['address']))?><br class="clear" />
    <?=$this->fieldUnion(array($user->profile['cphone'], $user->profile['fax']))?><br class="clear" />
    <?=$this->fieldUnion(array($user->email, $user->profile['site']))?><br class="clear" />
    <a href="" id="aRequisites" style="float:right;">Показать реквизиты</a> 
    <br class="clear" />
</div>
<!--
<?#$user->profile["inn"]?> / <?#$user->profile["kpp"]?> <br />
<?#$user->profile["index0"]?>, <?#$user->profile["fk_town0"]["name"]?>, <?#$user->profile["address0"]?> <br />
-->
<div id="divRequisites">
    Тип организации<b>: <?=Profile::model()->form[$user->profile["form"]][1]?></b><br/>
    <?if (!empty($user->requisites->user_id)):
    foreach($user->requisites as $name=>$req):
        if (0 < $i) // Отбрасываем id
            if ($req)
                echo CHtml::activeLabel($user->requisites,$name).'<b>: '.$req.'</b><br />';
        if($name == 'bik') {
	?>        	
      	<?php echo CHtml::activeLabel($user->profile, 'inn')?><b>: <?php echo $user->profile['inn']?></b><br/>
		<?php echo CHtml::activeLabel($user->profile, 'kpp')?><b>: <?php echo $user->profile['kpp']?></b><br/>
	<?php 
        }
    $i++;endforeach;endif;?>
</div>

<?if ( (! Yii::app()->user->isGuest) and ($pageID == Yii::app()->user->getId()) ):?>
    <br class="clear" /><a href="" id="aEdit" class="b-button" style="float:right;margin-right:40px;">редактировать</a><br />
<?endif?>

<?endif?>