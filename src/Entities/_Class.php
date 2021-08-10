<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractClassEntity;
use Evas\Documentor\Entities\Traits\InnerTraitsAndPropertiesTrait;
use Evas\Documentor\Entities\Traits\PrefixTrait;
use Evas\Documentor\Entities\_ClassConstant;

/**
 * Сущность класса.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
class _Class extends AbstractClassEntity
{
    use PrefixTrait; // $prefix
    use InnerTraitsAndPropertiesTrait; // $traits, $properties

    /**
     * @var _Class|null родительский класс
     */
    public $extends;

    /**
     * @var array of _Interface интерфейсы класса
     */
    public $implements = [];

    /**
     * @var array of _ClassConstant константы класса
     */
    public $constants = [];

    /**
     * Конструктор.
     * @param string имя класса вместе с extends и implements
     */
    public function __construct(string $name)
    {
        @[$name, $implements] = explode(' implements ', $name);
        if (!empty($implements)) {
            $implements = str_replace(' ', '', $implements);
            $implements = explode(',', $implements);
            foreach ($implements as $implement) {
                $this->implement($implement);
            }
        }
        @[$name, $extends] = explode(' extends ', $name);
        if (!empty($extends)) {
            $extends = trim($extends);
            $this->extends($extends);
        }
        $this->name = trim($name);
    }

    /**
     * Установка интерфейса класса.
     * @param string имя интерфейса
     */
    public function implements(string $name)
    {
        $this->implements[] = $name;
    }

    /**
     * Установка родительского класса.
     * @param string имя родительского класса
     */
    public function extends(string $name)
    {
        $this->extends = $name;
    }

    /**
     * Установка константы класса.
     * @param _ClassConstant
     */
    public function constant(_ClassConstant $constant)
    {
        $this->constants[$constant->name] = &$constant;
    }
}
