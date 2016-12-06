<?php

function var_export54($var, $indent='') {
    switch (gettype($var)) {
        case 'string':
            return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
        case 'array':
            $indexed = array_keys($var) === range(0, count($var) - 1);
            $r = [];
            foreach ($var as $key => $value) {
                $r[] = $indent . '    '
                    . ($indexed ? '' : var_export54($key) . ' => ')
                    . var_export54($value, $indent . '    ');
            }
            return "[\n" . implode(",\n", $r) . "\n" . $indent . ']';
        case 'object':
            $r = [];
            foreach ((array) $var as $key => $value) {
                $r[] = $indent . '    '
                    . var_export54($key) . ' => '
                    . var_export54($value, $indent . '    ');
            }
            return 'new ' . get_class($var) . " {\n" . implode(",\n", $r) . "\n" . $indent . '}';
        case 'boolean':
            return $var ? 'true' : 'false';
        default:
            return var_export($var, true);
    }
}

function get_output($code) {
    $result = [];

    try {
        $parser = new SqlParser\Parser($code);
        $result['raw'] = $parser->statements;
    } catch (\Exception $ex) {
        $result['raw'] = (array) $ex;
    }

    $result['dump'] = var_export54($result['raw']);
    $result['nice'] = (new Nicer($result['raw']))->generate();

    return json_encode($result);
}
