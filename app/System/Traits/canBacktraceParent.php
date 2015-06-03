<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 25-05-2015
 * Time: 01:32
 */

namespace System\Traits;


trait canBacktraceParent {

    public function calling_class()
    {

        //get the trace
        $trace = debug_backtrace();

        // Get the class that is asking for who awoke it
        $class = $trace[1]['class'];

        // +1 to i cos we have to account for calling this function
        for ($i = 1; $i < count($trace); $i++) {
            if (isset($trace[$i])) // is it set?
                if ($class != $trace[$i]['class']) // is it a different class
                    return $trace[$i]['class'];
        }
        return null;
    }
}