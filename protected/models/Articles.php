<?php

/**
 * This is the model class for table "articles".
 *
 * The followings are the available columns in table 'articles':
 * @property string $id
 * @property integer $rubricator_id
 * @property string $owner_id
 * @property string $articles_source_id
 * @property string $data
 * @property string $title
 * @property string $descr
 * @property string $author
 */
class Articles extends CActiveRecord
{
	public $userDate;
    
    /**
	 * Returns the static model of the specified AR class.
	 * @return Articles the static model class
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
		return 'articles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rubricator_id', 'numerical', 'integerOnly'=>true),
			array('owner_id, articles_source_id', 'length', 'max'=>10),
			array('title', 'length', 'max'=>300),
			array('author', 'length', 'max'=>255),
			array('data, descr', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, rubricator_id, owner_id, articles_source_id, data, title, descr, author', 'safe', 'on'=>'search'),
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
            'rubricator' => array(self::BELONGS_TO, 'RubArticles', 'rubricator_id'),
            'source'     => array(self::HAS_MANY, 'ArticlesSource', 'article_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'rubricator_id' => 'Rubricator',
			'owner_id' => 'Owner',
			'articles_source_id' => 'Articles Source',
			'data' => 'Data',
			'title' => 'Title',
			'descr' => 'Descr',
			'author' => 'Author',
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
		$criteria->compare('rubricator_id',$this->rubricator_id);
		$criteria->compare('owner_id',$this->owner_id,true);
		$criteria->compare('articles_source_id',$this->articles_source_id,true);
		$criteria->compare('data',$this->data,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('descr',$this->descr,true);
		$criteria->compare('author',$this->author,true);

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
       $interval = date_create()->diff(date_create($this->data));
        if ( 1 < $interval->d ) {
            $this->userDate = $this->dateFormat($this->data);
        } else {
            $this->userDate = $this->getDayAgo($this->data, $interval->d);
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
}