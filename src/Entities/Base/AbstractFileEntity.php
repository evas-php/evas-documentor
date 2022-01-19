<?php
/**
 * Абстрактный класс сущности файла.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1 Jul 2020
 */
namespace Evas\Documentor\Entities\Base;

use Evas\Documentor\Entities\Base\AbstractEntity;
use Evas\Documentor\Entities\_File;

abstract class AbstractFileEntity extends AbstractEntity
{
    /**
     * @var _File
     */
    private $file;

    /**
     * @var int номер строки
     */
    public $line;

    /**
     * Установка файла.
     * @param _File файл
     */
    public function file(_File $file)
    {
        $this->file = &$file;
    }
}
