<?php

namespace Ajax\service;

class JReflection {
	public static function shortClassName($object){
		$classNameWithNamespace = get_class($object);
		return substr($classNameWithNamespace, strrpos($classNameWithNamespace, '\\')+1);
	}

	public static function jsonObject($classname){
		$object=new $classname();
		$class = new \ReflectionClass($classname);
		$methods=$class->getMethods(\ReflectionMethod::IS_PUBLIC);
		foreach ($methods as $method){
			$name=$method->getName();
			if(JString::startswith($name, "set")){
				$property=\lcfirst(JString::replaceAtFirst($name, "set", ""));
				$value="__".$property."__";
				try{
					if($class->getProperty($property)!==null){
						\call_user_func_array([$object,$name],[$value]);
					}
				}catch(\Exception $e){
					//Nothing to do
				}
			}
		}
		return $object;
	}

	public static function callMethod($object,$callback,array $values){
		return \call_user_func_array([$object,$callback],$values);
	}

	public static function getterName($propertyName,$prefix="get"){
		return $prefix.\ucfirst($propertyName);
	}

	public static function callMethodFromAssociativeArray($object,$array,$methodPrefix="add"){
		foreach ($array as $key=>$value){
			if(\method_exists($object, $methodPrefix.\ucfirst($key))){
				\call_user_func([$object,$methodPrefix.\ucfirst($key)],$value);
			}
		}
	}
}
