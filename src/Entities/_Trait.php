<?php
/**
 * Сущность трейта.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractClassEntity;
use Evas\Documentor\Entities\Traits\InnerTraitsAndPropertiesTrait;

class _Trait extends AbstractClassEntity
{
    use InnerTraitsAndPropertiesTrait; // $traits, $properties
}
