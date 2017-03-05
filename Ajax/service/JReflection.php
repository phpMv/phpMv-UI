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
				$property=\strtolower(JString::replaceAtFirst($name, "set", ""));
				$value="[[".$property."]]";
				try{
					if($class->getProperty($property)!==null){
						\call_user_func_array([$object,$name],[$value]);
					}
				}catch(\Exception $e){}
			}
		}
		return $object;
	}
}