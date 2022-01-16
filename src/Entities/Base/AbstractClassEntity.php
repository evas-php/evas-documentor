<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities\Base;

use Evas\Documentor\Entities\Base\AbstractFileEntity;
use Evas\Documentor\Entities\Traits\DocCommentTrait;
use Evas\Documentor\Entities\Traits\NamespaceTrait;
use Evas\Documentor\Entities\_Method;

/**
 * Абстрактный класс классовой сущности (класса/интерфейса/трейта).
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1 Jul 2020
 */
abstract class AbstractClassEntity extends AbstractFileEntity
{
    use DocCommentTrait; // $docComment
    use NamespaceTrait; // $namespace

    /**
     * @var string Название класса
     */
    public $name;

    /**
     * @var array of _Method список функций класса
     */
    public $methods = [];

    /**
     * Установка функции .
     * @param _Method функция
     */
    public function method(_Method $method)
    {
        $this->methods[$method->name] = &$method;
    }
}
