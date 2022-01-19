<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\TokenParser;

use Evas\Documentor\TokenParser\Route;
use Evas\Documentor\TokenParser\RouteStore;

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
     * @static static законченные процессы
     */
    public static $ended = [];

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
    public static function set($route)
    {
        assert(is_array($route) || is_a($route, Route::class));
        if (is_array($route)) {
            foreach ($route as &$subroute) {
                static::set($subroute);
            }
        } else {
            static::$map[$route->name] = &$route;
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
    public static function setCurrent(Route $route)
    {
        // if (!empty(static::$current)) {
        //     static::$current->stop();
        //     static::$latest = static::$current;
        // }
        static::$current[] = &$route;
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
    public static function getCurrent(): array
    {
        return static::$current;
    }

    /**
     * Завершение процесса.
     */
    public static function shut(Route $route)
    {
        $pos = array_search($route, static::$current);
        array_splice(static::$current, $pos, 1);
        static::$ended[] = $route;
    }

    /**
     * Очистка маппера и возврат результатов.
     * @return RouteStore
     */
    public static function shrinkageMap(): RouteStore
    {
        if (count(static::$ended)>0) {
            $store = clone static::$ended[0]::$store;
            static::$current = [];
            static::$ended = [];
            return $store;
        }
        return new RouteStore;
    }

    /**
     * Поиск процесса по имени токена.
     * @param string имя токена
     * @return Route|null
     */
    public static function find(string $tokenName): ?Route
    {
        foreach (static::$map as &$route) {
            if (true === $route->isTokenName($tokenName)) {
                return $route;
            }
        }
        return null;
    }
}