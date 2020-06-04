<?php

/**
 * This is the model class for table "com_app".
 *
 * The followings are the available columns in table 'com_app':
 * @property integer $id
 * @property integer $owner_id
 * @property string $position
 * @property string $fname
 * @property string $sname
 * @property string $lname
 * @property string $phone
 * @property string $email
 * @property string $image
 */
class ComPersonal extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ComPersonal the static model class
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
		return 'com_app';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('owner_id', 'numerical', 'integerOnly'=>true),
			array('position', 'length', 'max'=>30),
			array('fname, sname', 'length', 'max'=>20),
			array('lname', 'length', 'max'=>25),
			array('phone', 'length', 'max'=>12),
			array('email, image', 'length', 'max'=>40),
            array('image', 'file', 'types'=>'jpeg, jpg, gif, png','allowEmpty'=>true),
            array('image', 'unsafe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, company_id, position, fname, sname, lname, phone, email, image', 'safe', 'on'=>'search'),
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
			'owner_id' => 'Владелец',
			'position' => 'Должность',
			'fname' => 'Имя',
			'sname' => 'Отчество',
			'lname' => 'Фамилия',
			'phone' => 'Тел.',
			'email' => 'E-mail',
			'image' => 'Фотография',
		);
	}
    
    public function safeAttributes()
    {
        return array(
            'image',
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
		$criteria->compare('owner_id',$this->company_id);
		$criteria->compare('position',$this->position,true);
		$criteria->compare('fname',$this->fname,true);
		$criteria->compare('sname',$this->sname,true);
		$criteria->compare('lname',$this->lname,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('image',$this->image,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

    /**
     * Удаление файлов изображений.
     */
    protected function afterDelete() 
    {
    	if($this->image) {
	        $img = 'uploads/users/'.$this->owner_id.'/com_app/'.$this->image;
	        $imgFull = 'uploads/users/'.$this->owner_id.'/com_app/full/'.$this->image;
	        #die("<br> $img <br> $imgFull");
	        if(file_exists($img)) unlink($img);
	        if(file_exists($imgFull)) unlink($imgFull);
    	}
        
        return true;
    }
    
    /**
     * Поведение для обработки фотографий. Создание уменьшенных изображений.
     */
    public function behaviors()
    {   #$userID = Yii::app()->user->getId();
        return array(
            'SImageUploadBehavior' => array(
                'class' => 'ext.SImageUploadBehavior.SImageUploadBehavior',
                'fileAttribute' => 'image',
                #'nameAttribute' => 'name',
                #'mkdir'         => true, Не применяется в моем расширении (Старое)
                'imagesRequired'=>array(
                    'thumb' => array('width'=>150,'height'=>198,'folder'=>'uploads/users/'.$this->owner_id.'/com_app'),
                    'full' => array('resize'=>false,'folder'=>'uploads/users/'.$this->owner_id.'/com_app/full'),
                    ),
            ),

        );
    }
}