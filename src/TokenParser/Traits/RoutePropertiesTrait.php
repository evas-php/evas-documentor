<?php
/**
 * Трейт свойств процесса парсинга PHP токенов.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @author Evgeniy Erementchouk <erement@evas-php.com>
 * @since 21 Jun 2020
 */
namespace Evas\Documentor\TokenParser\Traits;

use Evas\Documentor\Entities\_File;
use Evas\Documentor\TokenParser\Route;

trait RoutePropertiesTrait
{
    /** @var string имя процесса */
    public $name;
    /** @var string значение процесса */
    public $value;
    /** @var _File */
    public $file;

    /** @var string значение токена */
    public $tokenValue;

    /** @var int номер строки начала процесса */
    public $startLine;
    /** @var array токены процесса */
    public $tokenName = [];
    /** @var string символ окончания процесса */
    public $endSymbol;
    /** @var string символ перечисления значений */
    public $enumSymbol;
    /** @var string символ окончания процесса */
    public $stopSymbol;

    /** @var callable условие запуска */
    public $runCondition;

    /** @var callable обработчик внесения значения */
    public $endCallback;

    /** @var bool запущен ли процесс */
    public $isRun = false;

    /**
     * Установка токена/токенов процесса.
     * @param ... токены
     * @return self
     */
    public function tokenName(...$args): Route
    {
        $this->tokenName = $args;
        return $this;
    }

    /**
     * Установка символа окончания.
     * @param string символ
     * @return self
     */
    public function endSymbol(...$args): Route
    {
        $this->endSymbol = $args;
        return $this;
    }

    /**
     * Установка символа перечисления значений.
     * @param string символ
     * @return self
     */
    public function enumSymbol(string $symbol): Route
    {
        $this->enumSymbol = $symbol;
        return $this;
    }

    /**
     * Установка символа остановки.
     * @param string символ
     * @return self
     */
    public function stopSymbol(string $symbol): Route
    {
        $this->stopSymbol = $symbol;
        return $this;
    }

    /**
     * Установка условия запуска.
     * @param callable обработчик условия запуска
     * @return self
     */
    public function runCondition(callable $callback): Route
    {
        $this->runCondition = $callback->bindTo($this);
        return $this;
    }

    /**
     * Установка обработчика окончания.
     * @param callable обработчик условия запуска
     * @return self
     */
    public function endCallback(callable $callback): Route
    {
        $this->endCallback = $callback->bindTo($this);
        return $this;
    }


    /**
     * Склеивание значения.
     * @param string
     * @return self
     */
    public function mergeValue(string $value): Route
    {
        $this->value .= $value;
        return $this;
    }

    /**
     * Получение значения.
     * @return string
     */
    public function getValue(): string
    {
        return trim($this->value);
    }
}
