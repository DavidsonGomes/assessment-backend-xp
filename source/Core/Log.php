<?php


namespace Source\Core;


use Monolog\ErrorHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\TelegramBotHandler;
use Monolog\Logger;
use Source\Models\Auth;
use Source\Models\User;

/**
 * Class Log
 * @package Source\Support
 */
abstract class Log
{
    /** @var Logger */
    protected static $instance;
    /**
     * @return mixed
     */
    static public function getLogger(string $name) : ?Logger
    {
        if (!self::$instance) {
            self::configureInstance($name);
        }
        return self::$instance;
    }

    /**
     * @param string $name
     */
    protected static function configureInstance(string $name)
    {
        $logger = new Logger($name);
        $logger->pushHandler(new StreamHandler(__DIR__ . "/../../storage/logs/log.txt", Logger::DEBUG));

        $logger->pushProcessor(function ($record){
            $record["extra"]["HTTP_HOST"] = $_SERVER['HTTP_HOST'];
            $record["extra"]["REQUEST_URI"] = $_SERVER['REQUEST_URI'];
            $record["extra"]["REQUEST_METHOD"] = $_SERVER['REQUEST_METHOD'];
            $record["extra"]["HTTP_USER_AGENT"] = $_SERVER['HTTP_USER_AGENT'];
            $record["extra"]["REMOTE_ADDR"] = $_SERVER['REMOTE_ADDR'];
            return $record;
        });

        self::$instance = $logger;
    }

    /**
     * @param string $name
     * @param $message
     * @param array $context
     */
    public static function debug(string $name, $message, array $context = [])
    {
        self::getLogger($name)->debug($message, $context);
    }

    /**
     * @param string $name
     * @param $message
     * @param array $context
     */
    public static function info(string $name, $message, array $context = [])
    {
        self::getLogger($name)->info($message, $context);
    }

    /**
     * @param string $name
     * @param $message
     * @param array $context
     */
    public static function notice(string $name, $message, array $context = [])
    {
        self::getLogger($name)->notice($message, $context);
    }

    /**
     * @param string $name
     * @param $message
     * @param array $context
     */
    public static function warning(string $name, $message, array $context = [])
    {
        self::getLogger($name)->warning($message, $context);
    }

    /**
     * @param string $name
     * @param $message
     * @param array $context
     */
    public static function error(string $name, $message, array $context = [])
    {
        self::registerErrorHandler(self::getLogger($name));
        self::getLogger($name)->error($message, $context);
    }

    /**
     * @param string $name
     * @param $message
     * @param array $context
     */
    public static function critical(string $name, $message, array $context = [])
    {
        self::getLogger($name)->critical($message, $context);
    }

    /**
     * @param string $name
     * @param $message
     * @param array $context
     */
    public static function alert(string $name, $message, array $context = [])
    {
        self::getLogger($name)->alert($message, $context);
    }

    /**
     * @param string $name
     * @param $message
     * @param array $context
     */
    public static function emergency(string $name, $message, array $context = [])
    {
        self::getLogger($name)->emergency($message, $context);
    }

    private static function registerErrorHandler($app)
    {
        // get base logger
        $logger = clone $app;
        // add extra handler
        $handler = new ErrorLogHandler();
        // set formatter without datetime
        $handler->setFormatter(new LineFormatter('%channel%.%level_name%: %message% %context% %extra%'));
        $logger->pushHandler($handler);
        // attach php errorhandler to app logger
        ErrorHandler::register($logger);
    }

    /**
     * Log constructor.
     */
    final private function __construct()
    {
    }
}