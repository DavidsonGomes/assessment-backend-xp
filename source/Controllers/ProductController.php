<?php


namespace Source\Controllers;


use Source\Core\Controller;
use Source\Core\Log;
use Source\Models\Category;
use Source\Models\Product;
use Source\Support\Thumb;
use Source\Support\Upload;

/**
 * Class Web
 * @package Source\App
 */
class ProductController extends Controller
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
    public function index(): void
    {
        $products = (new Product())->find()->fetch(true);

        Log::debug("product", "Products page loaded");

        echo $this->view->render("product/index", [
            'products' => $products
        ]);
    }

    /**
     * @return void
     */
    public function create(): void
    {
        $categories = (new Category())->find()->fetch(true);

        Log::debug("product.create", "Product registration page loaded");

        echo $this->view->render("product/create", ['categories' => $categories]);
    }

    /**
     * @param $data
     * @return void
     */
    public function store($data): void
    {
        if (empty($data['name'])) {
            $this->message->error("Fill in the name!")->flash();
            Log::warning("product.store", "Empty product name");
            redirect($this->router->route("product.create"));
            return;
        }

        if (empty($data['sku'])) {
            $this->message->error("Fill in the SKU!")->flash();
            Log::warning("product.store", "Empty product SKU");
            redirect($this->router->route("product.create"));
            return;
        }

        if (empty($data['price'])) {
            $this->message->error("Fill in the price!")->flash();
            Log::warning("product.store", "Empty product price");
            redirect($this->router->route("product.create"));
            return;
        }

        if (empty($data['quantity'])) {
            $this->message->error("Fill in the quantity!")->flash();
            Log::warning("product.store", "Empty product quantity");
            redirect($this->router->route("product.create"));
            return;
        }

        if (empty($data['description'])) {
            $this->message->error("Fill in the description!")->flash();
            Log::warning("product.store", "Empty product description");
            redirect($this->router->route("product.create"));
            return;
        }

        $sku = $data['sku'];
        $name = $data['name'];
        $price = $data['price'];
        $quantity = $data['quantity'];
        $categories = implode('|', $data['categories']);
        $description = $data['description'];

        $duplicate = (new Product())->find("sku = :sku", "sku={$sku}")->fetch();

        if ($duplicate) {
            $this->message->error("SKU already registered!")->flash();
            Log::warning("product.store", "SKU already registered");
            redirect($this->router->route("product.create"));
            return;
        }

        $product = new Product();
        $product->sku = $sku;
        $product->name = $name;
        $product->price = $price;
        $product->quantity = $quantity;
        $product->categories = $categories;
        $product->description = $description;

        if (!empty($_FILES["image"])) {
            $file = $_FILES["image"];
            $upload = new Upload();

            if (!$product->image = $upload->image($file, $product->sku . '-' . $product->name, 360)) {
                $this->message->error($upload->message())->flash();
                Log::warning("product.store", "Upload error");
                redirect($this->router->route("product.create"));
                return;
            }
        }

        if (!$product->save()) {
            $this->message->error($product->message()->render())->flash();
            Log::warning("product.store", "Error saving product");
            redirect($this->router->route("product.create"));
            return;
        }

        $this->message->success("Product registered successfully!")->flash();
        Log::debug("product.store", "Registered product");
        redirect($this->router->route("product.index"));
    }

    /**
     * @param $data
     * @return void
     */
    public function edit($data): void
    {
        $id = $data['id'];

        $product = (new Product())->findById($id);
        $categories = (new Category())->find()->fetch(true);

        Log::debug("product.edit", "Página de editar o produto {$id} carregada!");

        echo $this->view->render("product/edit", ['product' => $product, 'categories' => $categories]);
    }

    /**
     * @param $data
     * @return void
     */
    public function update($data): void
    {
        $id = $data['id'];

        if (empty($data['id'])) {
            $this->message->error("Erro inesperado, recarregue a página!")->flash();
            Log::warning("product.store", "ID do produto vazio");
            redirect($this->router->route("product.edit", ['id' => $id]));
            return;
        }

        if (empty($data['name'])) {
            $this->message->error("Fill in the name!")->flash();
            Log::warning("product.store", "Empty product name");
            redirect($this->router->route("product.create"));
            return;
        }

        if (empty($data['sku'])) {
            $this->message->error("Fill in the SKU!")->flash();
            Log::warning("product.store", "Empty product SKU");
            redirect($this->router->route("product.create"));
            return;
        }

        if (empty($data['price'])) {
            $this->message->error("Fill in the price!")->flash();
            Log::warning("product.store", "Empty product price");
            redirect($this->router->route("product.create"));
            return;
        }

        if (empty($data['quantity'])) {
            $this->message->error("Fill in the quantity!")->flash();
            Log::warning("product.store", "Empty product quantity");
            redirect($this->router->route("product.create"));
            return;
        }

        if (empty($data['description'])) {
            $this->message->error("Fill in the description!")->flash();
            Log::warning("product.store", "Empty product description");
            redirect($this->router->route("product.create"));
            return;
        }

        $sku = $data['sku'];
        $name = $data['name'];
        $price = $data['price'];
        $quantity = $data['quantity'];
        $categories = implode('|', $data['categories']);
        $description = $data['description'];

        $product = (new Product())->findById($id);
        $product->sku = $sku;
        $product->name = $name;
        $product->price = $price;
        $product->quantity = $quantity;
        $product->categories = $categories;
        $product->description = $description;

        if (!empty($_FILES["image"])) {
            $file = $_FILES["image"];
            $upload = new Upload();

            if ($product->image()) {
                (new Thumb())->flush($product->data()->image);
                $upload->remove("storage/{$product->data()->image}");
            }

            if (!$product->image = $upload->image($file, $product->sku . '-' . $product->name, 360)) {
                $this->message->error($upload->message())->flash();
                Log::warning("product.store", "Upload error");
                redirect($this->router->route("product.create"));
                return;
            }
        }

        if (!$product->save()) {
            $this->message->error($product->message()->render())->flash();
            Log::warning("product.store", "Error updating product");
            redirect($this->router->route("product.create"));
            return;
        }

        $this->message->success("Product updated successfully!")->flash();
        Log::debug("product.store", "Updated product");
        redirect($this->router->route("product.index"));
    }

    /**
     * @param $data
     * @return void
     */
    public function delete($data): void
    {
        $id = $data['id'];

        $product = (new Product())->findById($id);
        $upload = new Upload();

        if ($product->image()) {
            (new Thumb())->flush($product->data()->image);
            $upload->remove("storage/{$product->data()->image}");
        }

        $product->destroy();

        $this->message->success("Product deleted successfully!")->flash();
        Log::debug("category.delete", "Deleted product");
        redirect($this->router->route("product.index"));
    }
}