<?php

/**
 * This is the model class for table "worktime".
 *
 * The followings are the available columns in table 'worktime':
 * @property string $user_id
 * @property string $mon1
 * @property string $mon2
 * @property string $tue1
 * @property string $tue2
 * @property string $wed1
 * @property string $wed2
 * @property string $thu1
 * @property string $thu2
 * @property string $fri1
 * @property string $fri2
 * @property string $sat1
 * @property string $sat2
 * @property string $sun1
 * @property string $sun2
 * @property string $din1
 * @property string $din2
 */
class Worktime extends CActiveRecord
{
	public $mon1 = '09:00';
	public $mon2 = '18:00';
	public $tue1 = '09:00';
	public $tue2 = '18:00';
	public $wed1 = '09:00';
	public $wed2 = '18:00';
	public $thu1 = '09:00';
	public $thu2 = '18:00';
	public $fri1 = '09:00';
	public $fri2 = '18:00';
	public $sat1 = '00:00';
	public $sat2 = '00:00';
	public $sun1 = '00:00';
	public $sun2 = '00:00';
	public $din1 = '13:00';
	public $din2 = '14:00';
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Worktime the static model class
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
		return 'worktime';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'length', 'max'=>10),
			array('mon1, mon2, tue1, tue2, wed1, wed2, thu1, thu2, fri1, fri2, sat1, sat2, sun1, sun2, din1, din2', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, mon1, mon2, tue1, tue2, wed1, wed2, thu1, thu2, fri1, fri2, sat1, sat2, sun1, sun2, din1, din2', 'safe', 'on'=>'search'),
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
            'worktime' => array(self::HAS_ONE, 'Worktime', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'ID',
			'mon1' => 'Mon1',
			'mon2' => 'Mon2',
			'tue1' => 'Tue1',
			'tue2' => 'Tue2',
			'wed1' => 'Wed1',
			'wed2' => 'Wed2',
			'thu1' => 'Thu1',
			'thu2' => 'Thu2',
			'fri1' => 'Fri1',
			'fri2' => 'Fri2',
			'sat1' => 'Sat1',
			'sat2' => 'Sat2',
			'sun1' => 'Sun1',
			'sun2' => 'Sun2',
			'din1' => 'Din1',
			'din2' => 'Din2',
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

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('mon1',$this->mon1,true);
		$criteria->compare('mon2',$this->mon2,true);
		$criteria->compare('tue1',$this->tue1,true);
		$criteria->compare('tue2',$this->tue2,true);
		$criteria->compare('wed1',$this->wed1,true);
		$criteria->compare('wed2',$this->wed2,true);
		$criteria->compare('thu1',$this->thu1,true);
		$criteria->compare('thu2',$this->thu2,true);
		$criteria->compare('fri1',$this->fri1,true);
		$criteria->compare('fri2',$this->fri2,true);
		$criteria->compare('sat1',$this->sat1,true);
		$criteria->compare('sat2',$this->sat2,true);
		$criteria->compare('sun1',$this->sun1,true);
		$criteria->compare('sun2',$this->sun2,true);
		$criteria->compare('din1',$this->din1,true);
		$criteria->compare('din2',$this->din2,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
    public function afterFind()
    {
		#die(print_r($this));
        /*foreach ($this->getIterator() as $key->$field) {
		  if ($key != 'user_id') {
		      $this->$key = date("H:i", $field);  
		  }
		}*/

        $this->mon1 = strftime("%H:%M", strtotime($this->mon1)); $this->mon2 = strftime("%H:%M", strtotime($this->mon2));
        $this->tue1 = strftime("%H:%M", strtotime($this->tue1)); $this->tue2 = strftime("%H:%M", strtotime($this->tue2));
        $this->wed1 = strftime("%H:%M", strtotime($this->wed1)); $this->wed2 = strftime("%H:%M", strtotime($this->wed2));
        $this->thu1 = strftime("%H:%M", strtotime($this->thu1)); $this->thu2 = strftime("%H:%M", strtotime($this->thu2));
        $this->fri1 = strftime("%H:%M", strtotime($this->fri1)); $this->fri2 = strftime("%H:%M", strtotime($this->fri2));
        $this->sat1 = strftime("%H:%M", strtotime($this->sat1)); $this->sat2 = strftime("%H:%M", strtotime($this->sat2));
        $this->sun1 = strftime("%H:%M", strtotime($this->sun1)); $this->sun2 = strftime("%H:%M", strtotime($this->sun2));
        $this->din1 = strftime("%H:%M", strtotime($this->din1)); $this->din2 = strftime("%H:%M", strtotime($this->din2));
    }
    
    /**
    ** Конвертирует день недели в название поля таблицы: понедельник -> array('mon1', 'mon2')
    */
    public function convert($date)
    {#die("date in  convert {$date}");
        $dayName = @strtolower(date("D", $date)); 
        if ($dayName)
            return array("{$dayName}1", "{$dayName}2");
        else 
            return null;
    }
    
    /**
     * Является ли день выходным
     * 
     * На данный момент день считается выходным, если длина рабочего дня равна 0
     * @param $worktime1 Начало рабочего дня
     * @param $worktime2 Конец рабочего дня
     * @result boolean 
     */
    public function isDayOff($worktime1, $worktime2) {
    	return $worktime1 == $worktime2 ? true : false;
    }
    
    public function beforeSave() {
    	// если выходной, зануляем начальное и кончное время
    	if(!empty($_POST['dayoff']) && is_array($_POST['dayoff'])) {
    		foreach($_POST['dayoff'] as $day => $v) {
		    	$this->setAttribute($day . '1', '00:00');
		    	$this->setAttribute($day . '2', '00:00');
    		}
    	}
    	return true;
    } 
}