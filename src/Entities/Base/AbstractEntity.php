<?php
/**
 * Абстрактный класс сущности.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1 Jul 2020
 */
namespace Evas\Documentor\Entities\Base;

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
