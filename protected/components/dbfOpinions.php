<?php
/**
 * Виджет "Партнерство".
 * Работа со списком партнеров
 * 1. НЕзарегистрированный посетитель
 *       - Отображается список партнеров интересующей компании
 * 2. Владелец страницы
 *       - Отображается список заявок на П. с возможностью добавления в П./ удаления из П. / блокирования
 *       - Отображается список партнеров компании с возможностью удаления из П. / блокирования
 * 3. Зарегистрированный посетитель
 *       - Отображается список партнеров интересующей компании с возможностью добавления в П./ удаления из П.
 * 4. Ajax-запрос на добавление/удаление П.
 * ...
 * ...
 * ...
 */
class dbfOpinions extends CWidget {
	
    public $pageID = '';         # ID страницы пользователя / компании
    public $set    = false;      # Получение списка / установление значений (type::id_partner)
    public $pageNumber = 1;      # Запрашиваемая страница
    
    const MAXLETTERS   = 300;    # Максимальное количество символов в отзыве 
    const MAXCOUNT     = 20;     # Количество загружаемых отзывов (сортитовка по дате)
    
    public function run() {
        $cs = Yii::app()->clientScript;
        if ( !$cs->isCSSFileRegistered(Yii::app()->theme->baseUrl.'js/dbfOpinions.css', CClientScript::POS_HEAD) )
            $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/dbfOpinions.css');
        if ( !$cs->isScriptFileRegistered(Yii::app()->theme->baseUrl.'js/jquery.easySlider.js', CClientScript::POS_HEAD) )
            $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.easySlider.js', CClientScript::POS_HEAD);
        if ( !$cs->isScriptFileRegistered(Yii::app()->theme->baseUrl.'js/dbfOpinions.js') )
            $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/dbfOpinions.js');
        
        if (Yii::app()->request->isAjaxRequest) {#Обработка ajax-запроса (вызов из контроллера для рендеринга списка/обработки)
        # =======================================================================================
            if ($this->set) { # Установка значений + Отправка сообщений
                $set = explode('::', $this->set);
                if ( !empty($set[0]) and $set[1] ) { # type :: id/body
                    
                    switch ($set[0]) { # 'send' :: pageID
                        case 'send':
                            if (Yii::app()->user->getId()) {  # Отправка сообщения от зарегистрированного пользователя
                                if (!user::model()->exists('id=:pageID', array(':pageID'=>$this->pageID))) { # Существует ли пользователь-получатель
                                    $out = array('result'=>'errorRecipient');
                                } else {
                                    $opinion = Opinions::model()->find(
                                        'owner_id=:ownerID and recipient_id=:recipient_id', 
                                        array(':ownerID'=>Yii::app()->user->id, ':recipient_id'=>$this->pageID)
                                    ); 
                                    if ( !$opinion->id ) $opinion = new Opinions;
                    
                                    $opinion['recipient_id'] = Yii::app()->getModule('user')->user($this->pageID)->id; #->getModule('user')
                                    $opinion['owner_id'] = Yii::app()->user->id; #getId()
                                    $opinion['body'] = $set[1];
                                    $opinion['data'] = date("Y-m-d H:i:s");#die(print_r($opinion));
                                    if ($opinion->validate())
                                        if ($opinion->save()) $out = array('result'=>'success');
                                        else $out = array('result'=>'error');
                                    else
                                        $out = array('result'=>'errorValidate');
                                }
                            }
                            break;
                    }
                    die(json_encode($out));
                }
            } else { # Получение списка партнеров
            }
        } else {
            if (!empty($_POST['dbfOptionsPage'])) {
                $pageNumber = (int)$_POST['dbfOptionsPage'];
                # Условия разбиения
            } else{
                $opinions = Opinions::model()->findAll(array(
                    'condition' => 'recipient_id=:recipient_id',
                    'order'  => 'data DESC',
                    'params' => array(':recipient_id'=>$this->pageID),
                    'limit'  => self::MAXCOUNT,
                ));
            }
         if (Yii::app()->user->isGuest) {        #Форма для НЕзарегистрированного посетителя
        # =======================================================================================
            $this->render('dbfOpinionsMain',array(
                'opinions'  => $opinions, 
                'pageID'    => $this->pageID,
                'cntOpinion'=> Opinions::model()->countByAttributes(array('recipient_id'=>$this->pageID)),
                ));
        } elseif ( $this->pageID == Yii::app()->user->getId()) {    #Форма для владельца страницы
        # =======================================================================================    
            $this->render('dbfOpinionsMain',array(
                'opinions'  => $opinions, 
                'pageID'    => $this->pageID,
                'cntOpinion'=> Opinions::model()->countByAttributes(array('recipient_id'=>$this->pageID)),
                ));
        } else {                                        #Форма для зарегистрированного посетителя
        # =======================================================================================
            $this->render('dbfOpinionsUser', array(
                'pageID'    => $this->pageID,
                'maxLetters'=> self::MAXLETTERS,
                'opinion'   => Opinions::model()->findByAttributes(array('recipient_id'=>$this->pageID, 'owner_id'=>Yii::app()->user->id)),
                'widgetContent' => $this->render('dbfOpinionsMain',array(
                    'opinions'  => $opinions, 
                    'pageID'    => $this->pageID,
                    'cntOpinion'=> Opinions::model()->countByAttributes(array('recipient_id'=>$this->pageID)),
                ), true),
            ));
        }
        }
	}
}