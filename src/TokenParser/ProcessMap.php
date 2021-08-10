<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\TokenParser;

use Evas\Documentor\TokenParser\Process;

/**
 * Маппинг процессов парсинга PHP токенов файла.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 2 Jul 2020
 */
class ProcessMap
{
    /**
     * @var array маппинг процессов.
     */
    public static $map = [];

    /**
     * @static static текущий процесс
     */
    public static $current;

    /**
     * @static static предыдущий процесс
     */
    public static $latest;


    /**
     * Установка процесса/процессов в маппинг.
     * @param array|Process
     */
    public static function set($process)
    {
        assert(is_array($process) || is_a($process, Process::class));
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
     * @return Process|null
     */
    public static function get(string $name = null): ?Process
    {
        return empty($name) ? static::getCurrent() : (static::$map[$name] ?? null);
    }

    /**
     * Установка текущего процесса.
     * @param Process
     */
    public static function setCurrent(Process &$process)
    {
        if (!empty(static::$current)) {
            static::$current->stop();
            static::$latest = static::$current;
        }
        static::$current = &$process;
    }

    /**
     * Получение предыдущего процесса.
     * @return Process|null
     */
    public static function getLatest(): ?Process
    {
        return static::$latest;
    }

    /**
     * Получение текущего процесса.
     * @return Process|null
     */
    public static function getCurrent(): ?Process
    {
        return static::$current;
    }

    /**
     * Получение текущего процесса, если он запущен.
     * @return Process|null
     */
    public static function getRun(): ?Process
    {
        $process = &static::$current;
        return empty($process) || false === $process->isRun() ? null : $process;
    }

    /**
     * Поиск процесса по имени токена.
     * @param string имя токена
     * @return Process|null
     */
    public static function find(string $tokenName): ?Process
    {
        foreach (static::$map as &$process) {
            if (true === $process->isTokenName($tokenName)) {
                return $process;
            }
        }
        return null;
    }
}