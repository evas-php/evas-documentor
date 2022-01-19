<?php
/**
 * Сущность подключаемого файла.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 2 Jul 2020
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractFileEntity;

class _Include extends AbstractFileEntity
{
    /**
     * @var string путь файла.
     */
    public $path;
}
