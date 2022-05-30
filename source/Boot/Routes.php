<?php

use CoffeeCode\Router\Router;

$route = new Router(url());

/*
 * WEB ROUTES
 */
$route->namespace("Source\Controllers");
$route->group(null);
//dashboard
$route->get("/", "DashboardController:dashboard", "dashboard");
//categories
$route->get("/categorias", "CategoryController:index", "category.index");
$route->get("/categoria/create", "CategoryController:create", "category.create");
$route->post("/categoria/create", "CategoryController:store", "category.store");
$route->get("/categoria/edit/{id}", "CategoryController:edit", "category.edit");
$route->post("/categoria/update", "CategoryController:update", "category.update");
$route->get("/categoria/delete/{id}", "CategoryController:delete", "category.delete");
//products
$route->get("/produtos", "ProductController:index", "product.index");
$route->get("/produto/create", "ProductController:create", "product.create");
$route->post("/produto/create", "ProductController:store", "product.store");
$route->get("/produto/edit/{id}", "ProductController:edit", "product.edit");
$route->post("/produto/update", "ProductController:update", "product.update");
$route->get("/produto/delete/{id}", "ProductController:delete", "product.delete");

/*
 * ERRORS ROUTES
 */
$route->namespace("Source\App");
$route->group("ops");
$route->get("/{errcode}", "Web:error", "web.error");

/*
 * ROUTE
 */
$route->dispatch();


/*
 * ERRORS PROCESS
 */
if ($route->error()) {
    $route->redirect("web.error", ["errcode" => $route->error()]);
}