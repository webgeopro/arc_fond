<?php
/**
 * JLoremIpsum class file.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @license BSD
 */

/**
 * This widget encapsulates the Lorem-jQuery plugin.
 * The plugin will generate Lorem Ipsum text for use as dummy texts on webpages.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 */

class JLoremIpsum extends CWidget
{
	/**
	 * @var string Allows you to specify what kind of result you want to see.
     * You may use: 'paragraphs', 'words' or 'characters'
	 * Defaults to 'paragraphs'.
	 */
	public $type;
	/**
	 * @var integer Allows you specify an amount. E.g. 5 paragraphs.
	 * Defaults to 3.
	 */
	public $amount;
	/**
	 * @var boolean Allows you to add <p> around each paragraph.
	 * Defaults to true.
	 */
	public $ptags;

	/**
	 * Initializes the widget.
	 * This method registers all needed client scripts 
	 */
	public function init()
	{
  		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
        $cs->registerScriptFile(CHtml::asset(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets').'/js/jquery.lorem.js');
		$options=$this->getClientOptions();
		$options=$options===array()?'{}' : CJavaScript::encode($options);
		$cs->registerScript('Yii.JLoremIpsum#'.$this->Id, "jQuery('{$this->Id}').lorem($options);");
	}

	/**
	 * @return array the javascript options
	 */
	protected function getClientOptions()
	{
		$options=array();
		foreach(array('type','amount','ptags') as $name)
		{
			if($this->$name!==null)
				$options[$name]=$this->$name;
		}
		return $options;
	}
}
