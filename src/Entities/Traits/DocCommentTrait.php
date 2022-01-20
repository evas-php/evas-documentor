<?php
/**
 * Трейт поддержки комментария docComment.
 * @package evas-php\evas-documentor
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 11 Jul 2020
 */
namespace Evas\Documentor\Entities\Traits;

trait DocCommentTrait
{
    /**
     * @var string доккоммент
     */
    public $docComment = [
        'text' => null,
        'description' => null,
        'package' => null,
        'author' => [],
        'since' => null,
        'var' => [],
        'property' => [],
        'todo' => [],
        'see' => [],
        'link' => [],
        'param' => [],
        'static' => [],
        'return' => null,
        'throws' => [],
    ];

    /**
     * Установка доккоммента.
     * @param string доккоммент
     */
    public function docComment(string $docComment)
    {
        $this->docComment['text'] = $docComment;
        $this->parse();
    }

    /**
     * Разбор доккоммента.
     * @param string доккоммент
     */
    private function parse()
    {
        $tokenLines = explode("\n", ltrim(rtrim($this->docComment['text'],"*/"),"/**"));
        $temp = '';
        $last = 'description';

        foreach ($tokenLines as &$tokenLine) {
            $tokenLine = trim($tokenLine);
            if (strlen($tokenLine) <=1 ) continue;
            $tokens = explode(" ",$tokenLine);

            foreach ($tokens as &$token) {
                if (!isset($token)) {
                    continue;
                }
                if (strlen($token)>0 && $token[0] == '@') {
                    if (is_array($this->docComment[$last])) {
                        $this->docComment[$last][] = trim($temp,"* ");
                    } else {
                        $this->docComment[$last] = trim($temp,"* ");
                    }
                    $temp = '';
                    $last = ltrim($token,'@');
                    continue;
                }
                $temp .= $token.' ';
            }
        }
        if (is_array($this->docComment[$last])) {
            $this->docComment[$last][] = trim($temp,"* ");
        } else {
            $this->docComment[$last] = trim($temp,"* ");
        }
        foreach ($this->docComment as $key => $value) {
            if (is_null($this->docComment[$key])) {
                unset($this->docComment[$key]);
                continue;
            } 
            if (is_array($this->docComment[$key])) {
                if (count($this->docComment[$key]) == 0) {
                    unset($this->docComment[$key]);
                    continue;
                }
            }

            if ($key == 'var' || $key == 'param' || $key == 'throw' || $key == 'static' ) {
                foreach ($this->docComment[$key] as &$elem) {
                    $params = explode(' ', $elem);
                    if (count($params) <= 1) continue;
                    $elem = [
                        'type' => $params[0],
                    ];
                    if (count($params)>1) {
                        unset($params[0]);
                        $elem['info'] = implode(' ', $params);
                    }
                }
            }
        }
    }
}
