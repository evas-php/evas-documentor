<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractFileEntity;
use Evas\Documentor\Entities\Traits\DocCommentTrait;

/**
 * Сущность вложенного трейта.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
class _InnerTrait extends AbstractFileEntity
{
    use DocCommentTrait; // $docComment
    public $traitName;

    /**
     * Конструктор.
     * @param string имя подключаемого трейта
     */
    public function __construct(string $traitName)
    {
        $this->traitName = $traitName;
    }
}
