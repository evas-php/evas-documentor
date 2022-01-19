<?php
/**
 * Трейт поддержки комментария docComment.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 11 Jul 2020
 */
namespace Evas\Documentor\Entities\Traits;

trait DocCommentTrait
{
    /**
     * @var string доккоммент
     */
    public $docComment;

    /**
     * Установка доккоммента.
     * @param string доккоммент
     */
    public function docComment(string $docComment)
    {
        $this->docComment = $docComment;
    }
}
