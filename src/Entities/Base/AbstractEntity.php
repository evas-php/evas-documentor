<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities\Base;

/**
 * Абстрактный класс сущности.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1 Jul 2020
 */
class AbstractEntity
{
    /**
     * Магия для блокировки установки значения несуществующего свойства без Fatal Error.
     * @param string имя свойства
     * @param mixed значение
     */
    public function __set(string $name, $value)
    {
        $instanceOf = get_called_class();
        echo "\e[41mNOT SET $name = $value for entity instance of $instanceOf\e[0m\n";
    }

    /**
     * Конструктор.
     * @param string имя сущности или другое важное значение
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
