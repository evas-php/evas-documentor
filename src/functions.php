<?php
/**
 * Вспомогательные функции.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 */

/**
 * Дебаг режим для разработчика.
 * @param mixed сообщение
 */
function debug($message) {
    if (!defined('DEV_DEBUG') || boolval(DEV_DEBUG) === false) return;
    if (is_array($message) || is_object($message)) $message = json_encode($message, JSON_UNESCAPED_UNICODE);
    else if ($message instanceof \Closure) $message = '\\Closure';
    echo $message;
}
