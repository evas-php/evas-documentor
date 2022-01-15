<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\TokenParser;

use Evas\Documentor\Documentor;
use Evas\Documentor\Entities\_File;
use Evas\Documentor\TokenParser\RouteStore;
use Evas\Documentor\TokenParser\Route;
use Evas\Documentor\TokenParser\RouteMap;
use Evas\Documentor\TokenParser\RouteMapInit;

/**
 * Парсер PHP токенов файла.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 21 Jun 2020
 */
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
        // ProcessMap::set([
        RouteMapInit::run();
        // ]);
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
     * @param _File
     */
    public function run(_File $file)
    {
        $tokens = $file->getTextTokens();

        foreach ($tokens as &$token) {
            usleep(150000);
            echo json_encode( $token);
            echo '   '.count(RouteMap::$current).'  '.count(RouteMap::$ended);
            echo "\n";
            if (is_string($token)) {
                $tokenValue = $token;
            } else
                $tokenValue = $token[1];
            $routes = RouteMap::getCurrent();
            $processed = false;
            if (count($routes)>0) {
                foreach ($routes as &$route) {
                    $processed = $route->check($token);
                    if (!$processed) {
                        $route->mergeValue($tokenValue);
                        continue;
                    } else break;
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
    }
}
