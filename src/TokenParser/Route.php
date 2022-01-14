<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\TokenParser;

use Evas\Documentor\Entities\_File;
use Evas\Documentor\TokenParser\RouteMap;
use Evas\Documentor\TokenParser\Traits\RouteCheckTrait;
use Evas\Documentor\TokenParser\Traits\RoutePropertiesTrait;
use Evas\Documentor\TokenParser\RouteStore;

/**
 * Процесс парсинга PHP токенов файла.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 21 Jun 2020
 */
class Route
{
    /**
     * Подключаем трейт свойств процесса.
     * Подключаем трейт проверок процесса.
     */
    use RoutePropertiesTrait, RouteCheckTrait;

    public RouteStore $store;


    /**
     * Конструктор процесса.
     * @param string имя
     */
    public function __construct(string $name, RouteStore $store)
    {
        $this->name = $name;
        $this->store = new RouteStore;
    }

    /**
     * Завершение процесса, если соответсует символ.
     * @param string символ
     * @return self
     */
    public function endIfSymbol(string $symbol): Route
    {
        if ($this->isEndSymbol($symbol)) {
            return $this->end($symbol);
        }
        return $this;
    }

    /**
     * Перечисление значения, если соответсует символ.
     * @param string символ
     * @return self
     */
    public function enumIfSymbol(string $symbol): Route
    {
        if ($this->isEnumSymbol($symbol)) {
            return $this->enum($symbol);
        }
        return $this;
    }

    /**
     * Остановка процесса, если соответсует символ.
     * @param string символ
     * @return self
     */
    public function stopIfSymbol(string $symbol): Route
    {
        if ($this->isStopSymbol($symbol)) {
            return $this->stop($symbol);
        }
        return $this;
    }

    /**
     * Запуск процесса.
     * @return self|null
     */
    public function run(_File &$file, string $tokenValue, int $tokenLine): ?Route
    {
        echo "Run Route: \e[35m$this->name\e[0m \e[36m$tokenValue\e[0m\n";
        $this->value = '';
        $this->entity = null;
        $this->file = &$file;
        $this->tokenValue = $tokenValue;
        $this->startLine = $tokenLine;
        echo 1;
        var_dump($this->isCondition());
        if (false === $this->isCondition()) return null;
        $this->isRun = true;
        RouteMap::setCurrent($this);
        // если нет завершающего символа
        echo 2;
        var_dump(empty($this->endSymbol));
        // var_dump($this);
        if (empty($this->endSymbol)) {
            // помещаем значение токена в значение и завершаем процесс
            $this->mergeValue($tokenValue);
            $this->end();
        }
        return $this;
    }

    /**
     * Проверка на запущенность процесса.
     * @return bool
     */
    public function isRun(): bool
    {
        return $this->isRun ? true : false;
    }

    /**
     * Создание сущности.
     * @param string имя класса
     * @return object
     */
    public function makeEntity(string $className): object
    {
        $entity = new $className($this->getValue());
        // if (isset($entity->file)) {
            // $entity->file = $this->file;
            $entity->line = $this->startLine;
        // }
        return $entity;
    }

    /**
     * Запуск колбека завершения
     * @param string|null символ запуска колбэка
     */
    public function runEndCallback(string $value = null)
    {
        $endCallback = $this->endCallback;
        if (!empty($endCallback)) {
            echo "\e[32mRun endCalback\e[0m\n";
            $endCallback($value);
        }
        else echo "\e[31mendCalback is empty\e[0m\n";
    }

    /**
     * Остановка процесса.
     * @return self
     */
    public function stop(string $value = null): Route
    {
        echo "Stop Route: \e[35m$this->name\e[0m \e[36m$value\e[0m\n";
        $this->isRun = false;
        return $this;
    }

    /**
     * Обработка перечисляемого значения.
     * @return self
     */
    public function enum(string $value): Route
    {
        echo "Enum Route: \e[35m$this->name\e[0m \e[36m$value\e[0m\n";
        $this->runEndCallback($value);
        $this->value = '';
        $this->entity = null;
        return $this;
    }

    /**
     * Завершение процесса.
     * @return self
     */
    public function end(string $value = null): Route
    {
        echo "End Route: \e[35m$this->name\e[0m \e[36m$value\e[0m\n";
        $this->runEndCallback($value);
        if ('functionArgs' === $this->name) {
            $endSymbols = implode('","', $this->endSymbol);
            echo "\e[41m EndSymbol is = [\"$endSymbols\"] \e[0m\n";
        }
        return $this->stop();
    }

    /**
     * Запуск следующего процесса.
     * @param string имя процесса
     * @param string символ начала процесса
     * @return Route новый процесс
     */
    public function runNextRoute(string $name, string $symbol): Route
    {
        $Route = RouteMap::get($name);
        return $Route->run($this->file, $symbol, $this->startLine);
    }
}
