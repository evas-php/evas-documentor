<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\TokenParser;

use Evas\Documentor\TokenParser\Route;

/**
 * Маппинг процессов парсинга PHP токенов файла.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 2 Jul 2020
 */
class RouteMap
{
    /**
     * @var array маппинг процессов.
     */
    public static $map = [];

    /**
     * @static static текущие процессы
     */
    public static $current = [];

    /**
     * @static static предыдущий процесс
     */
    public static $latest;


    /**
     * Установка процесса/процессов в маппинг.
     * @param array|Route
     */
    public static function set($process)
    {
        assert(is_array($process) || is_a($process, Route::class));
        if (is_array($process)) {
            foreach ($process as &$subprocess) {
                static::set($subprocess);
            }
        } else {
            static::$map[$process->name] = &$process;
        }
    }

    /**
     * Получение процесса из маппинга или текущего.
     * @param string|null имя процесса
     * @return Route|null
     */
    public static function get(string $name = null): ?Route
    {
        return empty($name) ? static::getCurrent() : (static::$map[$name] ?? null);
    }

    /**
     * Установка текущего процесса.
     * @param Route
     */
    public static function setCurrent(Route &$process)
    {
        if (!empty(static::$current)) {
            static::$current->stop();
            static::$latest = static::$current;
        }
        static::$current = &$process;
    }

    /**
     * Получение предыдущего процесса.
     * @return Route|null
     */
    public static function getLatest(): ?Route
    {
        return static::$latest;
    }

    /**
     * Получение текущего процесса.
     * @return Route|null
     */
    public static function getCurrent(): ?Route
    {
        return static::$current;
    }

    /**
     * Получение текущего процесса, если он запущен.
     * @return Route|null
     */
    public static function getRun(): ?Route
    {
        $process = &static::$current;
        return empty($process) || false === $process->isRun() ? null : $process;
    }

    /**
     * Поиск процесса по имени токена.
     * @param string имя токена
     * @return Route|null
     */
    public static function find(string $tokenName): ?Route
    {
        foreach (static::$map as &$process) {
            if (true === $process->isTokenName($tokenName)) {
                return $process;
            }
        }
        return null;
    }
}