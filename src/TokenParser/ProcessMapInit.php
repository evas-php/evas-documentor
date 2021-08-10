<?php
/**
 * @package evas-php\evas-documentor
 */
namespace Evas\Documentor\TokenParser;

use Evas\Documentor\Entities\Base\EntityNames;
use Evas\Documentor\TokenParser\Process;
use Evas\Documentor\TokenParser\ProcessMap;

/**
 * Установщик маппинга процессов парсинга.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 21 Jun 2020
 */
class ProcessMapInit
{
    public static function run()
    {
        ProcessMap::set([
            (new Process('include'))
                ->tokenName('T_INCLUDE', 'T_INCLUDE_ONCE', 'T_REQUIRE', 'T_REQUIRE_ONCE')
                ->runCondition(function () {
                    return empty($this->store->classEntity) ? true : false;
                })
                ->endSymbol(';')
                ->endCallback(function () {
                    $include = $this->makeEntity(EntityNames::_INCLUDE);
                    $this->store->file->include($include);
                }),
             (new Process('namespace'))
                ->tokenName('T_NAMESPACE')
                ->runCondition(function () {
                    return empty($this->store->classEntity) ? true : false;
                })
                ->endSymbol(';')
                ->endCallback(function () {
                    // $this->store->namespace = $this->makeEntity(EntityNames::_NAMESPACE);
                    $this->store->namespace = $this->getValue();
                    $this->store->setNamespace($this->file);
                    // $this->file->namespace($this->store->namespace);
                }),

            (new Process('use'))
                ->tokenName('T_USE')
                ->enumSymbol(',')
                ->stopSymbol('(') // игнорируем use анонимных функций
                ->endSymbol(';')
                ->endCallback(function () {
                    if (empty($this->store->classEntity)) {
                        $this->file->useAlias($this->makeEntity(EntityNames::_USE_ALIAS));
                    } else {
                        $innerTrait = $this->makeEntity(EntityNames::_INNER_TRAIT);
                        $this->store->setDocComment($innerTrait);
                        $this->store->classEntity->trait($innerTrait);
                    }
                }),


            (new Process('doubleColon'))
                ->tokenName('T_DOUBLE_COLON')
                ->endCallback(function () {
                    $this->store->doubleColon = true;
                }),
            (new Process('prefix'))
                ->tokenName('T_ABSTRACT', 'T_FINAL')
                ->endCallback(function () {
                    $this->store->prefix = $this->getValue();
                }),

            (new Process('classEntity'))
                ->tokenName('T_CLASS', 'T_INTERFACE', 'T_TRAIT')
                ->runCondition(function () {
                    if (false === $this->store->doubleColon) {
                        // $this->store->classEntity = null;
                        return true;
                    } else {
                        $this->store->doubleColon = false;
                        return false;
                    }
                })
                ->endSymbol('{')
                ->endCallback(function () use (&$file, &$store) {
                    $this->store->classEntity = $this->makeEntity(EntityNames::get($this->tokenValue));
                    $this->store->setPrefix($this->store->classEntity);
                        ->setNamespace($this->store->classEntity, $this->tokenValue);
                        ->setDocComment($this->store->classEntity);
                    call_user_func([$file, $this->tokenValue], $this->store->classEntity);
                }),


            (new Process('visibility'))
                ->tokenName('T_PUBLIC', 'T_PROTECTED', 'T_PRIVATE')
                ->endCallback(function () {
                    $this->store->visibility = $this->getValue();
                }),
            (new Process('staticly'))
                ->tokenName('T_STATIC')
                ->runCondition(function () {
                    return !empty($this->store->classEntity) && empty($this->store->method) ? true : false;
                })
                ->endCallback(function () {
                    $this->store->staticly = $this->getValue() === 'static' ? true : false;
                }),
            (new Process('functionName'))
                ->tokenName('T_FUNCTION')
                ->endSymbol('(')
                // ->endSymbol('{', ';')
                ->endCallback(function () {
                    if (empty($this->getValue())) return;
                    $className = empty($this->store->classEntity) 
                        ? EntityNames::_FILE_FUNCTION : EntityNames::_METHOD;
                    $method = $this->makeEntity($className);
                    $this->store->setDocComment($method);
                    if (empty($this->store->classEntity)) {
                        $this->file->addFunction($method);
                        $this->store->setNamespace($method, 'addFunction');
                    } else {
                        $this->store->setVisabilityAndStaticly($method)
                            ->setPrefix($method);
                        $this->store->classEntity->method($method);
                    }
                    $this->store->method = &$method;
                    $this->runNextProcess('functionArgs', '(');
                }),
            (new Process('functionArgs'))
                ->endSymbol(')')
                ->enumSymbol(',')
                ->endCallback(function (string $symbol = null) {
                    if (!empty($this->getValue())) {
                        $this->store->method->arg($this->makeEntity(EntityNames::_FUNCTION_ARG));
                    }
                    if ($this->isEndSymbol($symbol)) {
                        $this->runNextProcess('functionReturnType', ')');
                    }
                }),
            (new Process('functionReturnType'))
                ->endSymbol(';', '{')
                ->endCallback(function () {
                    $value = $this->getValue();
                    if (':' !== substr($value, 0, 1)) return;
                    $this->value = substr($this->getValue(), 1);
                    $returnType = $this->makeEntity(EntityNames::_FUNCTION_RETURN_TYPE);
                    $this->store->method->returnType($returnType);
                    if ($this->isEndSymbol('{')) {
                        $this->store->braceCountIncrement('method');
                    }
                }),

            (new Process('property'))
                ->tokenName('T_VARIABLE')
                ->endSymbol(';')
                ->runCondition(function () {
                    return !empty($this->store->classEntity) && empty($this->store->inMethod)
                        ? true : false;
                })
                ->endCallback(function () {
                    $this->value = "$this->tokenValue $this->value";
                    $property = $this->makeEntity(EntityNames::_PROPERTY);
                    $this->store->setVisabilityAndStaticly($property)
                        ->setDocComment($property);
                    $this->store->classEntity->property($property);
                }),

            (new Process('const'))
                ->tokenName('T_CONST')
                ->endSymbol(';')
                ->runCondition(function () {
                    return !empty($this->store->classEntity) ? true : false;
                })
                ->endCallback(function () {
                    $constant = $this->makeEntity(EntityNames::_CLASS_CONSTANT);
                    $this->store->setDocComment($constant);
                    $this->store->classEntity->constant($constant);
                }),
            (new Process('define'))
                ->tokenName('T_STRING')
                ->endSymbol(';')
                ->runCondition(function () {
                    echo "$this->name: $this->tokenValue, $this->value\n";
                    return 'define' === $this->tokenValue ? true : false;
                })
                ->endCallback(function () {
                    $constant = $this->makeEntity(EntityNames::_DEFINE);
                    $this->store->setNamespace($constant, 'constant');
                    $this->store->file->constant($constant);
                }),
            (new Process('docComment'))
                ->tokenName('T_DOC_COMMENT')
                ->endCallback(function () {
                    $this->store->docComment = $this->getValue();
                    if (empty($this->store->namespace) && empty($this->store->inEntity)) {
                        $this->store->setDocComment($this->store->file);
                    }
                }),
            (new Process('comment'))
                ->tokenName('T_COMMENT')
                ->endCallback(function () {
                    // $this->store->comment = $this->getValue();
                }),
            (new Process('throw'))
                ->tokenName('T_THROW')
                ->endCallback(function () {
                    // 
                }),
            (new Process('global'))
                ->tokenName('T_GLOBAL')
                ->endCallback(function () {
                    // 
                })
        ]);
    }
}
