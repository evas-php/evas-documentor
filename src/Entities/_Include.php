<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractFileEntity;

/**
 * Сущность подключаемого файла.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 2 Jul 2020
 */
class _Include extends AbstractFileEntity
{
    /**
     * @var string путь файла.
     */
    public $path;
}
