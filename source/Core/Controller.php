<?php


namespace Source\Core;


use CoffeeCode\Router\Router;
use League\Plates\Engine;
use Source\Support\Message;

/**
 * Class Controller
 * @package Source\Core
 */
abstract class Controller
{
    /** @var Engine */
    protected $view;

    /** @var Router */
    protected $router;

    /** @var Message */
    protected $message;

    /**
     * Controller constructor.
     * @param $router
     */
    public function __construct($router)
    {
        $this->router = $router;
        $this->view = new Engine(dirname(__DIR__, 2) . "/themes/" . CONF_VIEW_THEME, "php");
        $this->view->addData(["router" => $this->router]);

        $this->message = new Message();
    }
}