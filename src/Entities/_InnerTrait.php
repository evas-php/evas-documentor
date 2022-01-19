<?php
/**
 * Сущность вложенного трейта.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractFileEntity;
use Evas\Documentor\Entities\Traits\DocCommentTrait;

class _InnerTrait extends AbstractFileEntity
{
    use DocCommentTrait; // $docComment
    /**
     * @var string название
     */
    public $name;

    /**
     * Конструктор.
     * @param string имя подключаемого трейта
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
