<?php
/**
 * Виджет быстрых сообщений.
 * Определяет тип пользователя и перенаправляет вывод в соответствующие шаблоны:
 * 1. НЕзарегистрированный посетитель
 *       - Отображается форма для отправки сообщения (CAPTCHA, title, body)
 * 2. Владелец страницы
 *       - Отображается список сообщений с возможностью просмотра
 * 3. Зарегистрированный посетитель
 *       - Отображается форма для отправки сообщения (body)
 * 4. Ajax-запрос на получение/обработку сообщений
 *       - Выдается сформатированный список сообщений
 *       - Результат обработки запроса
 * 5. Ajax-запрос на отправку сообщений
 *       - Результат отправки сообщения
 */
class dbfMessages extends CWidget {
	
    public $pageID = '';         # ID страницы пользователя / компании
    public $messageType = 'all'; # Тип возвращаемого списка (Все;Непрочитанные;Избранные)
    public $set    = false;      # Получение списка / установление значений (type::id_message)
    public $messageBody = '';
    #public $captcha = array('registration'=>true);
    
    const UNREAD   = '0';
    const READ     = '1';
    const FAVORITE = '2';
    const FAVORITEUNREAD = '3';
    
    const MAXLETTERS = 400; # Ограничение на длину сообщения
    
    

    
    public function run() {
        $cs = Yii::app()->clientScript;
        if ( !$cs->isCSSFileRegistered(Yii::app()->theme->baseUrl.'js/dbfMessages.css', CClientScript::POS_HEAD) )
            $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/dbfMessages.css');
        if ( !$cs->isScriptFileRegistered(Yii::app()->theme->baseUrl.'js/dbfMessages.js') )
            $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/dbfMessages.js');
        if ( !$cs->isScriptFileRegistered(Yii::app()->theme->baseUrl.'js/jquery.hint.js') )
            $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.hint.js');
        
        if (Yii::app()->request->isAjaxRequest) {#Обработка ajax-запроса (вызов из контроллера для рендеринга списка/обработки)
        # =======================================================================================

            if ($this->set) { # Установка значений + Отправка сообщений
                $set = explode('::', $this->set);
                if ( !empty($set[0]) and $set[1] ) {
                    if ('send' == $set[0]) {
                        $message = new Messages;
                    } elseif ('getCnt' == $set[0]) {
                        die(Messages::model()->Count($this->getCondition('unread')));
                    } else {
                        $message = Messages::model()->findByPk($set[1]);
                        if ( $message['recipient_id'] != Yii::app()->user->getId() ) die; # Только получатель сообщения может редактировать
                    }
                    switch ($set[0]) {
                        case 'read': # Отметить сообщение как прочитанное
                            
                            if (self::FAVORITEUNREAD <= $message['status']) $message['status'] = self::FAVORITE;
                            else $message['status'] = $message['status'] + self::READ; # 2-Fav+Unread; 3-Fav+Read
                             
                            if ($message->save()) {
                                $out['result'] = 'success';
                                $out['cnt'] = Messages::model()->Count($this->getCondition('unread'));
                            }
                            break;
                        case 'favorite': # Отметить сообщение как избранное/ Удалить из избранного
                            #$message['status'] = $message['status'] % self::FAVORITE; # 0-Unread, 1-Read
                            switch ($message['status']) {
                                case self::FAVORITE:       $message['status'] = self::READ; break;
                                case self::FAVORITEUNREAD: $message['status'] = self::UNREAD; break;
                                case self::READ:           $message['status'] = self::FAVORITE; break;
                                default: #self::UNREAD
                                    $message['status'] = self::FAVORITEUNREAD;
                            }
                            if ($message->save()) {
                                $out['result'] = 'success';
                                $out['cnt'] = Messages::model()->Count($this->getCondition('favorite'));
                            }
                            break;
                        case 'send': # Отправить сообщение
                            if (!user::model()->exists('id=:pageID', array(':pageID'=>$this->pageID))) { # Существует ли пользователь-получатель
                                    $out = array('result'=>'errorRecipient');
                            } else {
                                $message['recipient_id'] = Yii::app()->getModule('user')->user($this->pageID)->id;
                                $message['owner_id'] = Yii::app()->user->getId()?Yii::app()->user->getId():0;
                                $message['body'] = $set[1];
                                $message['data'] = date("Y-m-d H:i:s");#die(print_r($message));
                                $verifyCode = trim($_POST["Messages"]["verifyCode"]); #die(print_r($message)."::".$verifyCode);
                                
                                if (!Yii::app()->user->getId()) { // Проверка CAPTCHA для НЕзарег. поль-ля
                                    $captcha=Yii::app()->getController()->createAction('captcha');
                                    if ($verifyCode !== $captcha->verifyCode) {
                                        die(json_encode(array('result'=>'errorCaptcha')));
                                    }
                                }
                                if ($message->validate())
                                    if ($message->save()) $out = array('result'=>'success');
                                    else $out = array('result'=>'error');
                                else
                                    $out = array('result'=>'errorValidate');
                            }
                            break;
                    }
                    die(json_encode($out));
                }
            } else { # Получение списка сообщений
                if ( Messages::model()->Count($this->getCondition($this->messageType)) )
                    $messages = Messages::model()->findAll($this->getCondition($this->messageType));
                else $messages = array();
                $this->render('dbfMessagesList', array(
                    'messages' => $messages,
                    'favorite' => self::FAVORITE,
                    'favoriteUnread' => self::FAVORITEUNREAD,
                    'unread' => self::UNREAD,
                    'read' => self::READ,
                ));
            }
        } elseif (Yii::app()->user->isGuest) {        #Форма для НЕзарегистрированного посетителя
        # =======================================================================================
            
            $this->render('dbfMessagesGuest', array(
                'pageID'    => $this->pageID,
                'maxLetters'=> self::MAXLETTERS,
                'model'     => new Messages,
            ));
        
        } elseif ( $this->pageID == Yii::app()->user->getId()) {    #Форма для владельца страницы
        # =======================================================================================    
            
            $this->render('dbfMessagesOwner', array(
                'cntAll'      => Messages::model()->Count($this->getCondition('all')),
                'cntUnread'   => Messages::model()->Count($this->getCondition('unread')),
                'cntFavorite' => Messages::model()->Count($this->getCondition('favorite')), 
                'pageID'   => $this->pageID,
            ));
        
        } else {                                        #Форма для зарегистрированного посетителя
        # =======================================================================================
            $this->render('dbfMessagesUser', array(
                'pageID'    => $this->pageID,
                'maxLetters'=> self::MAXLETTERS,
            ));
        }
	}
    
    /**
     * Формирование условий поиска для выбора непр, избр, всех сообщений
     */
    private function getCondition ($status='', $recipient=0)
    {   #todo НЕТ ЗАЩИТЫ ОТ ПРОСМОТРА НЕ ВЛАДЕЛЬЦЕМ ---ДОРАБОТАТЬ---
        $recipient = $recipient?$recipient:Yii::app()->user->getId();
        switch ($status) {
            case 'all':     $tmp = '';               break;
            case 'unread':  $tmp = "AND (status='".self::UNREAD."' OR status='".self::FAVORITEUNREAD."')"; break;
            case 'favorite':$tmp = "AND (status='".self::FAVORITE."' OR status='".self::FAVORITEUNREAD."')"; break;
            default:        $tmp = '';
        }
        return array(
                'condition' => "recipient_id=:recipient $tmp",
                'order'     => 'data DESC', 
                'params'    => array(':recipient'=>$recipient),
            );
    }
    
    /*public function filters() {
        return array(
            'accessControl',
        );
    }
    
    public function actions(){
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
            ),
        );
    }*/
}