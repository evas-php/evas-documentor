<?php
/**
 * Сущность алиаса пространства имен.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractFileEntity;

class _UseAlias extends AbstractFileEntity
{
    /**
     * @var string имя
     */
    public $name;

    /**
     * @var string псевдоним
     */
    public $as;

    /**
     * Конструктор.
     * @param string имя пространства, возможно, с псевдонимом
     */
    public function __construct(string $name)
    {
        @[$name, $as] = explode(' as ', $name);
        if (!empty($as)) {
            $this->as = trim($as);
        }
        $this->name = trim($name);
    }
}
