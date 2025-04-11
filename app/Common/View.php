<?php

namespace App\Common;

use League\Plates\Engine;

class View
{
    private $view;

    public function __construct()
    {
        $this->view = Engine::create(__DIR__ . env('CONFIG_PATH_VIEW'), 'php');
    }

    /**
     * @param array $data
     * @return array
     */
    public function addData(array $data)
    {
        return $this->view->addData($data);
    }

    /**
     * @param $view
     * @param $data [Columns param]
     * @return string
    */
    public function render(string $view, array $data = []): string
    {
        return $this->view->render($view, $data);
    }

    /**
     * @return Engine
     */
    public function engine(): Engine
    {
        return $this->view;
    }
}
