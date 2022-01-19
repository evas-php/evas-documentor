<?php
/**
 * Трейт поддержки имени, области видимости и статичности у сущностей класса.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1 Jul 2020
 */
namespace Evas\Documentor\Entities\Traits;

use Evas\Documentor\Entities\Traits\DocCommentTrait;

trait ClassEntitiesTrait
{
    use DocCommentTrait; // $docComment

    /**
     * @var string Название сущности
     */
    public $name;

    /**
     * @var string область видимости
     */
    public $visibility = 'public';

    /**
     * @var string статичность
     */
    public $staticly = false;
}
