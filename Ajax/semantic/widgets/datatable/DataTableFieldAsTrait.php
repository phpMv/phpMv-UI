<?php
namespace Ajax\semantic\widgets\datatable;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\widgets\base\InstanceViewer;
use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\common\html\BaseHtml;

/**
 * trait used in DataTable
 * @author jc
 * @property array $_deleteBehavior
 * @property array $_editBehavior
 * @property boolean $_visibleHover
 * @property InstanceViewer $_instanceViewer
 */
trait DataTableFieldAsTrait{
	abstract public function addField($field);
	abstract public function insertField($index,$field);
	abstract public function insertInField($index,$field);
	abstract public function fieldAs($index,$type,$attributes=NULL);
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
					$callback($object,$instance);
				}
			}
			if($object instanceof HtmlSemDoubleElement){
				$object->setProperty("data-ajax",$this->_instanceViewer->getIdentifier());
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
		$bt= new HtmlButton("",$caption);
		if($visibleHover)
			$this->_visibleOver($bt);
			return $bt;
	}

	/**
	 * Creates a submit button at $index position
	 * @param int $index
	 * @param string $cssStyle
	 * @param string $url
	 * @param string $responseElement
	 * @param array $attributes associative array (<b>ajax</b> key is for ajax post)
	 * @return \Ajax\semantic\widgets\datatable\DataTable
	 */
	public function fieldAsSubmit($index,$cssStyle=NULL,$url=NULL,$responseElement=NULL,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value,$caption) use ($url,$responseElement,$cssStyle,$index,$attributes){
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
	 * @return \Ajax\semantic\widgets\datatable\DataTable
	 */
	public function addFieldButton($caption,$visibleHover=true,$callback=null){
		$this->addField($this->getCallable("getFieldButton",[$caption,$visibleHover],$callback));
		return $this;
	}

	/**
	 * Inserts a new Button for each row at col $index
	 * @param int $index
	 * @param string $caption
	 * @param callable $callback
	 * @return \Ajax\semantic\widgets\datatable\DataTable
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
	 * @return \Ajax\semantic\widgets\datatable\DataTable
	 */
	public function insertInFieldButton($index,$caption,$visibleHover=true,$callback=null){
		$this->insertInField($index, $this->getFieldButtonCallable($caption,$visibleHover,$callback));
		return $this;
	}

	private function addDefaultButton($icon,$class=null,$visibleHover=true,$callback=null){
		$this->addField($this->getCallable("getDefaultButton",[$icon,$class,$visibleHover],$callback));
		return $this;
	}

	private function insertDefaultButtonIn($index,$icon,$class=null,$visibleHover=true,$callback=null){
		$this->insertInField($index,$this->getCallable("getDefaultButton",[$icon,$class,$visibleHover],$callback));
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
	 * @param boolean $visibleHover
	 * @param array $deleteBehavior
	 * @param callable $callback
	 * @return DataTable
	 */
	public function addDeleteButton($visibleHover=true,$deleteBehavior=[],$callback=null){
		$this->_deleteBehavior=$deleteBehavior;
		return $this->addDefaultButton("remove","_delete red basic",$visibleHover,$callback);
	}

	public function addEditButton($visibleHover=true,$editBehavior=[],$callback=null){
		$this->_editBehavior=$editBehavior;
		return $this->addDefaultButton("edit","_edit basic",$visibleHover,$callback);
	}

	public function addEditDeleteButtons($visibleHover=true,$behavior=[],$callbackEdit=null,$callbackDelete=null){
		$this->addEditButton($visibleHover,$behavior,$callbackEdit);
		$index=$this->_instanceViewer->visiblePropertiesCount()-1;
		$this->insertDeleteButtonIn($index,$visibleHover,$behavior,$callbackDelete);
		return $this;
	}

	public function insertDeleteButtonIn($index,$visibleHover=true,$deleteBehavior=[],$callback=null){
		$this->_deleteBehavior=$deleteBehavior;
		return $this->insertDefaultButtonIn($index,"remove","_delete red basic",$visibleHover,$callback);
	}

	public function insertEditButtonIn($index,$visibleHover=true,$editBehavior=[],$callback=null){
		$this->_editBehavior=$editBehavior;
		return $this->insertDefaultButtonIn($index,"edit","_edit basic",$visibleHover,$callback);
	}
}
