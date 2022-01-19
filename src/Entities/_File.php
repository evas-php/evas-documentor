<?php
/**
 * Сущность файла.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @author Evgeniy Erementchouk <erement@evas-php.com>
 * @since 30 Jun 2020
 */
namespace Evas\Documentor\Entities;

use Evas\Documentor\Entities\_Include;
use Evas\Documentor\Entities\_Namespace;
use Evas\Documentor\Entities\_UseAlias;
use Evas\Documentor\Entities\Traits\DocCommentTrait;
use Evas\Documentor\Entities\Traits\NamespaceEntitiesTrait;

class _File
{
    use DocCommentTrait; // $docComment
    use NamespaceEntitiesTrait; // $classes, $interfaces, $traits, $functions, $constants
    
    /**
     * @var string путь файла
     */
    public $path;

    /**
     * @var string имя файла
     */
    public $name;

    /**
     * @var string расширение
     */
    public $ext;

    /**
     * @var string текст файла
     */
    protected $text;
    
    /**
     * @var array подключения других файлов в файле
     */
    public $includes = [];

    /**
     * @var array пространства имен файла
     */
    public $namespaces = [];

    /**
     * @var array алиасы пространств имен файла
     */
    public $useAliases = [];

    /**
     * Конструктор.
     * @param string путь файла
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->name = basename($path);
        $dotPos = strrpos($this->name, '.');
        if (false !== $dotPos) {
            $this->ext = substr($this->name, $dotPos + 1);
        }
    }

    /**
     * Получение текста файла.
     * @return string
     */
    public function getText(): string
    {
        if (null === $this->text) {
            $this->text = file_get_contents($this->path);
        }
        return $this->text;
    }

    /**
     * Получение PHP токенов текста файла.
     * @return array
     */
    public function getTextTokens(): array
    {
        return token_get_all($this->getText());
    }

    /**
     * Добавление подключаемого файла.
     * @param _Include
     */
    public function include(_Include $include)
    {
        $this->includes[] = &$include;
    }

    /**
     * Добавление пространства имен.
     * @param _Namespace
     */
    public function namespace(_Namespace $namespace)
    {
        $this->namespaces[] = &$namespace;
    }

    /**
     * Добавление алиаса пространства имен.
     * @param _UseAlias
     */
    public function useAlias(_UseAlias $useAlias)
    {
        $this->useAliases[] = &$useAlias;
    }

    /**
     * Преобразование объекта файла в строку.
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this);
    }
}
