<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\TokenParser\Traits;

use Evas\Documentor\Entities\_File;
use Evas\Documentor\TokenParser\Process;

/**
 * Трейт свойств процесса парсинга PHP токенов.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 21 Jun 2020
 */
trait ProcessPropertiesTrait
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
    public function tokenName(...$args): Process
    {
        $this->tokenName = $args;
        return $this;
    }

    /**
     * Установка символа окончания.
     * @param string символ
     * @return self
     */
    public function endSymbol(...$args): Process
    {
        $this->endSymbol = $args;
        return $this;
    }

    /**
     * Установка символа перечисления значений.
     * @param string символ
     * @return self
     */
    public function enumSymbol(string $symbol): Process
    {
        $this->enumSymbol = $symbol;
        return $this;
    }

    /**
     * Установка символа остановки.
     * @param string символ
     * @return self
     */
    public function stopSymbol(string $symbol): Process
    {
        $this->stopSymbol = $symbol;
        return $this;
    }

    /**
     * Установка условия запуска.
     * @param callable обработчик условия запуска
     * @return self
     */
    public function runCondition(callable $callback): Process
    {
        $this->runCondition = $callback->bindTo($this);
        return $this;
    }

    /**
     * Установка обработчика окончания.
     * @param callable обработчик условия запуска
     * @return self
     */
    public function endCallback(callable $callback): Process
    {
        $this->endCallback = $callback->bindTo($this);
        return $this;
    }


    /**
     * Склеивание значения.
     * @param string
     * @return self
     */
    public function mergeValue(string $value): Process
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
