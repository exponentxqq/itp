<?php
namespace Core;

class Controller
{
    public $view;

    public function __construct($config = [])
    {
        include CORE_PATH . 'ITemp/ITemp.php';

        $default_config = [
            'compileDir' => APP_PATH . 'Runtime/',
            'tempDir'=>APP_PATH.MODULE_NAME.'/View/'.CONTROLLER_NAME.'/'
        ];
        $default_config = array_merge($default_config,$config);
        $this->view = new \ITemp($default_config);
    }

    public function assign($key, $value = '')
    {
        $this->view->assign($key, $value);
    }

    public function show($file)
    {
        $this->view->show($file);
    }
}