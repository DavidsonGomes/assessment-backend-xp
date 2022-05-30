<?php


namespace Source\Controllers;


use Source\Core\Controller;
use Source\Core\Log;
use Source\Models\Product;

/**
 * Class Web
 * @package Source\App
 */
class DashboardController extends Controller
{


    /**
     * Web constructor.
     * @param $router
     */
    public function __construct($router)
    {
        parent::__construct($router);
    }

    /**
     * DASHBOARD
     * @param array|null $data
     */
    public function dashboard(?array $data) : void
    {
        $products = (new Product())->find()->limit(4)->fetch(true);
        Log::debug("web_dashboard", "Página carregada!");

        echo $this->view->render("dashboard", ['products' => $products]);
    }

    /**
     * SITE ERROR
     * @param $data
     */
    public function error($data) : void
    {
        $error = filter_var($data["errcode"], FILTER_VALIDATE_INT);

        Log::error("error{$data["errcode"]}", "Erro pagina {$data["errcode"]}");
        echo $this->view->render("error", [
            "error" => $error,
        ]);
    }
}