<?php

/**
 * This is the model class for table "requisites".
 *
 * The followings are the available columns in table 'requisites':
 * @property string $user_id
 * @property string $name_short
 * @property string $base
 * @property string $bank_account
 * @property string $current_account
 * @property string $correspondent_account
 * @property string $bik
 * @property string $ogrn
 * @property string $okpo
 */
class Requisites extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Requisites the static model class
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
		return 'requisites';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, name_short, base, bank_account, current_account, correspondent_account, bik, ogrn, okpo', 'required'),
			array('user_id, okpo', 'length', 'max'=>10),
			array('name_short', 'length', 'max'=>100),
			array('bank_account', 'length', 'max'=>100),
			array('base', 'length', 'max'=>50),
			array('current_account, correspondent_account', 'length', 'max'=>20),
			array('bik', 'length', 'max'=>9),
			array('ogrn', 'length', 'max'=>13),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, name_short, base, bank_account, current_account, correspondent_account, bik, ogrn, okpo', 'safe', 'on'=>'search'),
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
			'user_id' => 'Id пользователя',
			'name_short' => 'Сокращенное название',
			'base' => 'Основание лица уполномоченного на подписание договора',
			'bank_account' => 'Банк',
			'current_account' => 'Расчетный счет',
			'correspondent_account' => 'Корреспондентский счет',
			'bik' => 'БИК',
			'ogrn' => 'ОГРН',
			'okpo' => 'ОКПО',
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
		$criteria->compare('name_short',$this->name_short,true);
		$criteria->compare('base',$this->base,true);
		$criteria->compare('bank_account',$this->bank_account,true);
		$criteria->compare('current_account',$this->current_account,true);
		$criteria->compare('correspondent_account',$this->correspondent_account,true);
		$criteria->compare('bik',$this->bik,true);
		$criteria->compare('ogrn',$this->ogrn,true);
		$criteria->compare('okpo',$this->okpo,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}