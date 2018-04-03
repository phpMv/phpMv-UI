<?php
namespace Ajax\bootstrap\html;

use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
use Ajax\bootstrap\html\base\HtmlNavElement;
use Ajax\bootstrap\html\base\CssRef;
use Ajax\common\html\BaseHtml;
use Ajax\JsUtils;
use Ajax\service\JString;

/**
 * Twitter Bootstrap Pagination component
 * @see http://getbootstrap.com/components/#pagination
 * @author jc
 * @version 1.001
 */
class HtmlPagination extends HtmlNavElement {

	/**
	 * @var int
	 */
	protected $from;

	/**
	 * @var int
	 */
	protected $to;


	/**
	 * @var int
	 */
	protected $countVisible;

	/**
	 * @var int
	 */
	protected $active;

	/**
	 * @var string
	 */
	protected $urlMask;

	/**
	 * @param string $identifier
	 */
	public function __construct($identifier,$from=1,$to=1,$active=NULL,$countVisible=NULL){
		parent::__construct($identifier,"ul");
		$this->setProperty("class", "pagination");
		$this->active=$active;
		$this->from=$from;
		$this->to=$to;
		$this->urlMask="%page%";
		if(!isset($countVisible))
			$this->countVisible=$to-$from+1;
		else
			$this->countVisible=$countVisible;
		$this->createContent();
	}

	private function createElement($num,$content,$disabled=false,$current=false){
		$count=sizeof($this->content)+1;
		$elem=new HtmlBsDoubleElement("li-".$this->identifier."-".$count,"li");
		if($disabled){
			$elem->setProperty("class", "disabled");
		}
		if($current){
			$content.="<span class='sr-only'>(current)</span>";
			$elem->setProperty("class", "active");
		}
		if(!$disabled){
			$url=$this->getUrl($num);
			$href=new HtmlLink("a-".$this->identifier."-".$count,$url,$content);
			$href->setProperty($this->attr, $url);
			$elem->setContent($href);
		}else{
			$elem->setContent($content);
		}
		$this->content[]=$elem;
		return $this;
	}

	protected function createContent(){
		$this->content=array();
		$this->createElement($this->active-1,"<span aria-hidden='true'>&laquo;</span>",$this->active===1);
		$start=$this->getStart();
		$end=min($start+$this->countVisible-1,$this->to);
		for($index=$start;$index<=$end;$index++){
			$this->createElement($index,$index,false,$index===$this->active);
		}
		$this->createElement($this->active+1,"<span aria-hidden='true'>&raquo;</span>",$this->active===$this->to);
	}

	protected function half(){
		return (int)($this->countVisible/2);
	}

	protected function getStart(){
		$result=1;
		if($this->countVisible!==$this->to-$this->from+1){
			$result=max($this->active-$this->half(),$result);
		}
		return $result;
	}

	public function _addEvent($event, $jsCode) {
		foreach ($this->content as $li){
			$content=$li->getContent();
			if($content instanceof BaseHtml)
				$content->_addEvent($event,$jsCode);
		}
	}
	/**
	 * set the active page corresponding to request dispatcher : controllerName, actionName, parameters and $urlMask
	 * @param JsUtils $js
	 * @param object $dispatcher the request dispatcher
	 * @return \Ajax\bootstrap\html\HtmlPagination
	 */
	public function fromDispatcher(JsUtils $js,$dispatcher,$startIndex=0){
		$items=$js->fromDispatcher($dispatcher);
		$url=implode("/", $items);
		if($this->urlMask==="%page%"){
			$this->urlMask=preg_replace("/[0-9]/", "%page%", $url);
		}
		for($index=$this->from;$index<=$this->to;$index++){
			if($this->getUrl($index)==$url){
				$this->setActive($index);
				break;
			}
		}
		return $this;
	}

	public function getUrl($index){
		return str_ireplace("%page%", $index, $this->urlMask);
	}

	/**
	 * define the buttons size
	 * available values : "lg","","sm","xs"
	 * @param string|int $size
	 * @return HtmlPagination default : ""
	 */
	public function setSize($size) {
		if (is_int($size)) {
			return $this->addToPropertyUnique("class", CssRef::sizes("pagination")[$size], CssRef::sizes("pagination"));
		}
		if(!JString::startsWith($size, "pagination-") && $size!=="")
			$size="pagination-".$size;
		return $this->addToPropertyCtrl("class", $size, CssRef::sizes("pagination"));
	}

	public function getFrom() {
		return $this->from;
	}
	public function setFrom($from) {
		$this->from = $from;
		$this->createContent();
		return $this;
	}
	public function getTo() {
		return $this->to;
	}
	public function setTo($to) {
		$this->to = $to;
		$this->createContent();
		return $this;
	}
	public function getActive() {
		return $this->active;
	}
	public function setActive($active) {
		$this->active = $active;
		$this->createContent();
		return $this;
	}
	public function getUrlMask() {
		return $this->urlMask;
	}
	public function setUrlMask($urlMask) {
		$this->urlMask = $urlMask;
		$this->createContent();
		return $this;
	}
	public function getCountVisible() {
		return $this->countVisible;
	}
	public function setCountVisible($countVisible) {
		$this->countVisible = $countVisible;
		$this->createContent();
		return $this;
	}


}
