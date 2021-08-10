<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractFileEntity;
use Evas\Documentor\Entities\Traits\NamespaceTrait;

/**
 * Сущность константы файла.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
class _FileConstant extends AbstractFileEntity
{
    use NamespaceTrait; // $namespace

    public $name;
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
