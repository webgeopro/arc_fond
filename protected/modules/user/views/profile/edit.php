<table style="width:100%;">
<tr>
    <td style="width:200px;">
        <h2>Здраствуйте, <?=CHtml::encode($model->username)?></h2>
        <?=$this->renderPartial('menu')?>
    </td>
    <td style="background:;text-align:right;">
        <h1>Личный кабинет :: Редактирование профиля</h1>
        <?if(Yii::app()->user->hasFlash('profileMessage')):?>
        <div class="success">
            <?=Yii::app()->user->getFlash('profileMessage')?>
        </div>
        <?endif;?>
        <?=CHtml::beginForm('','post',array('enctype'=>'multipart/form-data', 'style'=>'width:450px;margin:0 auto;text-align:left;'))?>
        <div class="information alert">
            <?=CHtml::errorSummary($model);?>
            <?=CHtml::errorSummary($profile);?>
        </div>
        <br class="clearBoth" />
        <fieldset id="fieldRadio">
            <legend>Физическое / юридическое лицо</legend>
            <label for="Profile[type]" style="padding:0 10px;">Физическое / юридическое лицо:</label>
            <input type="radio" name="Profile[type]" id="ip" value="fieldIP" <?=$typeOrg['ip']?> />&nbsp;&nbsp;
            <input type="radio" name="Profile[type]" id="firm" value="fieldFirm" <?=$typeOrg['firm']?> />
            <br class="clearBoth" />
        </fieldset>
        <fieldset style="<?=!empty($typeOrg['firm'])?'display:none;':''?>" id="fieldIP">
            <legend>Физическое лицо</legend>
            <label class="inputLabel" for="Profile[cfaceIp]">ФИО:</label>
            <?=CHtml::activeTextField($profile,'cfaceIp',array('style'=>'width:280px;margin:0 0 3px;'))?>
            <span class="alert">*</span>
            <br class="clearBoth" />

            <label class="inputLabel" for="Profile[addressIp]">Адрес доставки:</label>
            <?=CHtml::activeTextField($profile,'addressIp',array('style'=>'width:280px;margin:0 0 3px;'))?>
            <br class="clearBoth" />

            <label class="inputLabel" for="Profile[cphoneIp]">Телефон:</label>
            <?=CHtml::activeTextField($profile,'cphoneIp',array('style'=>'width:280px;'))?>
            <span class="alert">*</span>
        </fieldset>
        <fieldset style="<?=!empty($typeOrg['ip'])?'display:none;':''?>" id="fieldFirm">
            <legend>Юридическое лицо</legend>
            <label class="inputLabel" for="Profile[orgname]">Наименование:</label>
            <?=CHtml::activeTextField($profile,'orgname',array('style'=>'width:280px;margin:0 0 3px;'))?>
            <br class="clearBoth" />
                
            <label class="inputLabel" for="Profile[address0]">Юридический адрес:</label>
            <?=CHtml::activeTextField($profile,'address0',array('style'=>'width:280px;margin:0 0 3px;'))?>
            <br class="clearBoth" />
                
            <label class="inputLabel" for="Profile[inn]">ИНН / КПП:</label>
            <?=CHtml::activeTextField($profile,'inn')?>&nbsp;/&nbsp;
            <?=CHtml::activeTextField($profile,'kpp',array('style'=>'margin:0 0 3px;'))?>
            <br class="clearBoth" />
                
            <label class="inputLabel" for="Profile[cface]">Контактное лицо:</label>
            <?=CHtml::activeTextField($profile,'cface',array('style'=>'width:280px;margin:0 0 3px;'))?>
            <span class="alert">*</span>
            <br class="clearBoth" />
                
            <label class="inputLabel" for="Profile[cphone]">Телефон / факс:</label>
            <?=CHtml::activeTextField($profile,'cphone',array('style'=>'width:280px;margin:0 0 3px;'))?>
            <span class="alert">*</span>
            <br class="clearBoth" />

            <label class="inputLabel" for="Profile[address]">Адрес доставки:</label>
            <?=CHtml::activeTextField($profile,'address',array('style'=>'width:280px;'))?>
        </fieldset>
        <fieldset>
            <legend>Логин</legend>
            <label class="inputLabel" for="username">Логин:</label>
            <?=CHtml::activeTextField($model,'username',array('style'=>'width:280px;margin:0 0 3px;'))?>
            <span class="alert">*</span>
            <br class="clearBoth" />

            <label class="inputLabel" for="RegistrationForm[email]">E-mail:</label>
            <?=CHtml::activeTextField($model,'email',array('style'=>'width:280px;margin:0 0 3px;'))?>
            <span class="alert">*</span>
            <br class="clearBoth" />
        </fieldset>
        <?=CHtml::submitButton($model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save'))?>
        <?=CHtml::endForm()?>
    </td>   
</tr>
</table>