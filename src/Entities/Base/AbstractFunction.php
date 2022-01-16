<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities\Base;

use Evas\Documentor\Entities\Base\AbstractFileEntity;
use Evas\Documentor\Entities\Traits\DocCommentTrait;
use Evas\Documentor\Entities\_FunctionArg;
use Evas\Documentor\Entities\_FunctionReturnType;

/**
 * Абстрактный класс функции.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1 Jul 2020
 */
abstract class AbstractFunction extends AbstractFileEntity
{
    use DocCommentTrait; // $docComment

    /**
     * @var string Название функции
     */
    public $name;

    /**
     * @var array of _FunctionArg аргументы функции
     */
    public $args = [];

    /**
     * @var _FunctionReturnType|null возвращаемый функцией тип 
     */
    public $returnType;

    /**
     * Установка аргумента функции.
     * @param _FunctionArg аргумент
     */
    public function arg(_FunctionArg $arg)
    {
        $this->args[] = &$arg;
    }

    /**
     * Установка возвращаемого функцией типа.
     * @param _FunctionReturnType тип
     */
    public function returnType(_FunctionReturnType $type)
    {
        $this->returnType = &$type;
    }
}
