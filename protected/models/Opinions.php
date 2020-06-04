<?php

/**
 * This is the model class for table "opinions".
 *
 * The followings are the available columns in table 'opinions':
 * @property integer $id
 * @property integer $owner_id
 * @property integer $recipient_id
 * @property string $body
 * @property string $data
 */
class Opinions extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Opinions the static model class
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
		return 'opinions';
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
			array('body', 'length', 'max'=>300),
			array('data', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, owner_id, recipient_id, body, data', 'safe', 'on'=>'search'),
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
			'owner_id' => 'Owner',
			'recipient_id' => 'Recipient',
			'body' => 'Body',
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
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('recipient_id',$this->recipient_id);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('data',$this->data,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
    public function getCount($id)
	{  
	    $row = $this::model()->findByPk($id);#die(print_r($row));
        if ($row->id)
            $cnt = $this::model()->countByAttributes(array('recipient_id'=>$row->recipient_id));#, 'owner_id'=>$row->owner_id
        else
            $cnt = 0;
        
        return $cnt;
    }
}