<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities\Traits;

use Evas\Documentor\Entities\Traits\DocCommentTrait;

/**
 * Трейт поддержки имени, области видимости и статичности у сущностей класса.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1 Jul 2020
 */
trait ClassEntitiesTrait
{
    use DocCommentTrait; // $docComment
    public $name;
    public $visibility = 'public';
    public $staticly = false;
}
