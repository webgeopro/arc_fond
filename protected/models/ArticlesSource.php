<?php

/**
 * This is the model class for table "articles_source".
 *
 * The followings are the available columns in table 'articles_source':
 * @property string $id
 * @property string $article_id
 * @property string $owner_id
 * @property string $name
 * @property string $file
 */
class ArticlesSource extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ArticlesSource the static model class
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
		return 'articles_source';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('article_id, owner_id, name, file', 'required'),
			array('article_id, owner_id', 'length', 'max'=>10),
			array('name', 'length', 'max'=>100),
			array('file', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, article_id, owner_id, name, file', 'safe', 'on'=>'search'),
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
			'id'         => '№ записи',
			'article_id' => '№ статьи-владельца',
			'owner_id'   => 'Владелец статьи',
			'name'       => 'Название файла',
			'file'       => 'Имя файла',
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
		$criteria->compare('article_id',$this->article_id,true);
		$criteria->compare('owner_id',$this->owner_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('file',$this->file,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}