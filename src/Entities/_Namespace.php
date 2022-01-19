<?php
/**
 * Сущность пространства имен.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractEntity;
use Evas\Documentor\Entities\Traits\NamespaceEntitiesTrait;

class _Namespace extends AbstractEntity
{
    use NamespaceEntitiesTrait;
    public $name;
    protected $files = []; // array of _Files
}
