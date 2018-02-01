<?php
namespace Ajax\php\symfony;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class JquerySemantic extends Jquery_ {

	public function __construct(RouterInterface $router,ContainerInterface $container ){
		parent::__construct(["semantic"=>true,"defer"=>true],$router);
		$this->container=$container;
	}
}
