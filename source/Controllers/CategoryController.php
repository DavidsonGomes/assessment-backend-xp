<?php


namespace Source\Controllers;


use Source\Core\Controller;
use Source\Core\Log;
use Source\Models\Category;

/**
 * Class Web
 * @package Source\App
 */
class CategoryController extends Controller
{

    /**
     * @param $router
     */
    public function __construct($router)
    {
        parent::__construct($router);
    }

    /**
     * @return void
     */
    public function index() : void
    {
        $categories = (new Category())->find()->fetch(true);

        Log::debug("category", "Categories page loaded!");

        echo $this->view->render("category/index", ['categories' => $categories]);
    }

    /**
     * @return void
     */
    public function create() : void
    {
        Log::debug("category.create", "Category registration page loaded!");

        echo $this->view->render("category/create");
    }

    /**
     * @param $data
     * @return void
     */
    public function store($data) : void
    {
        if (empty($data['name'])) {
            $this->message->error("Fill in the name!")->flash();
            Log::warning("category.store", "Empty category name");
            redirect($this->router->route("category.create"));
            return;
        }

        $name = $data['name'];

        $category = new Category();
        $category->name = $name;

        if (!$category->save()) {
            $this->message->error($category->message()->render())->flash();
            Log::warning("category.store", "Error saving category");
            redirect($this->router->route("category.create"));
            return;
        }

        $this->message->success("Category registered successfully!")->flash();
        Log::debug("category.store", "Registered category");
        redirect($this->router->route("category.index"));

    }

    /**
     * @param $data
     * @return void
     */
    public function edit($data) : void
    {
        $id = $data['id'];

        $category = (new Category())->findById($id);

        Log::debug("category.edit", "Edit category {$id} page loaded!");

        echo $this->view->render("category/edit", ['category' => $category]);
    }

    /**
     * @param $data
     * @return void
     */
    public function update($data) : void
    {
        $id = $data['id'];

        if (empty($data['id'])) {
            $this->message->error("Unexpected error, reload the page!")->flash();
            Log::warning("category.store", "empty category id");
            redirect($this->router->route("category.edit", ['id' => $id]));
            return;
        }

        if (empty($data['name'])) {
            $this->message->error("Fill in the name!")->flash();
            Log::warning("category.store", "Empty category name");
            redirect($this->router->route("category.edit", ['id' => $id]));
            return;
        }
        $name = $data['name'];

        $category = (new Category())->findById($id);
        $category->name = $name;

        if (!$category->save()) {
            $this->message->error($category->message()->render())->flash();
            Log::warning("category.store", "Error editing category");
            redirect($this->router->route("category.edit", ['id' => $id]));
            return;
        }

        $this->message->success("Category edited successfully!")->flash();
        Log::debug("category.store", "Edited category");
        redirect($this->router->route("category.index"));

    }

    /**
     * @param $data
     * @return void
     */
    public function delete($data) : void
    {
        $id = $data['id'];

        $category = (new Category())->findById($id);
        $category->destroy();

        $this->message->success("Category deleted successfully!")->flash();
        Log::debug("category.delete", "Deleted category");
        redirect($this->router->route("category.index"));
    }
}