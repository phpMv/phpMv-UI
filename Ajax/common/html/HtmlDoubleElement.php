<?php

namespace Ajax\common\html;


use Ajax\JsUtils;
use Ajax\semantic\html\collections\form\HtmlFormField;
use Ajax\semantic\html\collections\form\HtmlForm;
class HtmlDoubleElement extends HtmlSingleElement {
	/**
	 *
	 * @var mixed
	 */
	protected $content;
	protected $wrapContentBefore="";
	protected $wrapContentAfter="";
	protected $_editableContent;

	public function __construct($identifier, $tagName="p") {
		parent::__construct($identifier, $tagName);
		$this->_template='<%tagName% id="%identifier%" %properties%>%wrapContentBefore%%content%%wrapContentAfter%</%tagName%>';
	}

	public function setContent($content) {
		$this->content=$content;
		return $this;
	}

	public function getContent() {
		return $this->content;
	}

	public function addContent($content,$before=false) {
		if (!\is_array($this->content)) {
			if(isset($this->content))
				$this->content=array ($this->content);
			else
				$this->content=array();
		}
		if($before)
			array_unshift($this->content,$content);
		else
			$this->content []=$content;
		return $this;
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\HtmlSingleElement::run()
	 */
	public function run(JsUtils $js) {
		parent::run($js);
		if ($this->content instanceof HtmlDoubleElement) {
			$this->content->run($js);
		} else if (\is_array($this->content)) {
			foreach ( $this->content as $itemContent ) {
				if ($itemContent instanceof HtmlDoubleElement) {
					$itemContent->run($js);
				}
			}
		}
	}

	public function setValue($value) {
	}

	public function wrapContent($before, $after="") {
		$this->wrapContentBefore.=$before;
		$this->wrapContentAfter=$after.$this->wrapContentAfter;
		return $this;
	}

	public function getContentInstances($class){
		return $this->_getContentInstances($class,$this->content);
	}

	protected function _getContentInstances($class,$content){
		$instances=[];
		if($content instanceof $class){
			$instances[]=$content;
		}elseif($content instanceof HtmlDoubleElement){
			$instances=\array_merge($instances,$content->getContentInstances($class));
		}elseif (\is_array($content)){
			foreach ($content as $element){
				$instances=\array_merge($instances,$this->_getContentInstances($class, $element));
			}
		}
		return $instances;
	}

	/**
	 * Transforms the element into a link
	 * @return HtmlDoubleElement
	 */
	public function asLink($href=NULL,$target=NULL) {
		if (isset($href))
			$this->setProperty("href", $href);
		if(isset($target))
			$this->setProperty("target", $target);
		return $this->setTagName("a");
	}
	
	public function getTextContent(){
		if(is_array($this->content)){
			return strip_tags(implode("", $this->content));
		}
		return strip_tags($this->content);
	}
	
	public function asEditable(HtmlFormField $field,$asForm=false,$setValueProperty="val()"){
		$idF=$field->getIdentifier();
		$idE=$idF;
		if($asForm){
			$frm=new HtmlForm("frm-".$field->getIdentifier());
			$frm->setProperty("onsubmit", "return false;");
			$fields=$frm->addFields();
			$idE=$frm->getIdentifier();
			$fields->addItem($field);
			$fields->addButtonIcon("bt-okay", "check","green mini","\$('#".$idE."').trigger('validate',{value: $('#'+idF+' input').val()});");
			$fields->addButtonIcon("bt-cancel", "close","mini","\$('#".$idE."').trigger('endEdit');");
			$this->_editableContent=$frm;
			$keypress="";
			$focusOut="";
		}else{
			$focusOut="if(e.relatedTarget==null)elm.trigger('endEdit');";
			$this->_editableContent=$field;
			$keypress="$('#".$idF."').keyup(function(e){if(e.which == 13) {\$('#".$idE."').trigger('validate',{value: $('#'+idF+' input').val()});}if(e.keyCode===27) {\$('#".$idE."').trigger('endEdit');}});";
		}
		$this->_editableContent->setProperty("style", "display:none;");
		$this->onCreate("let idF='".$idF."';let idE='".$idE."';let elm=$('#'+idE);let self=$('#".$this->getIdentifier()."');".$keypress."elm.on('validate',function(){self.html($('#'+idE+' input').".$setValueProperty.");elm.trigger('endEdit');});elm.on('endEdit',function(){self.show();$(this).hide();});elm.focusout(function(e){".$focusOut."});");
		$this->onClick("let self=$(this);self.hide();".$field->setJsContent("self.html()").";$('#".$idF." input').trigger('change');elm.show();$('#'+idE+' input').focus();");
	}
	/**
	 * {@inheritDoc}
	 * @see \Ajax\common\html\BaseHtml::compile_once()
	 */
	protected function compile_once(\Ajax\JsUtils $js = NULL, &$view = NULL) {
		if(!$this->_compiled && isset($this->_editableContent)){
			$this->wrap("",$this->_editableContent);
		}
		parent::compile_once($js,$view);
		
	}

}
