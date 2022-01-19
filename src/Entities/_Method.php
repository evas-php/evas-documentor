<?php
/**
 * Сущность метода класса/интерфейса/трейта.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractFunction;
use Evas\Documentor\Entities\Traits\PrefixTrait;
use Evas\Documentor\Entities\Traits\ClassEntitiesTrait;

class _Method extends AbstractFunction
{
    use PrefixTrait; // $prefix
    use ClassEntitiesTrait; // $name, $visibility, $staticly
}
