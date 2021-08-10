<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities\Traits;

/**
 * Трейт поддержки комментария docComment.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 11 Jul 2020
 */
trait DocCommentTrait
{
    public $docComment;

    public function docComment(string $docComment)
    {
        $this->docComment = $docComment;
    }
}
