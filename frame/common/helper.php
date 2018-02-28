<?php
function dump($var)
{
    echo "<style>pre{position: relative;z-index: 1000;padding: 10px;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;background: #F5F5F5;border: 1px solid #aaa;font-size:14px;line-height: 18px;opacity: 0.9;}</style>";
    if (is_bool($var)) {
        var_dump($var);
    } elseif ($var === null) {
        var_dump($var);
    } else {
        var_dump($var);
    }
}

function request()
{
    return \frame\Request::instance();
}