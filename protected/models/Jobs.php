<?php

/**
 * This is the model class for table "jobs".
 *
 * The followings are the available columns in table 'jobs':
 * @property integer $id
 * @property integer $owner_id
 * @property double $salary
 * @property integer $exp
 * @property integer $male
 * @property integer $ageFrom
 * @property integer $ageTill
 * @property integer $education
 * @property integer $contact
 * @property integer $towns_id
 * @property integer $specialities_id
 */
class Jobs extends CActiveRecord
{
	public $userDate;
    public $exp = array(
        'не важно',
        'меньше года',
        'от года до трех',
        'больше трех лет',);
    public $education = array(
        'не важно',
        'среднее',
        'средне-специальное',
        'неокоченное высшее',
        'высшее',
        'высшее техническое',
        'магистратура',
        'аспирантура',
        'кандидат наук',);
    public $male = array(
        'не важно',
        'муж.',
        'жен.',);
    
    /**
	 * Returns the static model of the specified AR class.
	 * @return Jobs the static model class
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
		return 'jobs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('owner_id, exp, male, ageFrom, ageTill, education, contact, towns_id, specialities_id', 'numerical', 'integerOnly'=>true),
			array('salary', 'numerical'),
            array('comment', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, owner_id, salary, exp, male, ageFrom, ageTill, education, contact, towns_id, specialities_id', 'safe', 'on'=>'search'),
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
            'fk_towns' => array(self::BELONGS_TO, 'Towns', 'towns_id'),
            'fk_spec'  => array(self::BELONGS_TO, 'Specialities', 'specialities_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'owner_id' => 'Владелец',
			'salary' => 'Зарплата',
			'exp' => 'Опыт',
			'male' => 'Пол',
			'ageFrom' => 'Возраст от',
			'ageTill' => 'Возраст до',
			'education' => 'Образование',
			'contact' => 'Контактное лицо',
			'towns_id' => 'Населенный пункт',
			'specialities_id' => 'Специальность',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('salary',$this->salary);
		$criteria->compare('exp',$this->exp);
		$criteria->compare('male',$this->male);
		$criteria->compare('ageFrom',$this->ageFrom);
		$criteria->compare('ageTill',$this->ageTill);
		$criteria->compare('education',$this->education);
		$criteria->compare('contact',$this->contact);
		$criteria->compare('towns_id',$this->towns_id);
		$criteria->compare('specialities_id',$this->specialities_id);

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

	/**
	 * @return string the associated database table name
	 */
	public function _beforeValidate()
	{
		if ('string' == gettype($this->towns_id)){
            $town = Towns::model()->findByAttributes(array('name'=>$this->towns_id ));
            if (!empty($town['id'])) {
                $this->towns_id = $town['id']; 
            }
		}
	}
    
    /**
	 * @return string the associated database table name
	 */
	public function getTown($towns_id)
	{
		if ('string' == gettype($towns_id)){
            $town = Towns::model()->findByAttributes(array('name'=>$towns_id));
            if (!empty($town['id'])) {
                return $town['id']; 
            }
		}
        return $towns_id;
	}
    
    /**
	 * @return string the associated database table name
	 */
	public function getSpec($spec_id)
	{
		if ('string' == gettype($spec_id)){
            $spec = Specialities::model()->findByAttributes(array('name'=>$spec_id));
            if (!empty($spec['id'])) {
                return $spec['id']; 
            }
		}
        return $spec_id;
	}
}