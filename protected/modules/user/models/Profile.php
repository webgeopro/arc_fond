<?php

class Profile extends UActiveRecord
{
	/**
	 * The followings are the available columns in table 'profiles':
	 * @var integer $user_id
	 * @var boolean $regMode
	 */
	public $regMode = false;
	
	private $_model;
	private $_modelReg;

	/**
	 * @deprecated Заменено на $cface_fname, $cface_sname, $cface_lname
	 */ 
    public $cfaceIp;   # Заглушка для формы Физ.лицо, контактное лицо
    
//    public $cface_fname;
//    public $cface_sname;
//    public $cface_lname;
    
//    public $addressIp; # Заглушка для формы Физ.лицо, адрес доставки
//    public $cphoneIp;  # Заглушка для формы Физ.лицо, контактный телефон
//    public $dosType;   # Используется при формировании типа доставки в корзине
//    public $comment;   # Используется при формировании комментария к доставке в корзине
//    public $opType;    # Используется при формировании типа оплаты в корзине
//    
//    public $index0;
//    public $index;
    
    public $form = array(    # Формы собственности
        array('', 'Частное лицо'),
        array('ООО', 'Общество с ограниченной ответственностью'),
        array('ИП', 'Индивидуальный предприниматель'),
        array('ОАО', 'Открытое акционерное общество'),
        array('ЗАО', 'Закрытое акционерное общество'),
        array('Некоммерческая организация', 'Некоммерческая организация'),
        array('ОДО', 'Общество с дополнительной ответственностью'),
        array('ПК', 'Производственный кооператив'),
        array('ПТ', 'Полное товарищество'),
        array('КТ', 'Коммандитное товарищество'),
        array('ГУП', 'Государственное унитарное предприятие'),
        array('МУП', 'Муниципальное унитарное предприятие'),
        array('ОО', 'Общественная организация'),
    );
    public $jurForm = array(    # Формы собственности
        '1'=>'ООО, Общество с ограниченной ответственностью',
        '2'=>'ИП, Индивидуальный предприниматель',
        '3'=>'ОАО, Открытое акционерное общество',
        '4'=>'ЗАО, Закрытое акционерное общество',
        '5'=>'ОО, Общественная организация',
        '6'=>'ОДО, Общество с дополнительной ответственностью',
        '7'=>'ПК, Производственный кооператив',
        '8'=>'ПТ, Полное товарищество',
        '9'=>'КТ, Коммандитное товарищество',
        '10'=>'ГУП, Государственное унитарное предприятие',
        '11'=>'МУП, Муниципальное унитарное предприятие',
        '12'=>'ОО, Общественная организация',
    );	
	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
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
		return Yii::app()->getModule('user')->tableProfiles;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$required = array();
		$numerical = array();		
		$rules = array();
		$model=$this->getFields();
		
		foreach ($model as $field) {
			$field_rule = array();
			if ($field->required==ProfileField::REQUIRED_YES_NOT_SHOW_REG||$field->required==ProfileField::REQUIRED_YES_SHOW_REG)
				array_push($required,$field->varname);
			if ($field->field_type=='FLOAT'||$field->field_type=='INTEGER')
				array_push($numerical,$field->varname);
			if ($field->field_type=='VARCHAR'||$field->field_type=='TEXT') {
				$field_rule = array($field->varname, 'length', 'max'=>$field->field_size, 'min' => $field->field_size_min);
				if ($field->error_message) $field_rule['message'] = UserModule::t($field->error_message);
				array_push($rules,$field_rule);
			}
			if ($field->other_validator) {
				if (strpos($field->other_validator,'{')===0) {
					$validator = (array)CJavaScript::jsonDecode($field->other_validator);
					$field_rule = array($field->varname, key($validator));
					$field_rule = array_merge($field_rule,(array)$validator[key($validator)]);
				} else {
					$field_rule = array($field->varname, $field->other_validator);
				}
				if ($field->error_message) $field_rule['message'] = UserModule::t($field->error_message);
				array_push($rules,$field_rule);
			} elseif ($field->field_type=='DATE') {
				$field_rule = array($field->varname, 'type', 'type' => 'date', 'dateFormat' => 'yyyy-mm-dd', 'allowEmpty'=>true);
				if ($field->error_message) $field_rule['message'] = UserModule::t($field->error_message);
				array_push($rules,$field_rule);
			}
			if ($field->match) {
				$field_rule = array($field->varname, 'match', 'pattern' => $field->match);
				if ($field->error_message) $field_rule['message'] = UserModule::t($field->error_message);
				array_push($rules,$field_rule);
			}
			if ($field->range) {
				$field_rule = array($field->varname, 'in', 'range' => self::rangeRules($field->range));
				if ($field->error_message) $field_rule['message'] = UserModule::t($field->error_message);
				array_push($rules,$field_rule);
			}
		}
		
		array_push($rules,array(implode(',',$required), 'required'));
		array_push($rules, array('orgname address address0 inn cphone form index0 index town0 town', 'required')); 
		array_push($rules,array(implode(',',$numerical), 'numerical', 'integerOnly'=>true));
		
        #array_push($rules, array(
			#array_push($rules, array('user_id', 'required')); #, orgdate, site, form, town, town0, index, index0
			array_push($rules, array('user_id, inn, kpp, form, town, town0, index, index0', 'numerical', 'integerOnly'=>true)); #kpp,
			array_push($rules, array('cface', 'length', 'max'=>100));
//			array_push($rules, array('cface_lname', 'length', 'max'=>80));
//			array_push($rules, array('cface_fname', 'length', 'max'=>50));
//			array_push($rules, array('cface_sname', 'length', 'max'=>50));
			array_push($rules, array('address, address0', 'length', 'max'=>255));
			array_push($rules, array('cphone, fax', 'length', 'max'=>20));
			array_push($rules, array('orgname', 'length', 'max'=>100));
			array_push($rules, array('site', 'length', 'max'=>40));
            array_push($rules, array('activities', 'length', 'max'=>200));
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array_push($rules, array('user_id, cface, address, cphone, fax, inn, kpp, orgname, address0, orgdate, site, form, town, town0, index, index0, activities', 'safe', 'on'=>'search'));
            // Для изображений
            array_push($rules, array('image', 'file', 'types'=>'jpeg, jpg, gif, png','allowEmpty'=>true));
            array_push($rules, array('image', 'unsafe'));
        #));
        return $rules;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		$relations = array(
			'user' => array(self::HAS_ONE, 'User', 'user_id'),
            'requisites' => array(self::HAS_ONE, 'Requisites', 'user_id'), # Банковские реквизиты
            //'worktime' => array(self::HAS_ONE, 'Worktime', 'user_id'),
            'fk_town'  => array(self::BELONGS_TO, 'Towns', 'town'),
            'fk_town0' => array(self::BELONGS_TO, 'Towns', 'town0'),
        );
		if (isset(Yii::app()->getModule('user')->profileRelations)) $relations = array_merge($relations,Yii::app()->getModule('user')->profileRelations);
		return $relations;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
//		$labels = array(
//			'user_id' => UserModule::t('User ID'),
//		);
//		$model=$this->getFields();
//		
//		foreach ($model as $field)
//			$labels[$field->varname] = UserModule::t($field->title);
//		return $labels;
		return array(
    		'orgname' => 'Наименование',
    		'address0' => 'Адрес', // юридический
    		'address' => 'Адрес', // физический
    		'inn' => 'ИНН',
    		'kpp' => 'КПП',
    		'cphone' => 'Телефон / факс',
    		'form' => 'Форма собственности',
    		'town0' => 'Город',
    		'town' => 'Город',
    		'index0' => 'Индекс',
    		'index' => 'Индекс',
    		'activities' => 'Сфера деятельности',
    	);
			
	}
	
	private function rangeRules($str) {
		$rules = explode(';',$str);
		for ($i=0;$i<count($rules);$i++)
			$rules[$i] = current(explode("==",$rules[$i]));
		return $rules;
	}
	
	static public function range($str,$fieldValue=NULL) {
		$rules = explode(';',$str);
		$array = array();
		for ($i=0;$i<count($rules);$i++) {
			$item = explode("==",$rules[$i]);
			if (isset($item[0])) $array[$item[0]] = ((isset($item[1]))?$item[1]:$item[0]);
		}
		if (isset($fieldValue)) 
			if (isset($array[$fieldValue])) return $array[$fieldValue]; else return '';
		else
			return $array;
	}
	
	public function widgetAttributes() {
		$data = array();
		$model=$this->getFields();
		
		foreach ($model as $field) {
			if ($field->widget) $data[$field->varname]=$field->widget;
		}
		return $data;
	}
	
	public function widgetParams($fieldName) {
		$data = array();
		$model=$this->getFields();
		
		foreach ($model as $field) {
			if ($field->widget) $data[$field->varname]=$field->widgetparams;
		}
		return $data[$fieldName];
	}
	
	public function getFields() {
		if ($this->regMode) {
			if (!$this->_modelReg)
				$this->_modelReg=ProfileField::model()->forRegistration()->findAll();
			return $this->_modelReg;
		} else {
			if (!$this->_model)
				$this->_model=ProfileField::model()->forOwner()->findAll();
			return $this->_model;
		}
	}


    public function safeAttributes()
    {
        return array(
		  'cphone', 'fax', 'site', 'form',
          'index', 'index0', 'address0', 'address', 'town', 'town0', 'activities',
          'image',
        );
    }
    
    public function beforeSave() {
    	/*if($this->orgname) {
	    	$orgname = trim($this->orgname);
	    	if(substr($orgname, 0, 1) == '"') {
	    		$orgname = substr($orgname, 1);
	    	}
	    	if(substr($orgname, -1) == '"') {
	    		$orgname = substr($orgname, 0, -1);
	    	}
	    	$this->orgname = trim($orgname);
    	}*/
    	return true;
    }
    
    public function afterSave()
    {
        $worktime = Worktime::model()->findByPk($this->user_id);
        if (!$worktime->user_id) {
            $worktime = new Worktime;
            $worktime->user_id = $this->user_id;
        }
        $worktime->attributes = $_POST; #['worktime']
        if ($worktime->validate() ){
            $worktime->save();
        } #selse die($worktime->errors);
        $req = Requisites::model()->findByPk($this->user_id);
        if (!$req->user_id) {
            $req = new Requisites;
            $req->user_id = $this->user_id;
        }
        $req->attributes = $_POST['Requisites'];
        if ($req->validate() ){
            $req->save();
        }
    }

    /**
     * Поведение для обработки фотографий. Создание уменьшенных изображений.
     */
    public function behaviors()
    {
        return array(
            'SImageUploadBehavior' => array(
                'class' => 'ext.SImageUploadBehavior.SImageUploadBehavior',
                'fileAttribute' => 'image',
                #'nameAttribute' => 'name',
                'imagesRequired'=>array(
                    //'thumb' => array('width'=>50,'height'=>37,'folder'=>'uploads/logo/thumb'),
                    'middle' => array('width'=>210,'height'=>134,'folder'=>'uploads/logo'),
                    'full' => array('resize'=>false,'folder'=>'uploads/logo/full'),
                    ),
            ),
        );
    }    
}