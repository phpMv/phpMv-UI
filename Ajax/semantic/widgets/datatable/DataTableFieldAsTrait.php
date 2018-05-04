<?php
namespace Ajax\semantic\widgets\datatable;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\common\html\BaseHtml;
use Ajax\semantic\html\elements\HtmlButtonGroups;

/**
 * trait used in DataTable
 * @author jc
 * @property array $_deleteBehavior
 * @property array $_editBehavior
 * @property array _displayBehavior
 * @property boolean $_visibleHover
 * @property InstanceViewer $_instanceViewer
 */
trait DataTableFieldAsTrait{
	protected $_buttons=["display","edit","delete"];
	protected $_buttonsColumn;
	abstract public function addField($field,$key=null);
	abstract public function insertField($index,$field,$key=null);
	abstract public function insertInField($index,$field,$key=null);
	abstract public function fieldAs($index,$type,$attributes=NULL);
	abstract protected function cleanIdentifier($id);
	abstract protected function _fieldAs($elementCallback,&$index,$attributes=NULL,$prefix=null);
	
	/**
	 * @param string $caption
	 * @param callable $callback
	 * @param boolean $visibleHover
	 * @return callable
	 */
	private function getFieldButtonCallable($caption,$visibleHover=true,$callback=null){
		return $this->getCallable("getFieldButton",[$caption,$visibleHover],$callback);
	}

	/**
	 * @param callable $thisCallback
	 * @param array $parameters
	 * @param callable $callback
	 * @return callable
	 */
	private function getCallable($thisCallback,$parameters,$callback=null){
		$result=function($instance) use($thisCallback,$parameters,$callback){
			$object=call_user_func_array(array($this,$thisCallback), $parameters);
			if(isset($callback)){
				if(\is_callable($callback)){
					$callback($object,$instance,$this->_instanceViewer->count()+1);
				}
			}
			if($object instanceof HtmlSemDoubleElement){
				$id=$this->_instanceViewer->getIdentifier();
				$object->setProperty("data-ajax",$id);
				if($object->propertyContains("class","visibleover")){
					$this->_visibleHover=true;
					$object->setProperty("style","visibility:hidden;");
				}
			}
			return $object;
		};
		return $result;
	}

	/**
	 * @param string $caption
	 * @return HtmlButton
	 */
	private function getFieldButton($caption,$visibleHover=true){
		$bt= new HtmlButton($this->cleanIdentifier($caption),$caption);
		if($visibleHover)
			$this->_visibleOver($bt);
		return $bt;
	}

	private function getFieldButtons($buttons,$visibleHover=true){
		$bts=new HtmlButtonGroups("",$buttons);
		if($visibleHover)
			$this->_visibleOver($bts);
		return $bts;
	}

	/**
	 * Creates a submit button at $index position
	 * @param int $index
	 * @param string $cssStyle
	 * @param string $url
	 * @param string $responseElement
	 * @param array $attributes associative array (<b>ajax</b> key is for ajax post)
	 * @return DataTable
	 */
	public function fieldAsSubmit($index,$cssStyle=NULL,$url=NULL,$responseElement=NULL,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value,$caption) use ($url,$responseElement,$cssStyle,$attributes){
			$button=new HtmlButton($id,$value,$cssStyle);
			$button->postOnClick($url,"$(event.target).closest('tr').find(':input').serialize()",$responseElement,$attributes["ajax"]);
			if(!isset($attributes["visibleHover"]) || $attributes["visibleHover"])
				$this->_visibleOver($button);
				return $button;
		}, $index,$attributes);
	}

	protected function _visibleOver(BaseHtml $element){
		$this->_visibleHover=true;
		return $element->addToProperty("class", "visibleover")->setProperty("style","visibility:hidden;");
	}

	/**
	 * Inserts a new Button for each row
	 * @param string $caption
	 * @param callable $callback
	 * @param boolean $visibleHover
	 * @return DataTable
	 */
	public function addFieldButton($caption,$visibleHover=true,$callback=null){
		$this->addField($this->getCallable("getFieldButton",[$caption,$visibleHover],$callback));
		return $this;
	}

	/**
	 * Inserts a new ButtonGroups for each row
	 * @param array $buttons
	 * @param callable $callback
	 * @param boolean $visibleHover
	 * @return DataTable
	 */
	public function addFieldButtons($buttons,$visibleHover=true,$callback=null){
		$this->addField($this->getCallable("getFieldButtons",[$buttons,$visibleHover],$callback));
		return $this;
	}

	/**
	 * Inserts a new Button for each row at col $index
	 * @param int $index
	 * @param string $caption
	 * @param callable $callback
	 * @return DataTable
	 */
	public function insertFieldButton($index,$caption,$visibleHover=true,$callback=null){
		$this->insertField($index, $this->getFieldButtonCallable($caption,$visibleHover,$callback));
		return $this;
	}

	/**
	 * Inserts a new Button for each row in col at $index
	 * @param int $index
	 * @param string $caption
	 * @param callable $callback
	 * @return DataTable
	 */
	public function insertInFieldButton($index,$caption,$visibleHover=true,$callback=null,$key=null){
		$this->insertInField($index, $this->getFieldButtonCallable($caption,$visibleHover,$callback),$key);
		return $this;
	}

	private function addDefaultButton($icon,$class=null,$visibleHover=true,$callback=null,$key=null){
		$this->addField($this->getCallable("getDefaultButton",[$icon,$class,$visibleHover],$callback),$key);
		return $this;
	}

	public function insertDefaultButtonIn($index,$icon,$class=null,$visibleHover=true,$callback=null,$key=null){
		$this->insertInField($index,$this->getCallable("getDefaultButton",[$icon,$class,$visibleHover],$callback),$key);
		return $this;
	}

	private function getDefaultButton($icon,$class=null,$visibleHover=true){
		$bt=$this->getFieldButton("",$visibleHover);
		$bt->asIcon($icon);
		if(isset($class))
			$bt->addClass($class);
		return $bt;
	}

	/**
	 * Adds a delete button
	 * @param boolean $visibleHover
	 * @param array $deleteBehavior default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"data-ajax","params"=>"{}","method"=>"get")
	 * @param callable $callback this function takes the following arguments : $object=>the delete button, $instance : the active instance of the object
	 * @return DataTable
	 */
	public function addDeleteButton($visibleHover=true,$deleteBehavior=[],$callback=null){
		$this->_deleteBehavior=$deleteBehavior;
		return $this->addDefaultButton("remove","_delete red basic",$visibleHover,$callback,"delete");
	}

	/**
	 * Adds an edit button
	 * @param boolean $visibleHover
	 * @param array $editBehavior default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"data-ajax","params"=>"{}","method"=>"get")
	 * @param callable $callback this function takes the following arguments : $object=>the delete button, $instance : the active instance of the object
	 * @return DataTable
	 */
	public function addEditButton($visibleHover=true,$editBehavior=[],$callback=null){
		$this->_editBehavior=$editBehavior;
		return $this->addDefaultButton("edit","_edit basic",$visibleHover,$callback,"edit");
	}
	
	/**
	 * Adds a button for displaying an object
	 * @param boolean $visibleHover
	 * @param array $displayBehavior default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"data-ajax","params"=>"{}","method"=>"get")
	 * @param callable $callback this function takes the following arguments : $object=>the delete button, $instance : the active instance of the object
	 * @return DataTable
	 */
	public function addDisplayButton($visibleHover=true,$displayBehavior=[],$callback=null){
		$this->_displayBehavior=$displayBehavior;
		return $this->addDefaultButton("eye","_display basic",$visibleHover,$callback,"display");
	}

	/**
	 * Adds an edit and a delete button
	 * @param boolean $visibleHover
	 * @param array $behavior default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"data-ajax","params"=>"{}","method"=>"get")
	 * @param callable $callbackEdit this function takes the following arguments : $object=>the delete button, $instance : the active instance of the object
	 * @param callable $callbackDelete this function takes the following arguments : $object=>the delete button, $instance : the active instance of the object
	 * @return DataTable
	 */
	public function addEditDeleteButtons($visibleHover=true,$behavior=[],$callbackEdit=null,$callbackDelete=null){
		$this->addEditButton($visibleHover,$behavior,$callbackEdit);
		$index=$this->_instanceViewer->visiblePropertiesCount()-1;
		$this->insertDeleteButtonIn($index,$visibleHover,$behavior,$callbackDelete);
		return $this;
	}
	
	/**
	 * Adds an edit and a delete button
	 * @param boolean $visibleHover
	 * @param array $behavior default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"data-ajax","params"=>"{}","method"=>"get")
	 * @param callable $callbackDisplay this function takes the following arguments : $object=>the delete button, $instance : the active instance of the object
	 * @param callable $callbackEdit this function takes the following arguments : $object=>the delete button, $instance : the active instance of the object
	 * @param callable $callbackDelete this function takes the following arguments : $object=>the delete button, $instance : the active instance of the object
	 * @return DataTable
	 */
	public function addAllButtons($visibleHover=true,$behavior=[],$callbackDisplay=null,$callbackEdit=null,$callbackDelete=null){
		$this->addDisplayButton($visibleHover,$behavior,$callbackDisplay);
		$index=$this->_instanceViewer->visiblePropertiesCount()-1;
		$this->_buttonsColumn=$index;
		$this->insertEditButtonIn($index,$visibleHover,$behavior,$callbackEdit);
		$this->insertDeleteButtonIn($index,$visibleHover,$behavior,$callbackDelete);
		return $this;
	}

	public function insertDeleteButtonIn($index,$visibleHover=true,$deleteBehavior=[],$callback=null){
		$this->_deleteBehavior=$deleteBehavior;
		return $this->insertDefaultButtonIn($index,"remove","_delete red basic",$visibleHover,$callback,"delete");
	}

	public function insertEditButtonIn($index,$visibleHover=true,$editBehavior=[],$callback=null){
		$this->_editBehavior=$editBehavior;
		return $this->insertDefaultButtonIn($index,"edit","_edit basic",$visibleHover,$callback,"edit");
	}
	
	public function insertDisplayButtonIn($index,$visibleHover=true,$displayBehavior=[],$callback=null){
		$this->_displayBehavior=$displayBehavior;
		return $this->insertDefaultButtonIn($index,"eye","_display basic",$visibleHover,$callback,"display");
	}
	
	/**
	 * @param array  $_buttons
	 */
	public function setButtons($_buttons) {
		$this->_buttons = $_buttons;
		return $this;
	}
}
