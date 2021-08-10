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
    public $name;
    public $args = [];
    public $returnType;

    public function arg(_FunctionArg $arg)
    {
        $this->args[] = &$arg;
    }

    public function returnType(_FunctionReturnType $type)
    {
        $this->returnType = &$type;
    }

    // /**
    //  * Конструктор.
    //  * @param string имя функции с аргументами и возвращаемым типом.
    //  */
    // public function __construct(string $name)
    // {
    //     @[$name, $returnType] = explode(')', $name);
    //     if (!empty($returnType)) {
    //         $returnType = str_replace(':', '', $returnType);
    //         if (!empty($returnType)) {
    //             $this->returnType($returnType);
    //         }
    //     }
    //     @[$name, $args] = explode('(', $name);
    //     if (!empty($args)) {
    //         trim($args);
    //         if (!empty($args)) {
    //             $args = explode(',', $args);
    //             foreach ($args as &$arg) {
    //                 $this->arg($arg);
    //             }
    //         }
    //     }
    //     $name = trim($name);
    //     $this->name = $name;
    // }

    // /**
    //  * Добавление аргумента.
    //  * @param string имя аргумента с типом и значением по умолчанию
    //  */
    // public function arg(string $name)
    // {
    //     $this->args[] = new _FunctionArg($name);
    // }

    // /**
    //  * Добавление возвращаемого типа.
    //  * @param string тип
    //  */
    // public function returnType(string $type)
    // {
    //     $this->returnType = new _FunctionReturnType($type);
    // }
}
