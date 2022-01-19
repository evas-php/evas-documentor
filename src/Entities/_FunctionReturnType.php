<?php
/**
 * Сущность возвращаемого типа функции.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractEntity;

class _FunctionReturnType extends AbstractEntity
{
    /**
     * @static array нативные типы PHP
     */
    const NATIVE_TYPES = [
        'boolean', 'integer', 'float', 'double', 'string', 
        'array', 'object', 'callable', 'iterable', 
        'resource', 'null',
        'mixed', 'number', 'callback', 'array|object', 'void',
        'bool', 'int',
    ];

    /**
     * @var string тип
     */
    public $type;

    /**
     * @var bool поддерживается ли null
     */
    public $nullable = false;

    /**
     * @var bool нативен ли тип
     */
    public $nativeType = true;

    /**
     * Конструктор.
     * @param string строка типа
     */
    public function __construct(string $type)
    {
        $type = trim($type);
        if ('?' === substr($type, 0, 1)) {
            $type = substr($type, 1);
            $this->nullable = true;
        }
        $this->type = trim($type);
        $this->setNativeType();
    }

    /**
     * Установка нативности типа.
     */
    public function setNativeType()
    {
        if (!in_array(strtolower($this->type), self::NATIVE_TYPES)) {
            $this->nativeType = false;
        }
    }
}
