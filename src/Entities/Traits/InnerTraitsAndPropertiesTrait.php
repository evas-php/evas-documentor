<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities\Traits;

use Evas\Documentor\Entities\_InnerTrait;
use Evas\Documentor\Entities\_Property;

/**
 * Трейт поддержки трейтов и свойств для классов и трейтов.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1 Jul 2020
 */
trait InnerTraitsAndPropertiesTrait
{
    /**
     * @var array of _InnerTrait список трейтов
     */
    public $traits = [];
    /**
     * @var array of _InnerTrait список свойств
     */
    public $properties = [];

    /**
     * Установка свойства.
     * @param _Property
     */
    public function property(_Property $property)
    {
        $this->properties[$property->name] = &$property;
    }

    /**
     * Установка трейта.
     * @param _Trait
     */
    public function trait(_InnerTrait $trait)
    {
        $this->traits[$trait->traitName] = &$trait;
    }
}
