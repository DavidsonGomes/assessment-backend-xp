<?php


namespace Source\Models;


use Source\Core\Model;
use Source\Core\Session;

/**
 * Class Product
 * @package Source\Models
 */
class Product extends Model
{
    /**
     * Access constructor.
     */
    public function __construct()
    {
        parent::__construct("products", ['name', 'sku', 'price', 'description', 'quantity']);
    }

    public function image(): ?string
    {
        if ($this->data()->image && file_exists(__DIR__ . "/../../" . CONF_UPLOAD_DIR . "/{$this->data()->image}")) {
            return $this->data()->image;
        }

        return null;
    }
}