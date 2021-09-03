<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\TokenParser;

use Evas\Documentor\Documentor;
use Evas\Documentor\Entities\_File;
use Evas\Documentor\TokenParser\TokenParserStore;
use Evas\Documentor\TokenParser\Process;
use Evas\Documentor\TokenParser\ProcessMap;
use Evas\Documentor\TokenParser\ProcessMapInit;

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
    public function setProcessMap()
    {
        ProcessMapInit::run();
        // ProcessMap::set([
        // ]);
    }

    /**
     * Конструктор.
     * @param Documentor
     */
    public function __construct(Documentor $documentor)
    {
        $this->documentor = &$documentor;
        $this->setProcessMap();
    }

    /**
     * Запуск парсинга.
     * @param _File
     */
    public function run(_File $file)
    {
        $store = new TokenParserStore($file);
        $store->file = &$file;
        $tokens = $file->getTextTokens();
        foreach ($tokens as &$token) {
            $process = ProcessMap::getRun();
            if (is_array($token)) {
                $tokenValue = $token[1];
                $tokenLine = $token[2];
                if (empty($process)) {
                    $tokenName = token_name($token[0]);
                    $process = ProcessMap::find($tokenName);
                    if (!empty($process)) {
                        $process->run($file, $tokenValue, $tokenLine);
                    }
                    continue;
                }
            }
            if (!empty($process)) {
                if (is_string($token)) {
                    $tokenValue = $token;
                }
                $process->endIfSymbol($tokenValue);
                $process->enumIfSymbol($tokenValue);
                $process->stopIfSymbol($tokenValue);

                if ('{' === $token) {
                    $store->incrementBraceCount();
                    continue;
                }
                else if ('}' === $token) {
                    $store->decrementBraceCount();
                    continue;
                }

                if ($process->isRun()) {
                    $process->mergeValue($tokenValue);
                }
            }
        }
    }
}
