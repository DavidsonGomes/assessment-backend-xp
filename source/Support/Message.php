<?php

namespace Source\Support;

use Source\Core\Session;

/**
 * FSPHP | Class Message
 *
 * @author Robson V. Leite <cursos@upinside.com.br>
 * @package Source\Core
 */
class Message
{
    /** @var string */
    private $text;

    /** @var string */
    private $title;

    /** @var string */
    private $type;

    /** @var string */
    private $before;

    /** @var string */
    private $after;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return string
     */
    public function getText(): ?string
    {
        return $this->before.$this->text.$this->after;
    }

    /**
     * @return string
     */
    public function getTitle() : ?string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $text
     * @return Message
     */
    public function before(string $text): Message
    {
        $this->before = $text;
        return $this;
    }

    /**
     * @param string $text
     * @return Message
     */
    public function after(string $text): Message
    {
        $this->after = $text;
        return $this;
    }

    /**
     * @param string $message
     * @return Message
     */
    public function info(string $message, string $title = "Informativo!"): Message
    {
        $this->type = "info";
        $this->title = $title;
        $this->text = $message;
        return $this;
    }

    /**
     * @param string $message
     * @return Message
     */
    public function success(string $message, string $title = "Sucesso!"): Message
    {
        $this->type = "success";
        $this->title = $title;
        $this->text = $message;
        return $this;
    }

    /**
     * @param string $message
     * @return Message
     */
    public function warning(string $message, string $title = "Atenção!"): Message
    {
        $this->type = "warning";
        $this->title = $title;
        $this->text = $message;
        return $this;
    }

    /**
     * @param string $message
     * @return Message
     */
    public function error(string $message, string $title = "Erro!"): Message
    {
        $this->type = "error";
        $this->title = $title;
        $this->text = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return "<script>$(document).ready(function(){Swal.fire({title:'{$this->title}', text:'{$this->text}', icon:'{$this->type}'})})</script>";
    }

    /**
     * @return string
     */
    public function json(): string
    {
        return json_encode(["message" => [
            "title" => $this->getTitle(),
            "type" => $this->getType(),
            "message" => $this->getText()
        ]], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Set flash Session Key
     */
    public function flash(): void
    {
        (new Session())->set("flash", $this);
    }

    /**
     * @param string $message
     * @return string
     */
    private function filter(string $message): string
    {
        return filter_var($message, FILTER_SANITIZE_STRIPPED);
    }
}