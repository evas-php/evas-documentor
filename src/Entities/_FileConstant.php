<?php
/**
 * Сущность константы файла.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractFileEntity;
use Evas\Documentor\Entities\Traits\NamespaceTrait;

class _FileConstant extends AbstractFileEntity
{
    use NamespaceTrait; // $namespace

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
        $name = substr($name, 1, strlen($name) - 2);
        @[$name, $value] = explode(',', $name);
        if (!empty($value)) {
            $this->value = trim($value);
        }
        $name = trim($name);
        $this->name = substr($name, 1, strlen($name) - 2);
    }
}
