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
        'descripton' => null,
        'package' => null,
        'authors' => null,
        'since' => null,
        'vars' => null,
        'params' => null,
        'return' => null,
        'throws' => null,
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
        $writer = &$this->docComment['descripton'];

        foreach ($tokenLines as &$tokenLine) {
            $tokenLine = trim($tokenLine);
            if (strlen($tokenLine) <=1 ) continue;
            $tokens = explode(" ",$tokenLine);

            foreach ($tokens as &$token) {
                // if ($token == '*') {
                //     $writer = &$this->docComment['descripton'];
                //     continue;
                // }
                if ($token == '@package') {
                    $writer = &$this->docComment['package'];
                    continue;
                }
                if ($token == '@since') {
                    $writer = &$this->docComment['since'];
                    continue;
                }
                if ($token == '@return') {
                    $writer = &$this->docComment['return'];
                    continue;
                }
                if ($token == '@author') {
                    $writer = &$this->docComment['authors'];
                    $writer .= '*';
                    continue;
                }
                if ($token == '@var') {
                    $writer = &$this->docComment['vars'];
                    $writer .= '*';
                    continue;
                }
                if ($token == '@param') {
                    $writer = &$this->docComment['params'];
                    $writer .= '*';
                    continue;
                }
                if ($token == '@throws') {
                    $writer = &$this->docComment['throws'];
                    $writer .= '*';
                    continue;
                }
                $writer .= $token.' ';
            }
        }
        foreach ($this->docComment as $key => $value) {
            if (is_null($this->docComment[$key])) {
                unset($this->docComment[$key]);
                continue;
            } else
            $this->docComment[$key] = trim($this->docComment[$key], '* ');
            if ($key == 'authors') {
                $this->docComment[$key] = explode('*', $this->docComment[$key]);
                continue;
            }
            if ($key == 'vars' || $key == 'params' || $key == 'throws' || $key == 'return') {
                $params = explode('*', $this->docComment[$key]);
                $this->docComment[$key] = [];

                foreach ($params as &$param) {
                    if (strlen($param) <= 1) continue;
                    $temp = explode(' ', trim($param));
                    $param = [
                        'type' => $temp[0],
                    ];
                    if (count($temp)>1) {
                        unset($temp[0]);
                        $param['info'] = implode(' ', $temp);
                    }
                    $this->docComment[$key][] = $param;
                }
            }
        }
    }
}
