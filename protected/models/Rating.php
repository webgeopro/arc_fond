<?php
#Good seller,highly recommended. A pleasure to do business with.
/**
 * This is the model class for table "rating".
 *
 * The followings are the available columns in table 'rating':
 * @property integer $id
 * @property integer $recipient_id
 * @property integer $owner_id
 * @property integer $rate
 * @property string  $data
 */
class Rating extends CActiveRecord
{
	const DATE_RATING_LIMIT = 14; # Голосование - раз в 2 недели
    public $avg;                  # Среднее значение
    
    /**
	 * Returns the static model of the specified AR class.
	 * @return Rating the static model class
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
		return 'rating';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('recipient_id, owner_id, rate', 'numerical', 'integerOnly'=>true),
			array('data', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, recipient_id, owner_id, rate, data', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'recipient_id' => 'Recipient',
			'owner_id' => 'Owner',
			'rate' => 'Rate',
			'data' => 'Data',
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
		$criteria->compare('recipient_id',$this->recipient_id);
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('rate',$this->rate);
		$criteria->compare('data',$this->data,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
    
    /**
	 * Проверяет возможно ли пользователю голосовать
	 * @return Boolean
	 */
    public function checkVoice($pageID)
    {
        // Сразу отсеиваем неавторизованных пользователеей и владельцев страницы
        if ( Yii::app()->user->isGuest or $pageID == Yii::app()->user->id ) return true;
        // Отсеиваем проголосовавших недавно (DATE_RATING_LIMIT)
        $rateExist = $this->model()->exists(array(
            'condition' => 'recipient_id=:pageID AND owner_id=:userID AND data>:date',
            'params' => array(':pageID'=>$pageID, ':userID'=>Yii::app()->user->id, ':date'=>date('Y-m-d', strtotime('-'.self::DATE_RATING_LIMIT.' days')),),
        ));
        if ($rateExist) return true;         
        // Оставшимся разрешаем голосовать
        return false;
    }
    
    /**
     * Получение рейтинга компании через ajax.
     */
    public function getRating($recipientID) 
    {
        $rating = $this::model()->find(array(
            'select'=>'AVG(rate) as avg',
            'condition'=>'recipient_id=:recipientID',
            'params'=>array(':recipientID' => $recipientID),
        ));
        
        return round($rating['avg']);
    }
    /**
     * Получение количества проголосовавших через ajax.
     */
    public function getCount($recipientID) 
    {
        $rating = $this::model()->count(array(
            'condition'=>'recipient_id=:recipientID',
            'params'=>array(':recipientID' => $recipientID),
        ));
        
        return $rating;
    }
    
}