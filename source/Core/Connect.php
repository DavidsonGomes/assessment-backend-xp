<?php


namespace Source\Core;

use PDO;
use PDOException;

/**
 * Class Connect
 * @package Source\Core
 */
class Connect
{
    /** @var PDO */
    private static $instance;

    /** @var PDOException */
    private static $error;

    /**
     * @return PDO|null
     */
    public static function getInstance(): ?PDO
    {
        if (empty(self::$instance)) {
            try {
                self::$instance = new PDO(
                    DATA_LAYER_CONFIG["driver"] . ":host=" . DATA_LAYER_CONFIG["host"] . ";dbname=" . DATA_LAYER_CONFIG["dbname"] . ";port=" . DATA_LAYER_CONFIG["port"],
                    DATA_LAYER_CONFIG["username"],
                    DATA_LAYER_CONFIG["passwd"],
                    DATA_LAYER_CONFIG["options"]
                );
            } catch (PDOException $exception) {
                Log::error("connect", $exception->getMessage(), ["exception" => $exception]);
                self::$error = $exception;
            }
        }
        return self::$instance;
    }

    /**
     * @return PDOException|null
     */
    public static function getError(): ?PDOException
    {
        return self::$error;
    }

    /**
     * Connect constructor.
     */
    final private function __construct()
    {
    }

    /**
     *
     */
    private function __clone()
    {
    }
}