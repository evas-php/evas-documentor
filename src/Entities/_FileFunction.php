<?php
/**
 * Сущность функции файла.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractFunction;
use Evas\Documentor\Entities\Traits\NamespaceTrait;

class _FileFunction extends AbstractFunction
{
    use NamespaceTrait; // $namespace
}
