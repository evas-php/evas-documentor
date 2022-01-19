<?php
/**
 * Трейт поддержки неймспейса.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1 Jul 2020
 */
namespace Evas\Documentor\Entities\Traits;

use Evas\Documentor\Entities\_Namespace;

trait NamespaceTrait
{
    /**
     * @var _Namespace|null
     */
    protected $namespace;

    /**
     * Установка пространства имен.
     * @param _Namespace
     * @return self
     */
    public function namespace(_Namespace &$namespace): object
    {
        $this->namespace = &$namespace;
        return $this;
    }
}
