<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractClassEntity;

/**
 * Сущность интерфейса.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
class _Interface extends AbstractClassEntity
{
    public $extends = [];
}
