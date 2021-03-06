<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities\Base;

use Evas\Documentor\Entities\Base\AbstractEntity;
use Evas\Documentor\Entities\_File;

/**
 * Абстрактный класс сущности.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1 Jul 2020
 */
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
}
