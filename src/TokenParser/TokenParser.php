<?php
/**
 * Парсер PHP токенов файла.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @author Evgeniy Erementchouk <erement@evas-php.com>
 * @since 21 Jun 2020
 */
namespace Evas\Documentor\TokenParser;

use Evas\Documentor\Documentor;
use Evas\Documentor\Entities\_File;
use Evas\Documentor\TokenParser\RouteStore;
use Evas\Documentor\TokenParser\Route;
use Evas\Documentor\TokenParser\RouteMap;
use Evas\Documentor\TokenParser\RouteMapInit;

class TokenParser
{
    /**
     * @var Documentor
     */
    public $documentor;
    
    /**
     * Установка процессов парсинга.
     */
    public function setRouteMap()
    {
        RouteMapInit::run();
    }

    /**
     * Конструктор.
     * @param Documentor
     */
    public function __construct(Documentor $documentor)
    {
        $this->documentor = &$documentor;
        $this->setRouteMap();
    }

    /**
     * Запуск парсинга.
     * @param _File входной файл
     * @return RouteStore результат разбора
     */
    public function run(_File $file):RouteStore
    {
        Route::regenerateStorage();
        Route::$store->file = $file;
        $tokens = $file->getTextTokens();

        foreach ($tokens as &$token) {
            debug(sprintf("%s %d %d %d %d\n", 
                is_array($token) ? (token_name($token[0]).','.json_encode($token[1])) : $token,
                count(RouteMap::$current), count(RouteMap::$ended),
                Route::$store->classBraceCount, Route::$store->methodBraceCount
            ));

            $tokenValue = is_string($token) ? $token : $token[1];
            $routes = RouteMap::getCurrent();
            $processed = false;
            if (count($routes) > 0) {
                foreach ($routes as &$route) {
                    $processed = $route->check($token);
                    if ($processed) break;
                    $route->mergeValue($tokenValue);
                }
            }
            if ('{' === $token ) {
                Route::$store->incrementBraceCount();
                continue;
            }
            if ('}' === $token) {
                Route::$store->decrementBraceCount();
                continue;
            }
            if (is_array($token) && $processed == false && ('{' !== $token || '}' !== $token)) {
                $tokenName = token_name($token[0]);
                if ($tokenName === 'T_CURLY_OPEN') {
                    Route::$store->incrementBraceCount();
                    continue;
                }
                $tokenLine = $token[2];
                $route = RouteMap::find($tokenName);
                if (isset($route)) {
                    $route->run($file, $tokenValue, $tokenLine);
                }
            }

        }
        $store = RouteMap::shrinkageMap();
        if (is_null($store->file)) {
            $store->file = $file;
        }
        return $store;
    }
}
