<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractFileEntity;
use Evas\Documentor\Entities\Traits\ClassEntitiesTrait;

/**
 * Сущность свойства класса/трейта.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
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
