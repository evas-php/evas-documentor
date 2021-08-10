<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\Base\AbstractFileEntity;

/**
 * Сущность алиаса пространства имен.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 30 Jun 2020
 */
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
