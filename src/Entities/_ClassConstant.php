<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractFileEntity;

/**
 * Сущность константы класса.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
class _ClassConstant extends AbstractFileEntity
{
    /**
     * @var string название
     */
    public $name;
    /**
     * @var значение
     */
    public $value;

    /**
     * Конструктор.
     * @param string имя константы и значение
     */
    public function __construct(string $name)
    {
        @[$name, $value] = explode('=', $name);
        if (!empty($value)) {
            $this->value = trim($value);
        }
        $this->name = trim($name);
    }
}