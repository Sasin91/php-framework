<?php

namespace System\Traits;


trait Markdown
{
    public function md($text) // md stands for MarkDown.
    {
        if (is_array($text)) {
            $keys = array_keys($text);
            foreach ($text as $piece) {
                if (in_array('contains', $keys)) {
                    return $this->parsedown->instance()->text($piece['contains']);
                } else {
                    return $this->parsedown->instance()->text($piece);
                }
            }
        } else {
            return $this->parsedown->instance()->text($text);
        }
    }

    public function mdlb($text) // mdlb stands for MarkDown (with) Line Breaks.
    {
        if(is_array($text))
        {
            return $this->convertArrayToStrings($text);
        }
        if($this->isItInCache($text) == true) { return $this->isItInCache($text); }

        $this->cache->set($text, $this->parsedown->instance()->setBreaksEnabled(true)->text($text));
        return $this->md($text);
    }

    function isItInCache($text)
    {
        #$key = base64_encode($text);
        return $this->cache->get($text);
    }


    function convertObjectToStrings($arrays)
    {
        $imploded = '';

        foreach($arrays as $object) {
            $array = get_object_vars($object);
            $imploded = implode("\n", $array);
        }
        return $this->md($imploded);
    }

    function convertArrayToStrings($arrays)
    {
        $imploded = '';
        foreach($arrays as $key => $array) {
            $imploded[] = implode("\n", $array);
        }
        return $this->md(implode("\n", $imploded));
    }
}