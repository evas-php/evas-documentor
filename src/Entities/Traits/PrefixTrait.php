<?php
/**
 * Трейт поддержки префикса.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1 Jul 2020
 */
namespace Evas\Documentor\Entities\Traits;

trait PrefixTrait
{
    public $prefix; // abstract|final|null

    public function prefix(string $prefix)
    {
        $this->prefix = $prefix;
    }
}
