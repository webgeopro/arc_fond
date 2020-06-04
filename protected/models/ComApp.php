<?php

/**
 * This is the model class for table "com_app".
 *
 * The followings are the available columns in table 'com_app':
 * @property integer $id
 * @property integer $company_id
 * @property string $position
 * @property string $fname
 * @property string $sname
 * @property string $lname
 * @property string $phone
 * @property string $email
 * @property string $image
 */
class ComApp extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ComApp the static model class
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
			array('company_id', 'numerical', 'integerOnly'=>true),
			array('position', 'length', 'max'=>30),
			array('fname, sname', 'length', 'max'=>20),
			array('lname', 'length', 'max'=>25),
			array('phone', 'length', 'max'=>12),
			array('email, image', 'length', 'max'=>40),
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
			'id' => 'ID',
			'company_id' => 'Company',
			'position' => 'Position',
			'fname' => 'Fname',
			'sname' => 'Sname',
			'lname' => 'Lname',
			'phone' => 'Phone',
			'email' => 'Email',
			'image' => 'Image',
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
		$criteria->compare('company_id',$this->company_id);
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
}