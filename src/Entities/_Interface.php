<?php
/**
 * Сущность интерфейса.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractClassEntity;

class _Interface extends AbstractClassEntity
{
    public $extends = [];
}
