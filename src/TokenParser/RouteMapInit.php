<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\TokenParser;

use Evas\Documentor\Entities\Base\EntityNames;
use Evas\Documentor\TokenParser\Route;
use Evas\Documentor\TokenParser\RouteMap;

/**
 * Установщик маппинга процессов парсинга.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 21 Jun 2020
 */
class RouteMapInit
{
    public static function run()
    {
        RouteMap::set([
             (new Route('namespace'))
                ->tokenName('T_NAMESPACE')
                ->runCondition(function () {
                    return empty(static::$store->classEntity) ? true : false;
                })
                ->endSymbol(';')
                ->endCallback(function () {
                    static::$store->namespace = $this->makeEntity(EntityNames::_NAMESPACE);
                    // static::$store->namespace = $this->getValue();
                    static::$store->setNamespace($this->file);
                    // $this->file->namespace(static::$store->namespace);
                }),

            (new Route('use'))
                ->tokenName('T_USE')
                ->enumSymbol(',')
                ->stopSymbol('(') // игнорируем use анонимных функций
                ->endSymbol(';')
                ->endCallback(function () {
                    if (empty(static::$store->classEntity)) {
                        $temp = $this->makeEntity(EntityNames::_USE_ALIAS);
                        if (empty(static::$store->useAliases)) {
                            static::$store->useAliases = [];
                        }
                        static::$store->useAliases[] = $temp;
                    } else {
                        $innerTrait = $this->makeEntity(EntityNames::_INNER_TRAIT);
                        static::$store->setDocComment($innerTrait);
                        static::$store->classEntity->trait($innerTrait);
                    }
                }),


            (new Route('doubleColon'))
                ->tokenName('T_DOUBLE_COLON')
                ->endCallback(function () {
                    static::$store->doubleColon = true;
                }),
            (new Route('prefix'))
                ->tokenName('T_ABSTRACT', 'T_FINAL')
                ->endCallback(function () {
                    static::$store->prefix = $this->getValue();
                }),

            (new Route('classEntity'))
                ->tokenName('T_CLASS', 'T_INTERFACE', 'T_TRAIT')
                ->runCondition(function () {
                    if (false === static::$store->doubleColon || !isset(static::$store->doubleColon)) {
                        return true;
                    } else {
                        static::$store->doubleColon = false;
                        return false;
                    }
                })
                ->endSymbol('{')
                ->endCallback(function () {
                    $classEntity = $this->makeEntity(EntityNames::get($this->tokenValue));
                    $classEntity->file($this->file);
                    static::$store->setPrefix($classEntity)
                        ->setUseAliases($classEntity)
                        ->setDocComment($classEntity);

                    if (isset(static::$store->namespace)) {
                        $classEntity->namespace(static::$store->namespace);
                    }
                    static::$store->classEntity = $classEntity;
                    static::$store->class = &$classEntity;
                }),

            (new Route('visibility'))
                ->tokenName('T_PUBLIC', 'T_PROTECTED', 'T_PRIVATE')
                ->endCallback(function () {
                    static::$store->visibility = $this->getValue();
                }),
            (new Route('staticly'))
                ->tokenName('T_STATIC')
                ->runCondition(function () {
                    return !empty(static::$store->classEntity) && empty(static::$store->method) ? true : false;
                })
                ->endCallback(function () {
                    static::$store->staticly = $this->getValue() === 'static' ? true : false;
                }),
            (new Route('functionName'))
                ->tokenName('T_FUNCTION')
                ->endSymbol('(')
                ->endCallback(function () {
                    if (empty($this->getValue())) return;
                    if (empty(static::$store->namespace)) return;
                    $className = empty(static::$store->class) 
                        ? EntityNames::_FILE_FUNCTION : EntityNames::_METHOD;
                    $method = $this->makeEntity($className);
                    if (empty(static::$store->class)) {
                        $this->file->addFunction($method);
                        static::$store->namespace->addFunction($method);
                    } else {
                        static::$store->setVisabilityAndStaticly($method)
                            ->setPrefix($method)
                            ->setDocComment($method);
                        static::$store->classEntity->method($method);
                    }
                    static::$store->method = &$method;
                    $this->runNextRoute('functionArgs', '(');
                }),
            // (new Route('include'))
            //     ->tokenName('T_INCLUDE', 'T_INCLUDE_ONCE', 'T_REQUIRE', 'T_REQUIRE_ONCE')
            //     ->runCondition(function () {
            //         var_dump(static::$store->classEntity);
            //         return empty(static::$store->classEntity) ? true : false;
            //     })
            //     ->endSymbol(';')
            //     ->endCallback(function () {
            //         $include = $this->makeEntity(EntityNames::_INCLUDE);
            //         var_dump($this->getValue());
            //         static::$store->file->include($include);
            //     }),
            (new Route('functionArgs'))
                ->enumSymbol(',')
                ->endSymbol(')')
                ->endCallback(function (string $symbol = null) {
                    if (!empty($this->getValue())) {
                        $arg = $this->makeEntity(EntityNames::_FUNCTION_ARG);
                        static::$store->method->arg($arg);
                    }
                    if ($this->isEndSymbol($symbol)) {
                        $this->runNextRoute('functionReturnType', ')');
                    }
                }),
            (new Route('functionReturnType'))
                ->endSymbol(';', '{')
                ->endCallback(function () {
                    $value = $this->getValue();
                    if (':' !== substr($value, 0, 1)) return;
                    $this->value = substr($this->getValue(), 1);
                    $returnType = $this->makeEntity(EntityNames::_FUNCTION_RETURN_TYPE);
                    static::$store->method->returnType($returnType);
                    if ($this->isEndSymbol('{')) {
                        static::$store->incrementMethodBraceCount();
                    }
                }),

            (new Route('property'))
                ->tokenName('T_VARIABLE')
                ->endSymbol(';')
                ->runCondition(function () {
                    return !empty(static::$store->classEntity) && empty(static::$store->method)
                        ? true : false;
                })
                ->endCallback(function () {
                    $this->value = "$this->tokenValue $this->value";
                    $property = $this->makeEntity(EntityNames::_PROPERTY);
                    static::$store->setVisabilityAndStaticly($property)
                        ->setDocComment($property);
                    static::$store->classEntity->property($property);
                }),

            (new Route('const'))
                ->tokenName('T_CONST')
                ->endSymbol(';')
                ->runCondition(function () {
                    return !empty(static::$store->classEntity) ? true : false;
                })
                ->endCallback(function () {
                    $constant = $this->makeEntity(EntityNames::_CLASS_CONSTANT);
                    static::$store->setDocComment($constant);
                    static::$store->classEntity->constant($constant);
                }),
            (new Route('define'))
                ->tokenName('T_STRING')
                ->endSymbol(';')
                ->runCondition(function () {
                    if (VERBOSE) 
                    echo "$this->name: $this->tokenValue, $this->value\n";
                        static::$store->doubleColon = false;
                    return 'define' === $this->tokenValue ? true : false;
                })
                ->endCallback(function () {
                    $constant = $this->makeEntity(EntityNames::_FILE_CONSTANT);
                    $constant->namespace(static::$store->namespace);
                    static::$store->file->constant($constant);
                }),
            (new Route('docComment'))
                ->tokenName('T_DOC_COMMENT')
                ->endCallback(function () {
                    static::$store->docComment = $this->getValue();
                    // if (!isset(static::$store->namespace) && !isset(static::$store->inEntity)) {
                        // static::$store->setDocComment(static::$store->docComment);
                    // }
                }),
            (new Route('comment'))
                ->tokenName('T_COMMENT')
                ->endCallback(function () {
                    // static::$store->comment = $this->getValue();
                }),
            (new Route('throw'))
                ->tokenName('T_THROW')
                ->endCallback(function () {
                    // 
                }),
            (new Route('global'))
                ->tokenName('T_GLOBAL')
                ->endCallback(function () {
                    // 
                })
        ]);
    }
}
