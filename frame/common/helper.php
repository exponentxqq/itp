<?php
function dump()
{
    $output = '';
    $args = func_get_args();
    if (!empty($args) && $args[0] === 'print') {
        $_output = $output;
        $output = '';
        return print $_output;
    }
    if (!isset($doc_root)) {
        $doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    }
    $backtrace = debug_backtrace();
    $line = htmlspecialchars($backtrace[0]['line']);
    $file = htmlspecialchars(str_replace(array('\\', $doc_root), array('/', ''), $backtrace[0]['file']));
    $class = !empty($backtrace[1]['class']) ? htmlspecialchars($backtrace[1]['class']) . '::' : '';
    $function = !empty($backtrace[1]['function']) ? htmlspecialchars($backtrace[1]['function']) . '() ' : '';
    $output .= "<b>$class$function =&gt;$file #$line</b><pre>";
    ob_start();
    foreach ($args as $arg) {
        var_dump($arg);
    }
    $new = htmlspecialchars(ob_get_contents(), ENT_COMPAT, 'UTF-8');
    ob_end_clean();
    if(!strpos($output, $new)) $output .= $new;
    $output .= '</pre>';
    return print htmlspecialchars_decode($output);
}

function request()
{
    return \frame\Request::instance();
}

function container()
{
    return \frame\Container::getInstance();
}