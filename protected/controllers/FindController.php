<?php

class FindController extends Controller
{
	public $layout='//layouts/find'; // Главный шаблон для страницы поиска
    private $arTags = array(  // Список доступных тэгов для поиска
        'news',	'education', 'health', 'business', 'sport', 'bb', 'job', 'persons', 'culture', 'org', 'blogs',  
    );
    private $limits = 100; // Ограничения выборки
    
    public function actionIndex()
	{
		#$cs = Yii::app()->clientScript;
        
        if ( $this->isCorrectTag() ) { // -------1.1 Поиск в соответствие с тэгом.
            $data = $this->getData();
            //RENDER VIEW
        } else { // -----------------------------1.2 Поиск по умолчанию.
            
            die('Поиск по умолчанию');
        }
        $this->render('index', array('tag' => $tag, 'subCat' => $data['tag'][2], 'data' => $data));
	}

	/**
	 * Получение последних событий (новостей, вакансий и т.д.) из вкладок всех пользователей с учетом фильтров.
     * Обработка и форматирование.
     * 
     * @return array - массив отобранных событий
	 */
    public function getData()
	{
		$sql = $this->getSQLCode();
        
        // Обрабатываем -Подуровни тэга-
        // Разбивка полученных категорий по три в строке
        $subcats = $sql['tags'][2];
        if (count($subcats)) { // Подуровни есть. Разбиваем по 3 столбца
            $arSubCat = array();
            for ($i=0,$j=0,$cnt=count($subcats);$i< $cnt; $i++) {
                if (!($i % 3)) { // Начинаем новую строку
                    $j++;
                }
                $arSubCat[$j][] = array($subcats[$i]['id'],$subcats[$i]['name']);
            }
        } else { // Подуровней нет. Выводим сообщение??? 
            //@todo: Обработка действия при отсутствии подуровней.
        }
        
        // Обрабатываем -Результат поиска-
        // Разбивка полученных выборок (3шт.) на два столбца //Именованный набор из модели c учетом фильтров
        die(print_r($sql['activities']));
        
        $data['result']  = $arResult;
        $data['subcats'] = $arSubCat;
        return $data;
	}
    
    
    /**
	 * Формирование кода SQL
     * 
     * @return String
	 */
    public function getSQLCode()
	{
        $filters = $this->getFilters();
        // ????
        return $filters;
	}  
    
    /**
	 * Получение всех глобальных фильтров (город, тэг, )
     * 
     * @return array - массив накладываемых фильтров
	 */
    public function getFilters()
	{
        // Tag :: Берем из адресной строки
        $filters['tags'] = $this->getTag(); // Общий. Если есть activities, игнорируется!!!
        // Activity :: Берем из адресной строки
        $filters['activities'] = $this->getActivities();
        // -- Далее просто накладываемые фильтры -- //
		// Town :: берем из Cookie
        $filters['town'] = $this->getTown();
        // Name :: Берем из адресной строки???
        $filters['name'] = $this->getName();
        // Page :: Берем из адресной строки
        $filters['page'] = $this->getPage();
        
        return $filters;
	}
    
    /**
	 * Проверка корректности тэга в адресной строке
     * 
     * @return Boolean
	 */
    public function isCorrectTag()
	{
		$tag = trim($_GET['tags']);
        
        if (!empty($tag) AND in_array($tag, $this->arTags) )
            return true;
        else
            return false;
	}
    
    /**
	 * Получение тэга из адресной строки
     * 
     * @return array[???, array[String]] ([???, [Подуровень1, Подуровень2, Подуровень3, ...]]) 
	 */
    public function getTag()
	{
        $tag = trim($_GET['tags']);
        $activities = $this->getActivities();
        if ( $activities) { // Тэг и НЕ указана сфера деятельности сущ. Формируем запрос к БД.
            $tagSQL = $this->getActivitiesSQL($tag);
        } elseif ($this->isCorrectTag()) {
            $tagSQL = $this->getTagSQL($tag);
        } else {
            $tagSQL = null;
        }
        
        return $tagSQL;
	}
          
    /**
	 * Получение города.
     * Получение из куки, если есть в строке, выбирается и setTown. (???)
     * 
     * @return String
	 */
    public function getTown()
	{  $town = 'Выбран Town';
        // Читаем Qookie
        return $town;
	}  
    
    /**
	 * Установка города.
     * Cохраняется в куки.
     * 
     * @return Boolean
	 */
    public function setTown()
	{
        // Устанавливаем Qookie
        return true;
	} 
          
    /**
	 * Получение сфер дефтельности по которым производится поиск.
     * Формат Рубрика_Подрубрика / Рубрика
     * НОВЫЙ Формат Рубрика [int]
     * 
     * @return String
	 */
    public function getActivities()
	{   
        $activities = (int)trim($_GET['activity']);
        /*if (!empty($activities)) {
            $activities = explode('_', $activities, 3);
        } else {
            $activities = null;
        }*/
        
        return $activities;
	}     
          
    /**
	 * Получение имени из адресной строки
     * 
     * @return String
	 */
    public function getName()
	{  $name = 'Выбрано имя от балды';
        //Выбор из адресной строки text
        return $name;
	}
              
    /**
	 * Получение номера страницы
     * 
     * @return ??? {FROM X TO Y}
	 */
    public function getPage()
	{  $pages = 'FROM X TO Y';
        //Выбор из адресной строки page
        return $pages;
	}

              
    /**
	 * Получение SQL-кода для извлечения сфер деятельности
     * 
     * @return ??? 
	 */
    public function getActivitiesSQL($activities)
	{   $subcat = getSubcategories();
        $activities = 'activities';
        
        return $activities;
	}
        
    /**
	 * Получение ...
     * 
     * @param string - название тега
     * @return array - массив имен закладок
	 */
    public function getTagSQL($tag)
	{   $ids = array();
        switch ($tag) {
                case 'news':
                    $tag = 'news';
                    $ids = array(536, );
                    break;
                case 'education':
                    $tag = 'education';
                    break;
                case 'health':
                    $tag = 'health';
                    break;
                case 'business':
                    $tag = 'business';
                    break;
                case 'sport':
                    $tag = 'sport'; 
                    $ids = array(536, );
                    break;
                case 'bb':
                    $tag = 'bb';
                    break;
                case 'job':
                    $tag = 'job';
                    break;
                case 'persons':
                    $tag = 'persons';
                    break;
                case 'culture':
                    $tag = 'culture';
                    break;
                case 'org':
                    $tag = 'org';
                    break;
                case 'blogs':
                    $tag = 'blogs';
                    break;
                
                default:;
            }
        return array($tag, $ids, $this->getSubcategories($ids));
	}
              
    /**
	 * Получение номера страницы
     * 
     * @return array[String]
	 */
    public function getSubcategories($ids)
	{   //$subcategories = 'subcategories'; #die(print_r($ids));
        #$subcategories = Activity::model()->findAllByAttributes(array('root'=>$ids));
        $criteria = new CDbCriteria;
        $criteria->select = 'id, name';
        $criteria->condition = 'root IN ('.implode(',', $ids).')';
        #$criteria->addInCondition();
        $criteria->order = 'name ASC';
        //$criteria->params = array(':rootID' => $ids);
        
        $subcategories = Activity::model()->findAll($criteria); #die(CVarDumper::dump($subcategories));
        
        return $subcategories;
	}
}