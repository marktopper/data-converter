<?php

/**
 * jsonpp - Pretty print JSON data
 *
 * In versions of PHP < 5.4.x, the json_encode() function does not yet provide a
 * pretty-print option. In lieu of forgoing the feature, an additional call can
 * be made to this function, passing in JSON text, and (optionally) a string to
 * be used for indentation.
 *
 * @param string $json  The JSON data, pre-encoded
 * @param string $istr  The indentation string
 *
 * @return string
 */
// https://gist.github.com/GloryFish/1045396#gistcomment-1181083
function prettify_json($json, $istr='  ')
{
    $result = '';
    for($p=$q=$i=0; isset($json[$p]); $p++)
    {
        $json[$p] == '"' && ($p>0?$json[$p-1]:'') != '\\' && $q=!$q;
        if(strchr('}]', $json[$p]) && !$q && $i--)
        {
            strchr('{[', $json[$p-1]) || $result .= "\n".str_repeat($istr, $i);
        }
        $result .= $json[$p];
        if(strchr(',{[', $json[$p]) && !$q)
        {
            $i += strchr('{[', $json[$p])===FALSE?0:1;
            strchr('}]', $json[$p+1]) || $result .= "\n".str_repeat($istr, $i);
        }
    }
    return $result;
}