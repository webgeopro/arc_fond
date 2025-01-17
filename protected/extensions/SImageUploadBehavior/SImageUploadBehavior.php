<?php

class SImageUploadBehavior extends CActiveRecordBehavior {
	
	public $fileAttribute;
	public $nameAttribute;
	public $folder = 'uploads';
	
	public $imagesRequired;
	
	public function beforeSave($event) {
				
		if(!$this->fileAttribute){
			throw new CHttpException(500,'"fileAttribute" должен быть установлен!');
		}
		if(!$this->imagesRequired){
			throw new CHttpException(500,'"imagesRequired" должен(ны)быть установлен!');
		}
		
		
		$model = $this->getOwner();
		$file = CUploadedFile::getInstance($model, $this->fileAttribute);
		$fileAttribute = $this->fileAttribute;
		
		//Если файл не был загружен, поле с файлом обновлять не нужно.
		if(is_null($file)){
			unset($model->image);
			return;
		}elseif(!$model->isNewRecord && !empty($model->$fileAttribute)){
			$this->deleteImages();
		}
		
		//Имя будущего изображения
		if($this->nameAttribute){
			//Дата на случай, если такое имя уже есть
			$fileName =	date('dMY_H-i-s').$this->safeFileName($model->name).'.'.$file->getExtensionName();
		}else{
			$fileName = date('dMY_H-i-s').$this->safeFileName($file->getName()).'.'.$file->getExtensionName();
		}
		
		
		$model->$fileAttribute = $fileName;
		
		Yii::import('ext.helpers.CArray');
		Yii::import('ext.image.Image');
		
		if(!is_array(reset($this->imagesRequired))){
			$this->imagesRequired['fileName'] = $fileName;
			$this->manipulate($file, $this->imagesRequired);
		}
		else{
			foreach($this->imagesRequired as $imageRequired){
				$imageRequired['fileName'] = $fileName;
				$this->manipulate($file, $imageRequired);
			}
		}
		
	}
	//Абсолютный путь к изображеию
	private static function getAbsolutePath($folder, $fileName = null){
		return
		Yii::app()->basePath.'/../'.	//Путь к корню приложения
		$folder.'/'.	//Папка из конфигурации
		$fileName;
	}
	
	//Получение ссылки на изображение
	public function getImageUrl($image = null){
		$model = $this->getOwner();
		if(!is_array(reset($this->imagesRequired))){
			$folder = $this->imagesRequired['folder'];	
		}else{
			$folder = $this->imagesRequired[$image]['folder'];
		}
		$fileAttribute = $this->fileAttribute;
		return Yii::app()->baseUrl.'/'.$folder.'/'.$model->$fileAttribute;
	}
		
	
	//Удаление всех копий файла для текущей модели
	public function deleteImages(){
		$fileAttribute = $this->fileAttribute;
		$model = $this->getOwner();
		if ( $model->$fileAttribute ) { // иначе пытается снести всю папку comApp
			if(!is_array(reset($this->imagesRequired))){
				$imagePath = self::getAbsolutePath($this->imagesRequired['folder'], $model->$fileAttribute);
				if(file_exists($imagePath)) unlink($imagePath);
			}
			else{
				foreach($this->imagesRequired as $imageRequired){
					$imagePath = self::getAbsolutePath($imageRequired['folder'], $model->$fileAttribute);
					if(file_exists($imagePath)) unlink($imagePath);				
				}
			}	
		}
	}
	
	//Создание изображения на основе параметров
	private function manipulate($file, $options){
		//Первым делом валидация
		$this->validateOptions($options);
		
		$targetFolder = $options['folder'];
		$fileName = $options['fileName'];

		//Путь будущего изображения
		$path = self::getAbsolutePath($targetFolder ,$fileName);
		
		//Если изменять размеры не нужно - просто сделаем копию изображения
		if(isset($options['resize'])){
			if($options['resize'] === false){
				copy($file->getTempName(), $path);
				return;
			}
		}
		
		//Ширина и высота требуемого изображения
		$targetWidth = $options['width'];
		$targetHeight = $options['height'];
		//Ширина и высота загруженного изображения
		list($uploadedWidth, $uploadedHeight) = getimagesize($file->getTempName());
		
		//Если изменять размеры не нужно - просто сделаем копию изображения
		if(isset($options['smartResize'])){
			if($options['smartResize'] === false){
				//Если требуемое изображение больше загруженного, его не нужно изменять
				if($targetWidth>$uploadedWidth && $targetHeight>$uploadedHeight){
					copy($file->getTempName(), $path);
				}
				else{
					//Изображение для манипуляции берется из временной папки
					$image = new Image($file->getTempName());
					//Манипуляция
					$image->resize($targetWidth, $targetHeight, Image::AUTO)->sharpen(1)->quality(95)->save($path);
				}
				return;
			}
		}

		//Отношение сторон загруженного и требуемого изображения
		$uploadedRatio = $uploadedWidth/$uploadedHeight;
		$targetRatio = $targetWidth/$targetHeight;

		//Сравниваем отношения и считаем координаты для кадрирования(если нарисовать на бумаге алгоритм становится очевидным :))
		if($uploadedRatio>$targetRatio){
			$cropHeight	= $uploadedHeight;
			$cropWidth	= $uploadedHeight*$targetRatio;
			$cropLeft	= ($uploadedWidth - $uploadedHeight*$targetRatio)*0.5;
			$cropTop	= 0;
		}
		else{
			$cropHeight	= $uploadedWidth/$targetRatio;
			$cropWidth	= $uploadedWidth;
			$cropLeft	= 0;
			$cropTop	= ($uploadedHeight - $uploadedWidth/$targetRatio)*0.2;
		}
		//Изображение для манипуляции берется из временной папки
		$image = new Image($file->getTempName());
		//Манипуляция
		
        $image->crop($cropWidth, $cropHeight, $cropTop, $cropLeft)
				->resize($targetWidth, $targetHeight, Image::NONE)
				->sharpen(1)->quality(95)->save($path);
        

	}
	
	public function beforeDelete(){
		$this->deleteImages();
	}
	
	//Валидация опций создаваемого изображения
	private function validateOptions($options){
		if(!is_array($options))
			throw new CHttpException(500,'Конфигурацией изображения должен быть массив');
		if(!isset($options['folder']))
			throw new CHttpException(500,'Папка для загрузки не установлена');
		if(isset($options['resize']) && $options['resize']===false) return;
		if(!isset($options['width']) || !isset($options['height']))
			throw new CHttpException(500,'Параметры изображений установлены неправильно');
	}
	
	//У файлов должны быть безопасные имена
	private function safeFileName($string) {
		$converter = array(
			'а' => 'a',   'б' => 'b',   'в' => 'v',
			'г' => 'g',   'д' => 'd',   'е' => 'e',
			'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
			'и' => 'i',   'й' => 'y',   'к' => 'k',
			'л' => 'l',   'м' => 'm',   'н' => 'n',
			'о' => 'o',   'п' => 'p',   'р' => 'r',
			'с' => 's',   'т' => 't',   'у' => 'u',
			'ф' => 'f',   'х' => 'h',   'ц' => 'c',
			'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
			'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
			'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

			'А' => 'A',   'Б' => 'B',   'В' => 'V',
			'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
			'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
			'И' => 'I',   'Й' => 'Y',   'К' => 'K',
			'Л' => 'L',   'М' => 'M',   'Н' => 'N',
			'О' => 'O',   'П' => 'P',   'Р' => 'R',
			'С' => 'S',   'Т' => 'T',   'У' => 'U',
			'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
			'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
			'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
			'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
		);
		$str = strtr($string, $converter);
		$str = strtolower($str);
		$str = preg_replace('~[^-a-z0-9_]+~u', '_', $str);
		$str = trim($str, "-");
		
		return $str;
	}

	
}
