<?php
/**
 * Имена сущностей.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1 Jul 2020
 */
namespace Evas\Documentor\Entities\Base;

use Evas\Documentor\Entities\_Class;
use Evas\Documentor\Entities\_ClassConstant;
use Evas\Documentor\Entities\_File;
use Evas\Documentor\Entities\_FileConstant;
use Evas\Documentor\Entities\_FileFunction;
use Evas\Documentor\Entities\_FunctionArg;
use Evas\Documentor\Entities\_FunctionReturnType;
use Evas\Documentor\Entities\_Include;
use Evas\Documentor\Entities\_InnerTrait;
use Evas\Documentor\Entities\_Interface;
use Evas\Documentor\Entities\_Method;
use Evas\Documentor\Entities\_Namespace;
use Evas\Documentor\Entities\_Property;
use Evas\Documentor\Entities\_UseAlias;
use Evas\Documentor\Entities\_Trait;
// use Evas\Documentor\Entities\Base\AbstractClassEntity;

class EntityNames
{
    const _CLASS = _Class::class;
    const _CLASS_CONSTANT = _ClassConstant::class;
    const _FILE = _File::class;
    const _FILE_CONSTANT = _FileConstant::class;
    const _FILE_FUNCTION = _FileFunction::class;
    const _FUNCTION_ARG = _FunctionArg::class;
    const _FUNCTION_RETURN_TYPE = _FunctionReturnType::class;
    const _INCLUDE = _Include::class;
    const _INNER_TRAIT = _InnerTrait::class;
    const _INTERFACE = _Interface::class;
    const _METHOD = _Method::class;
    const _NAMESPACE = _Namespace::class;
    const _PROPERTY = _Property::class;
    const _USE_ALIAS = _UseAlias::class;
    const _TRAIT = _Trait::class;

    const NAMES = [
        'class' => self::_CLASS,
        'interface' => self::_INTERFACE,
        'trait' => self::_TRAIT,
    ];

    public static function get(string $name): ?string
    {
        return self::NAMES[$name] ?? null;
    }
}
