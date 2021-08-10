<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities\Traits;

/**
 * Трейт поддержки префикса.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1 Jul 2020
 */
trait PrefixTrait
{
    public $prefix; // abstract|final|null

    public function prefix(string $prefix)
    {
        $this->prefix = $prefix;
    }
}
