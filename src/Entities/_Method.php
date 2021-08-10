<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractFunction;
use Evas\Documentor\Entities\Traits\PrefixTrait;
use Evas\Documentor\Entities\Traits\ClassEntitiesTrait;

/**
 * Сущность метода класса/интерфейса/трейта.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
class _Method extends AbstractFunction
{
    use PrefixTrait; // $prefix
    use ClassEntitiesTrait; // $name, $visibility, $staticly
}
