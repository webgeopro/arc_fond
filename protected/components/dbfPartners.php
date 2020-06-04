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
 */
class dbfPartners extends CWidget {
	
    public $pageID = '';     # ID страницы пользователя / компании
    public $set    = false;  # Получение списка / установление значений (type::id_partner)
    
    public function run() {
        $cs = Yii::app()->clientScript;
        if ( !$cs->isCSSFileRegistered(Yii::app()->theme->baseUrl.'js/dbfPartners.css') )
            $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/dbfPartners.css', CClientScript::POS_HEAD);
        if ( !$cs->isScriptFileRegistered(Yii::app()->theme->baseUrl.'js/dbfPartners.js') )
            $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/dbfPartners.js');
        
        if (Yii::app()->request->isAjaxRequest) {#Обработка ajax-запроса (вызов из контроллера для рендеринга списка/обработки)
        # =======================================================================================

            if ($this->set) { # Установка значений + Отправка сообщений
                $set = explode('::', $this->set);
                if ( !empty($set[0]) and $set[1] ) {
                    #$partner = Partners::model()->findByPk($set[1]);
                    die('SET action');
                    if ( $partner['from_id'] != Yii::app()->user->getId() ) die; # Только ??? может редактировать
                    
                    switch ($set[0]) {
                        case ' ': # Отметить сообщение как прочитанное
                            break;
                    }
                    die(json_encode($out));
                }
            } else { # Получение списка партнеров
            }
        } elseif (Yii::app()->user->isGuest) {        #Форма для НЕзарегистрированного посетителя
        # =======================================================================================
            echo('Guest -Partner-');
        } elseif ( $this->pageID == Yii::app()->user->getId()) {    #Форма для владельца страницы
        # =======================================================================================    
            echo('Owner -Partner-');
            /*$partner = Partners::model()->findAll(array(
                
                'condition' => '',
                
            ));*/
        } else {                                        #Форма для зарегистрированного посетителя
        # =======================================================================================
            echo('User -Partner-');
        }
	}
}