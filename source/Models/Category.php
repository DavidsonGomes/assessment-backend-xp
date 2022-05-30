<?php


namespace Source\Models;


use Source\Core\Model;
use Source\Core\Session;

/**
 * Class Product
 * @package Source\Models
 */
class Category extends Model
{
    /**
     * Access constructor.
     */
    public function __construct()
    {
        parent::__construct("categories", ['name']);
    }
}