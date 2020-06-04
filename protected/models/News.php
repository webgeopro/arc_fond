<?php

/**
 * This is the model class for table "news".
 *
 * The followings are the available columns in table 'news':
 * @property string $id
 * @property string $root
 * @property string $lft
 * @property string $rgt
 * @property integer $level
 * @property integer $rubricator_id
 */
class News extends CActiveRecord
{
	public $userDate;
    
    /**
	 * Returns the static model of the specified AR class.
	 * @return News the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'news';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			#array('lft, rgt, level', 'required'),
			#array('level', 'numerical', 'integerOnly'=>true),
			#array('root, lft, rgt', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, root, lft, rgt, level', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'rubricator' => array(self::BELONGS_TO, 'RubNews', 'rubricator_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'root' => 'Root',
			'lft' => 'Lft',
			'rgt' => 'Rgt',
			'level' => 'Level',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('root',$this->root,true);
		$criteria->compare('lft',$this->lft,true);
		$criteria->compare('rgt',$this->rgt,true);
		$criteria->compare('level',$this->level);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

    /**
	 * Обрабатывает дату каждой записи после find()
     * @return array 
	 */
    public function afterFind()
	{
		if($this->data) {
	        $interval = date_create()->diff(date_create($this->data));
	        if ( 1 < $interval->d ) {
	            $this->userDate = $this->dateFormat($this->data);
	        } else {
	            $this->userDate = $this->getDayAgo($this->data, $interval->d);
	        }
		}
		else {
			$this->userDate = '';
		}
    }  

	/**
	 * @return String Выводит дату в формате [2 Апр. 2009]
	 */	
    function dateFormat($string, $format="%e %b. %Y", $lang = 'ru')
    {
        
        if (substr(PHP_OS,0,3) == 'WIN') {
               $_win_from = array ('%e',  '%T',       '%D');
               $_win_to   = array ('%#d', '%H:%M:%S', '%m/%d/%y');
               $format = str_replace($_win_from, $_win_to, $format);
        }
        
        if($string != '') {
            $out = strftime($format, strtotime($string));
        } else {
            $out = '';
        }
        
        $strFrom = array(
                'january',      'jan',  
                'february',     'feb',  
                'march',        'mar',  
                'april',        'apr',  
                'may',          'may',  
                'june',        'jun',   
                'july',         'jul',  
                'august',       'aug',  
                'september',    'sep',  
                'october',      'oct',  
                'november',     'nov',  
                'december',     'dec',
                'monday',   
                'tuesday',  
                'wednesday',    
                'thursday', 
                'friday',   
                'saturday', 
                'sunday',
                'mon',
                'tue',
                'wed',
                'thu',
                'fri',
                'sat',
                'sun',          
            );
            $strTo = array('ru' => array(
                                'Январь',   'Янв',  
                                'Февраль',  'Фев',  
                                'Март',     'Мар',  
                                'Апрель',   'Апр',  
                                'Май',      'Май',  
                                'Июнь',     'Июн',  
                                'Июль',     'Июл',  
                                'Август',   'Авг',  
                                'Сентябрь', 'Сен',  
                                'Октябрь',  'Окт',
                                'Ноябрь',   'Ноя',  
                                'Декабрь',  'Дек',  
                                'Понедельник',
                                'Вторник',
                                'Среда',
                                'Четверг',
                                'Пятница',
                                'Суббота',
                                'Воскресенье',
                                'Пн',
                                'Вт',
                                'Ср',
                                'Чт',
                                'Пт',
                                'Сб',
                                'Вс',
                            )
                );
            
        $outOld = $out;
        
        $out = str_replace($strFrom, $strTo[$lang], strtolower($out));
        if ($out == strtolower($outOld)){
            $out = $outOld;
        }
        $out = str_replace('Май.', 'мая', $out);
//        return iconv("Windows-1251", "UTF-8", $out);
		return $out;        
    }
    
    /**
     * Переводим TIMESTAMP в формат вида: 5 дн. назад
     * или 1 мин. назад и тп.
     *
     * @param unknown_type $date_time
     * @return unknown
     */
    function getTimeAgo($date_time)
    {
        $timeAgo = time() - strtotime($date_time);
        $timePer = array(
            'day'   => array(3600 * 24, 'дн.'),
            'hour'  => array(3600, ''),
            'min'   => array(60, 'мин.'),
            'sek'   => array(1, 'сек.'),
            );
        foreach ($timePer as $type =>  $tp) {
            $tpn = floor($timeAgo / $tp[0]);
            if ($tpn){
                
                switch ($type) {
                    case 'hour':
                        if (in_array($tpn, array(1, 21))){
                            $tp[1] = 'час';
                        }elseif (in_array($tpn, array(2, 3, 4, 22, 23)) ) {
                            $tp[1] = 'часa';
                        }else {
                            $tp[1] = 'часов';
                        }
                        break;
                }
                return $tpn.' '.$tp[1].' назад';
            }
        }
    }

    /**
     * Переводим DATE в формат вида: вчера, сегодня
     *
     * @param unknown_type $date_time
     * @return unknown
     */
    function getDayAgo($date_time, $day)
    {
        switch ($day) {
            case 0:
                $out = 'Сегодня'; break;
            case 1:
                $out = 'Вчера'; break;
            case 2:
                $out = 'Позавчера'; break;
        }
        return $out;
    }
        
    
    
}