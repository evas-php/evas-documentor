<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractClassEntity;
use Evas\Documentor\Entities\Traits\InnerTraitsAndPropertiesTrait;

/**
 * Сущность трейта.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
class _Trait extends AbstractClassEntity
{
    use InnerTraitsAndPropertiesTrait; // $traits, $properties
}
