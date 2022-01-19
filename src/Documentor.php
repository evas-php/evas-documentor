<?php
/**
 * Documentor endpoint.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 20 Jun 2020
 */
namespace Evas\Documentor;

use Evas\Documentor\Entities\_File;
use Evas\Documentor\Entities\Base\EntityNames;
use Evas\Documentor\DocumentorException;
use Evas\Documentor\TokenParser\TokenParser;

class Documentor
{
    /**
     * @static array поддерживаемые языки
     */
    const SUPPORT_LANGS = ['php', 'py', 'c', 'cpp'];

    /**
     * @static array расширения файлов языка
     */
    const LANG_EXTS = [
        'php' => ['php'],
        'py' => ['py'],
        'c' => ['c', 'h'],
        'cpp' => ['cpp', 'h', 'hpp'],
    ];

    public $lang;
    public $tokenParser;

    public $parsed = [];

    public $dirCount = 0;
    public $fileCount = 0;
    public $level = 0;


    const MAX_TRIES_COUNT = 15;
    const MAX_NESTING_LEVEL = 50;

    /**
     * @param string директория документирования
     * @param string директория вывода
     * @param string язык
     * @throws DocumentorException
     */
    public function __construct(string $in, string $out, string $lang)
    {
        if (!in_array($lang, static::SUPPORT_LANGS)) {
            throw new DocumentorException("Not supported lang: $lang");
        }
        $this->lang = $lang;
        $this->tokenParser = new TokenParser($this);
        
        if (is_file($in)) $this->scanFile($in);
        else if (is_dir($in)) $this->scanDir($in);
        else {
            throw new DocumentorException('Указанное для чтения значение не является файлом или директорией');
        }
        $this->shrinkParsedStores();
        $this->line('----------');
        $this->line("Просканировано директорий: \e[1;35m$this->dirCount\e[0m");
        $this->line("Просканировано файлов: \e[1;32m$this->fileCount\e[0m");

        if (is_file($out)) $this->outFile($out);
        else if (is_dir($out)) $this->outDir($out);
        else {
            throw new DocumentorException('Указанное для записи значение не является файлом или директорией');
        }
    }

    /**
     * Вывод строки в консоль.
     * @param string сообщение
     */
    public function line(string $message)
    {
        echo str_pad('', 2 * $this->level) . $message . "\n";
    }

    /**
     * Сканирование директории.
     * @param string путь директории
     * @throws DocumentorException
     */
    public function scanDir(string $dir)
    {
        static $try = 0;
        $try++;
        if ($try > static::MAX_TRIES_COUNT) {
            throw new DocumentorException("Maximum tries exceeded ($try)");
        }
        if ($this->level > static::MAX_NESTING_LEVEL) {
            throw new DocumentorException("Maximum directory nesting level exceeded ($level)");
        }
        $this->dirCount++;
        $this->line("Dir: \e[1;35m$dir\e[0m");
        $this->level++;
        $scan = scandir($dir);
        $dirs = [];
        $files = [];
        foreach ($scan as &$item) {
            if (in_array($item, ['.', '..', '.git'])) continue;
            $file = realpath("$dir/$item");
            if (is_dir($file)) {
                $dirs[] = $file;
            } else if (is_file($file)) {
                $files[] = $file;
            }
        }
        if ($dirs) foreach ($dirs as &$dir) {
            if ($try > static::MAX_TRIES_COUNT) {
                return false;
            }
            $this->scanDir($dir);
        }
        if ($files) foreach ($files as &$file) {
            $this->scanFile($file);
        }
        $this->level--;
        $try--;
    }


    /**
     * Сканирование файла.
     * @param string путь файла
     */
    public function scanFile(string $path)
    {
        $file = new _File($path);
        $exts = static::LANG_EXTS[$this->lang];
        if (!in_array($file->ext, $exts)) {
            $this->line("Ignore: $path");
            return;
        }
        $this->fileCount++;
        $this->line("Parsing: \e[1;32m$path\e[0m");
        $this->level++;
        $this->parsed[] = $this->tokenParser->run($file);
        $try = 0;
        if (VERBOSE) {
            $this->line("\e[33m------------------------------\e[0m");
            $this->iterationView($this->parsed[count($this->parsed)-1]);
            $this->line("\e[33m------------------------------\e[0m");
        }
        $this->level--;
        if (VERBOSE) readline('Press any key to continue: ');
    }

    /**
     * Глубокий разбор и выдача в командную строку обьекта.
     * @param object entity
     */
    public function iterationView($entity)
    {
        $buf = [];
        foreach ($entity as $name => &$value) {
            if (is_numeric($name)) {
                $key = "\e[36m$name\e[0m";
            } else {
                $key = "\e[33m\"$name\"\e[0m";
            }
            if (is_array($value) || is_object($value)) {
                if (!empty($buf)) {
                    $buf = implode(', ', $buf);
                    $this->showLines($buf);
                    $buf = [];
                }
                $brace = is_object($value) ? true : false;
                $openBrace = $brace ? '{' : '[';
                $closeBrace = $brace ? '}' : ']';
                if (empty($value)) {
                    $openBrace = '[]';
                    $closeBrace = null;
                } 
                $this->line("$key: $openBrace");
                if (!empty($closeBrace)) {
                    $this->level++;
                    $this->iterationView($value);
                    $this->level--;
                    $this->line($closeBrace);
                }
            } else {
                $val = $value;
                if (is_string($val)) {
                    $val = "\e[32m\"$val\"\e[0m";
                } else {
                    if (is_bool($val)) {
                        $val = true === $value ? 'true': 'false';
                        $val = "\e[36m$val\e[0m";
                    } else if (is_null($val)) {
                        $val = 'null';
                        $val = "\e[41m$val\e[0m";
                    } else {
                        $val = "\e[36m$val\e[0m";
                    }
                }
                $buf[] = "$key: $val";
            }
        }
        if (!empty($buf)) {
            $buf = implode(', ', $buf);
            $this->showLines($buf);
            $buf = [];
        }
    }

    /**
     * Отображение текста.
     * @param object entity
     */
    public function showLines(string $text)
    {
        static $maxTry = 30;
        static $lineLength = 200;
        $try = 0;
        while (strlen($text) > 0) {
            $try++;
            $line = substr($text, 0, $lineLength);
            $lineEnd = strrpos($line, ',');
            if (false === $lineEnd) {
                $lineEnd = strlen($line);
            } else {
                $lineEnd++;
                $line = substr($line, 0, $lineEnd);
            }
            $text = substr($text, $lineEnd);
            $this->line($line);
            if ($try > $maxTry) die ("\e[31mMax Try Print error\e[0m\n");
        }
    }

    /**
     * Переработка собранных неймспейсов.
     */
    public function shrinkParsedStores()
    {
        $namespaces = [];
        foreach ($this->parsed as &$store) {
            if (!isset($store->namespace)) {
                if (!isset($namespaces['UNRECOGNIZED'])) {
                    $namespaces['UNRECOGNIZED'] = [];
                }
                $namespaces['UNRECOGNIZED'][] = $store;
                continue;
            }
            if (!isset($namespaces[$store->namespace->name])) {
                $namespaces[$store->namespace->name] = $store->namespace;
            }
            if (!isset($store->classEntity)) {
                $namespaces[$store->namespace->name]->functions = array_merge($namespaces[$store->namespace->name]->functions,$store->namespace->functions);
                $namespaces[$store->namespace->name]->constants = array_merge($namespaces[$store->namespace->name]->constants,$store->namespace->constants);
                continue;
            }
            if (is_a($store->classEntity, EntityNames::_CLASS)) {
                $namespaces[$store->namespace->name]->classes[$store->classEntity->name] = $store->classEntity;
            } else
            if (is_a($store->classEntity, EntityNames::_INTERFACE)) {
                $namespaces[$store->namespace->name]->interfaces[$store->classEntity->name] = $store->classEntity;
            } else
            if (is_a($store->classEntity, EntityNames::_TRAIT)) {
                $namespaces[$store->namespace->name]->traits[$store->classEntity->name] = $store->classEntity;
            }            
        }
        $this->parsed = $namespaces;
    }

    /**
     * Сохранение результатов в файл.
     */
    public function outFile(string $file, $namespace = null)
    {
        if (is_null($namespace)) {
            $namespace = &$this->parsed;
        }
        file_put_contents($file, json_encode($namespace,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->line("\e[1;32mСохранен файл \e[1;35m".$file."\e[0m");
    }

    /**
     * Сохранение результатов в директорию.
     */
    public function outDir(string $dir)
    {
        foreach ($this->parsed as $name => $namespace) {
            $this->outFile($dir.'\\'.str_replace('\\','',$name).'.json',$namespace);
        }
    }
}
