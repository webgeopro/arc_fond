<?php
/**
 * Виджет "Назначить встречу". (Appointment)
 * Работа с календарем.
 * 1. Изменение информации
 *      1. Установка даты встречи
 *      2. Удаление назначенной встречи
 * 2. Получение информации
 *      1. Гость
 *      2. Зарегистрированный пользователь
 *      3. Владелец страницы
 *          1. Утверждение даты встречи
 *          2. Удаление назначенной встречи
 */
class dbfAppointment extends CWidget {
	
    public $pageID = '';    # ID страницы пользователя / компании
    public $set    = false; # Получение списка / установление значений (type::id_partner)
    public $quant  = 15;    # Квант времени в минутах (изменяется в настройках пользователя)
    
    public function run() {
        $cs = Yii::app()->clientScript;
        if ( !$cs->isCSSFileRegistered(Yii::app()->theme->baseUrl.'css/dbfAppointment.css') )
            $cs->registerCSSFile(Yii::app()->theme->baseUrl.'/css/dbfAppointment.css');
        if ( !$cs->isScriptFileRegistered(Yii::app()->theme->baseUrl.'js/dbfAppointment.js') )
            $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/dbfAppointment.js', CClientScript::POS_HEAD);
        
        if ( $this->pageID ) { # Инициализация продолжительности приема
            $ownerPage = User::model()->with('worktime')->findByPk($this->pageID);
            if ( $ownerPage->profile->quant ) {
                $this->quant = $ownerPage->profile->quant;
            }
        } else {die();}
        if (Yii::app()->request->isAjaxRequest) {#Обработка ajax-запроса (вызов из контроллера для рендеринга списка/обработки)
        # =======================================================================================
            if ($this->set) { # Установка значений + Установка времени и продолжительности встречи
                $set = explode('::', $this->set);
                if ( !empty($set[0]) and $set[1] ) { # type :: id/body
                    switch ($set[0]) {
                        case '----':
                            break;
                    }
                    die(json_encode($out));
                }
            } else {
                echo 'Ajax, но не set';
            }
        } else {
            $currentDay = Worktime::convert(date("Y-m-d"));#die(print_r($currentDay));
            $currentDay = array($ownerPage->worktime[$currentDay[0]], $ownerPage->worktime[$currentDay[1]]);
            $calendar = $this->render('dbfAppointmentMain',array(# Инициализация календаря (показывается всем)
                'pageID'=> $this->pageID,
                'quant' => $this->quant,
                'currentDay2'=> $currentDay,
                'currentDay'=> $this->getDay($currentDay),
            ));
            if (Yii::app()->user->isGuest) {              #Форма для НЕзарегистрированного посетителя
            # =======================================================================================
                echo 'Guest appointment. Просто отображения календаря с занятыми днями, без функционала'; 
            } elseif ( $this->pageID == Yii::app()->user->id ) {    #Форма для владельца страницы
            # =======================================================================================    
                echo 'Owner appointment';
            } else {                                        #Форма для зарегистрированного посетителя
            # =======================================================================================
                $this->render('dbfAppointmentUser',array(
                    'pageID'  => $this->pageID,
                    'calendar'=> $calendar,
                ));
            }
        }
	}
    
    /**
     * Получение "развертки дня" + наложение на него занятых промежутков:
     *   - Обеденный перерыв
     *   - Зарезервированное время
     *   - Забронированное время
     * @param $currentDay (array(день1, день2) - время начала и конца выбранного дня)
     * @return HTML (Сформатированное расписание дня)
     */ 
    public function getDay($currentDay)
    {
        if ($currentDay[0] AND $currentDay[1]){
            $temp1 = explode(':', $currentDay[0]);
            $temp2 = explode(':', $currentDay[1]);
            $time1 = $temp1[0]*60 + $temp1[1];
            $time2 = $temp2[0]*60 + $temp2[1];
            $diff = $time2 - $time1;
            if (0 >= $diff) die;
            $cntQuant = floor($diff / $this->quant);
            $quantPerHour = floor(60 / $this->quant); #die("\$quantPerHour=$quantPerHour  \$cntQuant=$cntQuant  {$this->quant}");
            
            // Цикл по квантам, в котором накладываем ограничения. (Берем из базы)
            
            for ($i=0, $color=1; $i<$cntQuant; $i++, $tmp=array()) {
                if ( !($i % $quantPerHour) ) { # Новый час
                    $color = -$color;
                }
                $hour = $temp1[0] + floor($i * $this->quant / 60); 
                $tmp['min'] = ($i * $this->quant) % 60;
                /* Установка статусов и ограничений */
                $tmp['status'] = 'dbfAppointmentFree'; // Сделать по умолчанию
                
                $arQuant[$hour][] = $tmp;
                $arQuant[$hour]['color'] = $color; // Не особо и требуется
            }
            
            
            return $arQuant;
        } else
            return null;
    }
}