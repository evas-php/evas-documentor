<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities\Traits;

use Evas\Documentor\Entities\_Class;
use Evas\Documentor\Entities\_Interface;
use Evas\Documentor\Entities\_Trait;
use Evas\Documentor\Entities\_FileFunction;
use Evas\Documentor\Entities\_FileConstant;

/**
 * Трейт поддержки сущностей неймспейса.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1 Jul 2020
 */
trait NamespaceEntitiesTrait
{
    /**
     * @var array of _Class список классов
     */
    public $classes = [];

    /**
     * @var array of _Interface список интерфейсов
     */
    public $interfaces = [];

    /**
     * @var array of _Trait список трейтов
     */
    public $traits = [];

    /**
     * @var array of _FileFunction список файловых функций
     */
    public $functions = [];

    /**
     * @var array of _FileConstant список файловых констант
     */
    public $constants = [];

    /**
     * Установка класса.
     * @param _Class класс
     */
    public function class(_Class $class)
    {
        $this->classes[$class->name] = &$class;
    }

    /**
     * Установка интерфейса.
     * @param _Interface интерфейс
     */
    public function interface(_Interface $interface)
    {
        $this->interfaces[$interface->name] = &$interface;
    }

    /**
     * Установка трейта.
     * @param _Trait трейт
     */
    public function trait(_Trait $trait)
    {
        $this->traits[$trait->name] = &$trait;
    }

    /**
     * Установка файловой функции.
     * @param _FileFunction файловая функция
     */
    public function addFunction(_FileFunction $function)
    {
        $this->functions[$function->name] = &$function;
    }

    /**
     * Установка файловой константы.
     * @param _FileConstant файловая константа
     */
    public function constant(_FileConstant $constant)
    {
        $this->constants[$constant->name] = &$constant;
    }
}
