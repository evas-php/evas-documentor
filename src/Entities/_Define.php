<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractFunction;
use Evas\Documentor\Entities\Traits\DocCommentTrait;
use Evas\Documentor\Entities\Traits\NamespaceTrait;

/**
 * Сущность функции файла.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
class _Define extends AbstractFunction
{
    use DocCommentTrait; // $docComment
    use NamespaceTrait; // $namespace
}
