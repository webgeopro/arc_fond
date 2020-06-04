<?php

/**
 * This is the model class for table "tabs".
 *
 * The followings are the available columns in table 'tabs':
 * @property integer $id
 * @property integer $owner_id
 * @property string $name
 * @property string $txt
 * @property string $data
 */
class Tabs extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Tabs the static model class
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
		return 'tabs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, owner_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('txt, data', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, owner_id, name, txt, data', 'safe', 'on'=>'search'),
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
			'id'       => '№',
			'owner_id' => 'Владелец',
			'name'     => 'Название закладки',
			'txt'      => 'Содержимое',
			'data'     => 'Дата сохранения',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('txt',$this->txt,true);
		$criteria->compare('data',$this->data,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}