<?php

/**
 * This is the model class for table "towns".
 *
 * The followings are the available columns in table 'towns':
 * @property string $id
 * @property string $root
 * @property string $lft
 * @property string $rgt
 * @property integer $level
 * @property integer $rubricator
 * @property string $name
 * @property string $comment
 */
class Towns extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Towns the static model class
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
		return 'towns';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'), // lft, rgt, level, rubricator, 
			array('level, rubricator', 'numerical', 'integerOnly'=>true),
			array('root, lft, rgt', 'length', 'max'=>10),
			array('name', 'length', 'max'=>100),
			array('comment', 'length', 'max'=>150),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, root, lft, rgt, level, rubricator, name, comment', 'safe', 'on'=>'search'),
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

    public function behaviors(){
        return array(
            'tree' => array(
                'class' => 'ext.yiiext.behaviors.model.trees.ENestedSetBehavior',
                // хранить ли множество деревьев в одной таблице
                'hasManyRoots' => true,
                // поле для хранения идентификатора дерева при $hasManyRoots=false; не используется
                'rootAttribute' => 'root',
                // обязательные поля для NS
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
            ),
        );
    }
    
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'root' => 'Root',
			'lft' => 'Lft',
			'rgt' => 'Rgt',
			'level' => 'Level',
			'rubricator' => 'Rubricator',
			'name' => 'Name',
			'comment' => 'Comment',
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
		$criteria->compare('root',$this->root,true);
		$criteria->compare('lft',$this->lft,true);
		$criteria->compare('rgt',$this->rgt,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('rubricator',$this->rubricator);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
    
    public function getAutoName() {
       return $this->name;# . ', ' . $this->Province;
    }

    /**
	 * @return string the associated database table name
	 */
	public function getTown($towns_id)
	{
		if ('string' == gettype($towns_id)){
            $town = Towns::model()->findByAttributes(array('name'=>$towns_id));
            if (!empty($town['id'])) {
                return $town['id']; 
            }
		}
        #return $towns_id;
        return null;
	}
	
	/**
	 * Вывод дочерних элементов дерева
	 * @param $current Идентификатор текущего узла (если нет или меньше нуля, выводятся корневые узлы)
     * @return Array 
	 */
	public function getChildren( $current = null )
	{
		if( $current >= 0) {
			$root = Towns::model()->findByPk($current);
			if($root) {
				$items = $root->children()->findAll(array('order'=>'priority DESC, name ASC'));						
			}
			else {
				return false;
			}
		}
		else {
			$items = Towns::model()->roots()->findAll(array('order'=>'priority DESC, name ASC'));				
		}
		return $items;
	}
}