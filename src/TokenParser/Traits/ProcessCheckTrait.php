<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\TokenParser\Traits;

use Evas\Documentor\Entities\_File;

/**
 * Трейт проверок процесса парсинга PHP токенов.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 21 Jun 2020
 */
trait ProcessCheckTrait
{
    /**
     * Проверка на соответствие символа значению свойства класса.
     * @param string имя свойства
     * @param string символ для проверки
     * @return bool
     */
    protected function isSymbol(string $name, string $sym): bool
    {
        $var = $this->$name;
        return is_array($var) && in_array($sym, $var) || $sym === $var ? true : false;
    }

    /**
     * Проверка соответстивия на символ окончания.
     * @param string символ для проверки
     * @return bool
     */
    public function isEndSymbol(string $symbol): bool
    {
        return $this->isSymbol('endSymbol', $symbol);
    }

    /**
     * Проверка соответстивия на символ перечисления.
     * @param string символ для проверки
     * @return bool
     */
    public function isEnumSymbol(string $symbol): bool
    {
        return $this->isSymbol('enumSymbol', $symbol);
    }

    /**
     * Проверка соответстивия на символ остановки.
     * @param string символ для проверки
     * @return bool
     */
    public function isStopSymbol(string $symbol): bool
    {
        return $this->isSymbol('stopSymbol', $symbol);
    }

    /**
     * Проверка соответстивия токена начала.
     * @param string имя токена
     * @return bool
     */
    public function isTokenName(string $tokenName): bool
    {
        return $this->isSymbol('tokenName', $tokenName);
    }

    /**
     * Проверка на условие запуска.
     * @return bool
     */
    public function isCondition(): bool
    {
        // если условия запуска нет или если есть и выполнимо
        $runCondition = $this->runCondition;
        return empty($runCondition) || true === $runCondition() ? true : false;
        
    }
}
