<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities\Traits;

use Evas\Documentor\Entities\_Class;
use Evas\Documentor\Entities\_Interface;
use Evas\Documentor\Entities\_Trait;
use Evas\Documentor\Entities\_FileFunction;
use Evas\Documentor\Entities\_FileConstant;

/**
 * Трейт поддержки сущностей неймспейса.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1 Jul 2020
 */
trait NamespaceEntitiesTrait
{
    public $classes = [];
    public $interfaces = [];
    public $traits = [];
    public $functions = [];
    public $constants = [];

    public function class(_Class $class)
    {
        $this->classes[$class->name] = &$class;
    }

    public function interface(_Interface $interface)
    {
        $this->interfaces[$interface->name] = &$interface;
    }

    public function trait(_Trait $trait)
    {
        $this->traits[$trait->name] = &$trait;
    }

    public function addFunction(_FileFunction $function)
    {
        $this->functions[$function->name] = &$function;
    }

    public function constant(_FileConstant $constant)
    {
        $this->constants[$constant->name] = &$constant;
    }
}
