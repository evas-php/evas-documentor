<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\_FunctionReturnType;

/**
 * Сущность аргумента функции.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
class _FunctionArg extends _FunctionReturnType
{
    /**
     * @var string имя
     */
    public $name;
    
    /**
     * @var string значение по умолчанию
     */
    public $default;

    /**
     * Конструктор.
     * @param string сторка аргумента функции
     */
    public function __construct(string $name)
    {
        $name = trim($name);
        @[$name, $default] = explode('=', $name);
        if (!empty($default)) {
            $this->default = trim($default);
            // if ('null' === strtolower($this->default)) {
                $this->nullable = true;
            // }
        }
        $name = trim($name);
        @[$type, $name] = explode(' ', $name);
        if (empty($name)) {
            $name = $type;
            $type = null;
        }
        if (!empty($type)) {
            $this->type = $type;
            $this->setNativeType();
        }
        $this->name = $name;
    }
}
