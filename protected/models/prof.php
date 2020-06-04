<?php

/**
 * This is the model class for table "tbl_profiles".
 *
 * The followings are the available columns in table 'tbl_profiles':
 * @property integer $user_id
 * @property string $cface
 * @property string $address
 * @property string $cphone
 * @property string $fax
 * @property integer $inn
 * @property integer $kpp
 * @property string $orgname
 * @property string $address0
 * @property string $orgdate
 * @property string $site
 * @property integer $form
 * @property integer $town
 * @property integer $town0
 * @property integer $index
 * @property integer $index0
 */
class prof extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return prof the static model class
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
		return 'tbl_profiles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, orgdate, site, form, town, town0, index, index0', 'required'),
			array('user_id, inn, kpp, form, town, town0, index, index0', 'numerical', 'integerOnly'=>true),
			array('cface', 'length', 'max'=>100),
			array('address, address0', 'length', 'max'=>255),
			array('cphone, fax', 'length', 'max'=>20),
			array('orgname', 'length', 'max'=>50),
			array('site', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, cface, address, cphone, fax, inn, kpp, orgname, address0, orgdate, site, form, town, town0, index, index0', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'cface' => 'Cface',
			'address' => 'Address',
			'cphone' => 'Cphone',
			'fax' => 'Fax',
			'inn' => 'Inn',
			'kpp' => 'Kpp',
			'orgname' => 'Orgname',
			'address0' => 'Address0',
			'orgdate' => 'Orgdate',
			'site' => 'Site',
			'form' => 'Form',
			'town' => 'Town',
			'town0' => 'Town0',
			'index' => 'Index',
			'index0' => 'Index0',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('cface',$this->cface,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('cphone',$this->cphone,true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('inn',$this->inn);
		$criteria->compare('kpp',$this->kpp);
		$criteria->compare('orgname',$this->orgname,true);
		$criteria->compare('address0',$this->address0,true);
		$criteria->compare('orgdate',$this->orgdate,true);
		$criteria->compare('site',$this->site,true);
		$criteria->compare('form',$this->form);
		$criteria->compare('town',$this->town);
		$criteria->compare('town0',$this->town0);
		$criteria->compare('index',$this->index);
		$criteria->compare('index0',$this->index0);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}