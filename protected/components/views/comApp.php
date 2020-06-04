<?if (count($comPhoto) > 0):?>
<div id="comApp" >
    <div id="aComAppPrev">
        <a class="aScroll">
            <img src="<?=Yii::app()->theme->baseUrl?>/images/cab/comApp_arrow_l.png" width="29" height="30" />
        </a>
    </div>
    <div class="divComAppConner"></div>
    <div id="divComApp">
        <ul>
        <?foreach($comPhoto as $ph):?>
            <li>
                <div class="divComAppPhoto">
                    <div class="divComAppPhotoInner">
                        <div style="background:#efefef url('/uploads/users/<?=$pageID?>/com_app/<?=$ph['image']?>');"></div>
                    </div>
                </div>
                <div id="con_<?=$ph['id']?>" class="divComAppText">
                    <?=$ph['fname']?$ph['fname'].' ':''?><?=$ph['sname']?$ph['sname'].'<br />':''?>
                    <?=$ph['lname']?$ph['lname'].'<br /><br />':'<br />'?>
                    <?=$ph['position']?><br />
                    <?=$ph['phone']?'Тел.: '.$ph['phone'].'<br />':''?>
                    <?=$ph['email']?'E-mail: '.$ph['email'].'<br />':''?>
                    <?=$ph['icq']?'ICQ: '.$ph['icq'].'<br />':''?>
                    <?=$ph['skype']?'Skype: '.$ph['skype']:''?>
                    <br class="clear" />
                </div>
            </li>
        <?endforeach;?>
        </ul>
    </div>
    <a class="aScroll" id="aComAppNext">
        <img src="<?=Yii::app()->theme->baseUrl?>/images/cab/comApp_arrow_r.png" width="29" height="30" />
    </a>
    <div id="divComAppAll">
        <a href="" id="aComAppAll" name="<?=$pageID?>" class="aFormSend">Весь состав</a>
    </div>            
</div>
<?elseif($pageID == Yii::app()->user->getId()):?>
<div id="divComAppAll">
    <a href="" id="aComAppAll" name="<?=$pageID?>" class="aFormSend">Редактировать состав</a>
</div> 
<?endif;?>