<?php
/**
 * @package evas-php\evas-documentor
 *
 * Эндпоинт генерации документации.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 2020-06-19
 */
use Evas\Documentor\Documentor;
use Evas\Base\Loader;

set_exception_handler(function (\Throwable $e) {
    $error = get_class($e) . ': ' . $e->getMessage()
        . ' in ' . $e->getFile() . ':' . $e->getLine();
    die("\e[41m$error\e[0m\n");
});

// директория запускаемых файлов документора
define('BIN_DIR', __DIR__);
// директория файлов документора
define('SRC_DIR', realpath(dirname(BIN_DIR) . '/src/'));
// директория запуска скрипта
define('RUN_DIR', getcwd());

// подключаем автозагрузчик
require_once BIN_DIR . '/../../evas-loader/src/Loader.php';
$loader = (new Loader)->useEvas()->run();


if ($argc < 3 || in_array($argv[1], array('--help', '-help', '-h', '-?'))) {
    die ("help info\n");
}

list(, $in, $out) = $argv;
$in = realpath(RUN_DIR . $in);
$out = realpath(RUN_DIR . $out);

function chooseLang(): string {
    static $stringLangs = null;
    if (null === $stringLangs) {
        $stringLangs = implode('/', Documentor::SUPPORT_LANGS);
    }
    $lang = readline("Choose lang ($stringLangs): ");
    $lang = strtolower(trim($lang));
    if (!in_array($lang, Documentor::SUPPORT_LANGS)) {
        if (!empty($lang)) {
            echo "\e[41m Not supported lang: \e[1m$lang \e[0m\n";
        }
        return chooseLang();
    }
    echo "Choosed lang: \e[1;32m$lang\e[0m\n";
    return $lang;
}

echo <<<EOT
\e[1mWelcome to \e[45m Evas Documentor \e[0m
See documentation on \e[1;4mhttps://documentor.evas-php.com\e[0m
Created by Egor Vasyakin, 2020
License CC-BY-4.0
----------
Source: \e[1m$in\e[0m
Output: \e[1m$out\e[0m

EOT;

// $lang = chooseLang();
$lang = 'php';

echo "\e[1mAre you ready? Start?\e[0m\n";

$start = readline('(Y/N): ');
$start = strtolower(trim($start));
if (!in_array($start, ['y', 'yes', ''])) {
    echo "Excaped and Exit\n";
    return;
}
echo "\e[1mStart\e[0m\n";
echo "----------\n";

$documentor = new Documentor($in, $out, $lang);

