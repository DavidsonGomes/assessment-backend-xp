<?php


namespace Source\Core;

use Exception;
use PDO;
use PDOException;
use Source\Support\Message;
use stdClass;

/**
 * Class Model
 * @package Source\Core
 */
abstract class Model
{
    use CrudTrait;

    /** @var string $entity database table */
    private $entity;

    /** @var string $primary table primary key field */
    private $primary;

    /** @var array $required table required fields */
    private $required;

    /** @var int $numDatabase control order database */
    private $numDatabase;

    /** @var string $timestamps control created and updated at */
    private $timestamps;

    /** @var string */
    protected $statement;

    /** @var string */
    protected $params;

    /** @var string */
    protected $group;

    /** @var string */
    protected $order;

    /** @var int */
    protected $limit;

    /** @var int */
    protected $offset;

    /** @var \PDOException|null */
    protected $fail;

    /** @var object|null */
    protected $data;

    /** @var Message|null */
    protected $message;

    /**
     * Model constructor.
     * @param string $entity
     * @param array $required
     * @param int $numDatabase
     * @param string $primary
     * @param bool $timestamps
     */
    public function __construct(
        string $entity,
        array $required,
        int $numDatabase = 1,
        string $primary = 'id',
        bool $timestamps = true
    ) {
        $this->entity = $entity;
        $this->primary = $primary;
        $this->required = $required;
        $this->numDatabase = $numDatabase;
        $this->timestamps = $timestamps;

        $this->message = new Message();
    }


    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (empty($this->data)) {
            $this->data = new stdClass();
        }

        $this->data->$name = $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    /**
     * @param $name
     * @return string|null
     */
    public function __get($name)
    {
        $method = $this->toCamelCase($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        if (method_exists($this, $name)) {
            return $this->$name();
        }

        return ($this->data->$name ?? null);
    }

    /**
     * @return object|null
     */
    public function data() : ?object
    {
        return $this->data;
    }

    /**
     * @return PDOException|Exception|null
     */
    public function fail()
    {
        return $this->fail;
    }

    /**
     * @return Message|null
     */
    public function message() : ?Message
    {
        return $this->message;
    }

    /**
     * @param string|null $terms
     * @param string|null $params
     * @param string $columns
     * @param string|null $join
     * @return $this
     */
    public function find(?string $terms = null, ?string $params = null, string $columns = "*", string $join = null) : Model
    {
        if ($terms) {
            if($join){
                $this->statement = "SELECT {$columns} FROM {$this->entity} a {$join} WHERE {$terms}";
            }else{
                $this->statement = "SELECT {$columns} FROM {$this->entity} WHERE {$terms}";
            }
            parse_str($params, $this->params);
            return $this;
        }

        $this->statement = "SELECT {$columns} FROM {$this->entity}";
        return $this;
    }

    /**
     * @param int $id
     * @param string $columns
     * @return Model|null
     */
    public function findById(int $id, string $columns = "*") : ?Model
    {
        return $this->find("{$this->primary} = :id", "id={$id}", $columns)->fetch();
    }

    /**
     * @param string $column
     * @return Model|null
     */
    public function group(string $column) : ?Model
    {
        $this->group = " GROUP BY {$column}";
        return $this;
    }

    /**
     * @param string $columnOrder
     * @return Model|null
     */
    public function order(string $columnOrder) : ?Model
    {
        $this->order = " ORDER BY {$columnOrder}";
        return $this;
    }

    /**
     * @param int $limit
     * @return Model|null
     */
    public function limit(int $limit) : ?Model
    {
        $this->limit = " LIMIT {$limit}";
        return $this;
    }

    /**
     * @param int $offset
     * @return Model|null
     */
    public function offset(int $offset) : ?Model
    {
        $this->offset = " OFFSET {$offset}";
        return $this;
    }

    /**
     * @param bool $all
     * @return array|mixed|null
     */
    public function fetch(bool $all = false)
    {
        try{
            $stmt = Connect::getInstance()->prepare($this->statement . $this->group . $this->order . $this->limit . $this->offset);

            $stmt->execute($this->params);

            if (!$stmt->rowCount()) {
                return null;
            }

            if ($all) {
                return $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
            }

            return $stmt->fetchObject(static::class);
        }catch (PDOException $exception){
            Log::error("modelfetch", $exception->getMessage(), ["exception" => $exception]);
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @return int
     */
    public function count() : int
    {
        $stmt = Connect::getInstance()->prepare($this->statement . $this->group . $this->order . $this->limit . $this->offset);

        $stmt->execute($this->params);
        return $stmt->rowCount();
    }

    /**
     * @return bool
     */
    public function save() : bool
    {
        $primary = $this->primary;
        $id = null;

        if (!$this->required()) {
            $this->message->warning("Preencha todos os campos necessários para continuar!");
            Log::notice("modelsave", "Preencha todos os campos necessários para continuar!");
            return false;
        }

        /** Update */
        if (!empty($this->data->$primary)) {
            $id = $this->data->$primary;
            $this->update($this->safe(), "{$this->primary} = :id", "id={$id}");
            if ($this->fail()) {
                $this->message->error("Erro ao atualizar, verifique os dados");
                Log::warning("modelsave", "Erro ao atualizar, verifique os dados");
                return false;
            }
        }

        /** Create */
        if (empty($this->data->$primary)) {
            $id = $this->create($this->safe());
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar, verifique os dados");
                Log::warning("modelsave", "Erro ao cadastrar, verifique os dados");
                return false;
            }
        }

        $this->data = $this->findById($id)->data();
        return true;

    }

    /**
     * @return bool
     */
    public function destroy() : bool
    {
        $primary = $this->primary;
        $id = $this->data->$primary;

        if (empty($id)) {
            return false;
        }

        return $this->delete("{$this->primary} = :id", "id={$id}");
    }

    /**
     * @return bool
     */
    protected function required() : bool
    {
        $data = (array)$this->data();
        foreach ($this->required as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return array|null
     */
    protected function safe() : ?array
    {
        $safe = (array)$this->data;
        unset($safe[$this->primary]);
        return $safe;
    }

    /**
     * @param string $string
     * @return string
     */
    protected function toCamelCase(string $string): string
    {
        $camelCase = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
        $camelCase[0] = strtolower($camelCase[0]);
        return $camelCase;
    }

}