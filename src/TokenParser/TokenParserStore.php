<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\TokenParser;

/**
 * Хранилище парсинга PHP токенов файла.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 2 Jul 2020
 */
class TokenParserStore
{
    /**
     * @static array список допустимых префиксов сущности
     */
    const PREFIX_LIST = ['abstract', 'final'];

    /**
     * @static array список допустимых видимостей сущности
     */
    const VISABILITY_LIST = ['public', 'protected', 'private'];

    /**
     * @var string докблок
     */
    public $docComment;

    /**
     * @var string пространство имен
     */
    public $namespace;

    /**
     * @var bool было ли двоеточие перед ключевым словом class
     */
    public $doubleColon;

    /**
     * @var ClassEntity сущность типа класса (_Class, _Interface, _Trait)
     */
    public $class;

    /**
     * @var _Method
     */
    public $method;

    /**
     * @var string префикс (null|abstract|final)
     */
    public $prefix;

    /**
     * @var string видимость (public|protected|private)
     */
    public $visability;

    /**
     * @var bool статичность
     */
    public $staticly;

    /**
     * @var int счетчики скобок сущности класса
     */
    public $classBraceCount = 0;

    /**
     * @var int счетчики скобок метода
     */
    public $methodBraceCount = 0;

    /**
     * Установка пространства имен сущности.
     * @param object сущность
     * @return self
     */
    public function setNamespace(object &$object): TokenParserStore
    {
        if (!empty($this->namespace)) {
            // call_user_func([$this->namespace, $namespaceEntityMethod], $object);
            // $object->namespace($this->namespace);
            $object->namespace = $this->namespace;
        }
        return $this;
    }

    /**
     * @param object сущность
     * @return self
     */
    public function setDocComment(object &$object): TokenParserStore
    {
        if (!empty($this->docComment)) {
            if (method_exists($object, 'docComment')) {
                $object->docComment($this->docComment);
            }
            $this->docComment = null;
        }
        return $this;
    }

    /**
     * Установка префикса (abstract|final) сущности.
     * @param object сущность
     * @return self
     */
    public function setPrefix(object &$object): TokenParserStore
    {
        if (!empty($this->prefix)) {
            if ($object instanceof _Method || $object instanceof AbstractClassEntity) {
                $object->prefix = in_array($this->prefix, self::PREFIX_LIST) 
                    ? $this->prefix : null;
            }
            $this->prefix = null;
        }
        return $this;
    }

    /**
     * Установка видимости и статичности сущности.
     * @param object сущность
     * @return self
     */
    public function setVisabilityAndStaticly(object &$object): TokenParserStore
    {
        if ($object instanceof _Method || $object instanceof _Property) {
            $object->visibility = in_array($this->visibility, self::VISABILITY_LIST) 
                ? $this->visibility : 'public';
            $object->staticly = $this->staticly ?? false;
        }
        $this->visibility = $this->staticly = null;
        return $this;
    }

    /**
     * Инкремент счетчика скобок сущности класса.
     */
    public function incrementClassBraceCount()
    {
        $this->classBraceCount++;
    }

    /**
     * Декремент счетчика скобок сущности класса.
     */
    public function decrementClassBraceCount()
    {
        $this->classBraceCount--;
        if (0 >= $this->classBraceCount) {
            $this->classBraceCount = 0;
            $this->class = null;
        }
    }

    /**
     * Инкремент счетчика скобок метода.
     */
    public function incrementMethodBraceCount()
    {
        $this->methodBraceCount++;
    }

    /**
     * Декремент счетчика скобок метода.
     */
    public function decrementMethodBraceCount()
    {
        $this->methodBraceCount--;
        if (0 >= $this->methodBraceCount) {
            $this->methodBraceCount = 0;
            $this->method = null;
        }
    }

    /**
     * Проверка наличия положительного значения счетчика скобок сущности класса.
     * @return bool
     */
    public function hasClassBraceCount(): bool
    {
        return $this->classBraceCount > 0 ? true : false;
    }

    /**
     * Проверка наличия положительного значения счетчика скобок метода.
     * @return bool
     */
    public function hasMethodBraceCount(): bool
    {
        return $this->methodBraceCount > 0 ? true : false;
    }

    /**
     * Проверка наличия сущности класса.
     * @return bool
     */
    public function hasClass(): bool
    {
        return empty($this->class) ? false : true;
    }

    /**
     * Проверка наличия метода.
     * @return bool
     */
    public function hasMethod(): bool
    {
        return empty($this->method) ? false : true;
    }

    /**
     * Общий инкремент скобок.
     */
    public function incrementBraceCount()
    {
        if ($this->hasMethod()) $this->incrementMethodBraceCount();
        if ($this->hasClass()) $this->incrementClassBraceCount();
    }

    /**
     * Общий декремент скобок.
     */
    public function decrementBraceCount()
    {
        if ($this->hasMethodBraceCount()) $this->decrementMethodBraceCount();
        if ($this->hasClassBraceCount()) $this->decrementClassBraceCount();
    }
}
