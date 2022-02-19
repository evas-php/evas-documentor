<?php
/**
 * Преобразователь результатов работы документора в Markdown.
 * @package evas-php\evas-documentor
 * @author Evgeniy Erementchouk <erement@evas-php.com>
 * @since 17 Feb 2022
 */
namespace Evas\Documentor;

trait Markdown
{
	public function getAliases($entity): array
	{
		$aliases = [];
		if (!empty($entity->useAliases)) {
            foreach ($entity->useAliases as &$alias) {
                $temp = explode('\\', $alias->name);
                $name = array_pop($temp);
                if (!is_null($alias->as)) {
                    $aliases[$alias->as] = strtolower(implode('', $temp));
                } else {
                    $aliases[$name] = strtolower(implode('', $temp));
                }
            }
        }
		return $aliases;
	}
	public function aliasLink($name, $aliases): string
	{		
		return isset($aliases[$name]) ? '['.$name.']('.$aliases[$name].'.html#'.strtolower($name).')' : "$name";
	}	
	public function getExtends($entity, array $aliases): string
	{	
		$out = '';
		if (!is_null($entity->extends)) {
            $out .= "extends     \n";
            $out .= '* '.$this->aliasLink($entity->extends, $aliases)."     \n\n";
        }
		return $out;
	}	
	public function getExtendsMultiple($entity, array $aliases): string
	{	
		$out = '';
		if (!empty($entity->extends)) {
            $out .= "extends     \n";
            foreach ($entity->extends as &$extends) {
            	$out .= '* '.$this->aliasLink($extends, $aliases)."     \n";
            }
            $out .= "\n";
        }
		return $out;
	}
	public function getImplements($entity, array $aliases): string
	{	
		$out = '';
        if (!empty($entity->implements)) {
            $out .= "implements     \n";
            foreach ($entity->implements as &$implement) {
                $out .= '* '.$this->aliasLink($implement, $aliases)."     \n";
            }
            $out .= "\n";
        }
		return $out;
	}
	public function getTraits($entity, array $aliases): string
	{	
		$out = '';
        if (!empty($entity->traits)) {
            $out .= "use     \n";
            foreach ($entity->traits as $traitName => &$trait) {
                $out .= '* '. $this->aliasLink($traitName, $aliases)." ".implode("     \n", explode('* ', $trait->docComment['description'])) ."     \n";
            }
            $out .= "\n";
        }
		return $out;
	}
	public function getProperties($entity, array $aliases): string
	{	
		$out = '';        
        if (!empty($entity->properties)) {
            $out .= "### Properties     \n";
            foreach ($entity->properties as $propertyName => &$property) {
                $out .= $property->visibility .($property->staticly ? 'static ' : ' ');

                if (!empty($property->docComment['var'])) {
                	if(!is_string($property->docComment['var'][0])){
                    	$out .= $this->aliasLink($property->docComment['var'][0]['type'],$aliases);
                	}
                	$out .= " *$propertyName* ";
                	if(!is_string($property->docComment['var'][0])){
                    	$out .= '**'.implode("     \n", explode('* ', $property->docComment['var'][0]['info'])).'**';
                	} else {
                    	$out .= '**'.implode("     \n", explode('* ', $property->docComment['var'][0])).'**';                		
                	}
                } else {
                	$out .= "*$propertyName*";                	
                }
                $out .= "     \n";
            }
        }
		return $out;
	}
	public function getConstants($entity, array $aliases): string
	{	
		$out = '';        
        if (!empty($entity->constants)) {
            $out .= "### Constants     \n";
            foreach ($entity->constants as $constantName => &$constant) {
                // $out .= '###### ';
                $out .= " *$constantName* = ".$constant->value;
                $out .= "     \n";
            }
        }
		return $out;
	}
	public function getMethodArguments($entity, array $aliases): string
	{	
        $out = "#### Arguments     \n";
        if (!empty($entity->args)) {
            $out .= "|name|default|type|nullable|description|     \n";
            $out .= "|----|-------|----|--------|-----------|     \n";
            foreach ($entity->args as $key => &$argument) {
                $out .= "|".$argument->name;
                $out .= "|".(is_null($argument->default) ? 'None' : $argument->default);
                $out .= "|".$this->aliasLink($argument->type,$aliases);
                $out .= "|".($argument->nullable ? 'Yes' : 'None');
                $out .= "|";
                if (!empty($entity->docComment['param'])) {
                    if (isset($entity->docComment['param'][$key])) {
                    	if (is_string($entity->docComment['param'][$key])) {
                    		$out.= $entity->docComment['param'][$key];
                    	} else {
                    		$out.= $entity->docComment['param'][$key]['info'];
                    	}
                    }
                }
                $out .= "|     \n";
            }
        } else {                                
            $out .= "None     \n     \n";
        }
		return $out;
	}
	public function getMethod($entity, array $aliases): string
	{	
		$out = ''; 
		if (isset($entity->visibility) && isset($entity->visibility)) {
        	$out .= $entity->visibility .($entity->staticly ? 'static ' : ' ');
		}
        if (!is_null($entity->returnType)) {
        	$out .= ' :'.$this->aliasLink($entity->returnType->type, $aliases);
        }
        $out .= " *$".$entity->name."*";
        if (!is_null($entity->docComment)) {
            $out .= " **" . implode("     \n", explode('* ', $entity->docComment['description'])) . "**";
        }
        $out .= "     \n".$this->getMethodArguments($entity,$aliases);
		$out .= "\n ---        \n";
		return $out;
	}
	public function getMethods($entity, array $aliases): string
	{	
		$out = '';        
        if (!empty($entity->methods)) {
            $out .= "### Methods     \n";
            foreach ($entity->methods as $methodName => &$method) {
                $out .= $this->getMethod($method,$aliases);
            }
        }
		return $out;
	}
	public function class($entity): string
	{	
        $aliases = $this->getAliases($entity);
		$out = '';
        $out .= $this->getExtends($entity, $aliases);
        $out .= $this->getImplements($entity, $aliases);
        $out .= $this->getTraits($entity, $aliases);
        $out .= "     \n";
        $out .= $this->getProperties($entity, $aliases);
        $out .= "     \n";
        $out .= $this->getConstants($entity, $aliases);
        $out .= "     \n";
        $out .= $this->getMethods($entity, $aliases);
        $out .= "     \n";
        $out .= "\n ---        \n";

		return $out;
	}
	public function interface($entity): string
	{	
        $aliases = $this->getAliases($entity);
		$out = '';
        $out .= $this->getExtendsMultiple($entity, $aliases);
        $out .= "     \n";
        $out .= $this->getMethods($entity, $aliases);
        $out .= "     \n";
        $out .= "\n ---        \n";

		return $out;
	}
	public function trait($entity): string
	{	
        $aliases = $this->getAliases($entity);
		$out = '';
        $out .= $this->getTraits($entity, $aliases);
        $out .= "     \n";
        $out .= $this->getProperties($entity, $aliases);
        $out .= "     \n";
        $out .= $this->getMethods($entity, $aliases);
        $out .= "     \n";
        $out .= "\n ---        \n";

		return $out;
	}
	public function fileFunction($entity): string
	{	
        $aliases = $this->getAliases($entity);
		$out = '';
        $out .= $this->getMethod($entity, $aliases);
        $out .= "     \n";
        $out .= "\n ---        \n";

		return $out;
	}
	public function fileConstant($entity): string
	{	
        throw new Exception("Error Processing Request", 1);
        

		return $out;
	}

}
?>