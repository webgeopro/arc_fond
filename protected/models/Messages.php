<?php

/**
 * This is the model class for table "messages".
 *
 * The followings are the available columns in table 'messages':
 * @property integer $id
 * @property integer $owner_id
 * @property integer $recipient_id
 * @property string $title
 * @property string $body
 * @property string $data
 * @property string $status
 */
class Messages extends CActiveRecord
{
    public $verifyCode; # Поле для CAPTCHA () для незарег. пользователя

    /**
	 * Returns the static model of the specified AR class.
	 * @return Messages the static model class
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
		return 'messages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('owner_id, recipient_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>100),
			array('body', 'length', 'max'=>400),
			array('status', 'length', 'max'=>1),
			array('data', 'safe'),
            // CAPTCHA для отправки сообщений
            array('verifyCode', 'captcha', 'allowEmpty'=>!Yii::app()->user->isGuest || !extension_loaded('gd'), 'on'=>'send'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, owner_id, recipient_id, title, body, data, status', 'safe', 'on'=>'search'),
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
			'id' => '№',
			'owner_id' => 'Отправитель',
			'recipient_id' => 'Получатель',
			'title' => 'Тема сообщения',
			'body' => 'Сообщение',
			'data' => 'Дата',
			'status' => 'Статус сообщения',
            'verifyCode' => 'Код проверки',
		);
	}
    
    public function safeAttributes()
    {
        return array(
            'send' => 'verifyCode, body, recipient_id',
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
		$criteria->compare('recipient_id',$this->recipient_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('data',$this->data,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}