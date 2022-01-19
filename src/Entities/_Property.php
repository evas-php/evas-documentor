<?php
/**
 * Сущность свойства класса/трейта.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractFileEntity;
use Evas\Documentor\Entities\Traits\ClassEntitiesTrait;

class _Property extends AbstractFileEntity
{
    use ClassEntitiesTrait; // $name, $visibility, $staticly
    public $default;

    public function __construct (string $name)
    {
        @[$name, $default] = explode('=', $name);
        if (!empty($default)) {
            $this->default = trim($default);
        }
        $this->name = trim($name);
    }
}
