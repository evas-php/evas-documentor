<?php
/**
 * Трейт поддержки трейтов и свойств для классов и трейтов.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1 Jul 2020
 */
namespace Evas\Documentor\Entities\Traits;

use Evas\Documentor\Entities\_InnerTrait;
use Evas\Documentor\Entities\_Property;

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
        $this->traits[$trait->name] = &$trait;
    }
}
