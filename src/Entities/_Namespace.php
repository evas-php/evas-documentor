<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractEntity;
use Evas\Documentor\Entities\Traits\NamespaceEntitiesTrait;

/**
 * Сущность пространства имен.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
class _Namespace extends AbstractEntity
{
    use NamespaceEntitiesTrait;
    public $name;
    protected $files = []; // array of _Files
}
