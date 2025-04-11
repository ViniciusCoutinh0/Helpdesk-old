<?php

namespace App\Common;

class Message
{
    /**
     * Default css class
     *
     * @string
    */
    private const DEFAULT_CLASS = 'alert';

    /**
     * Default css class success
     *
     * @string
    */
    private const SUCCESS_CLASS = 'success';

    /**
     * Default css class warning
     *
     * @string
    */
    private const WARNING_CLASS = 'warning';

    /**
     * Default css class info
     *
     * @string
    */
    private const INFO_CLASS    = 'primary';

    /**
     * Default css class error
     *
     * @string
    */
    private const ERROR_CLASS   = 'danger';

    /**
     *
     * @var string
    */
    private $text;

    /**
     *
     * @var string
    */
    private $type;

    public function __get($name)
    {
        return ($this->$name ?? null);
    }


    public function __toString()
    {
        return $this->render();
    }

    /**
     * @param string $message
     * @return Message
    */
    public function success(string $message): Message
    {
        $this->text = clearHtml($message);
        $this->type = self::SUCCESS_CLASS;
        return $this;
    }

    /**
     * @param string $message
     * @return Message
    */
    public function warning(string $message): Message
    {
        $this->text = clearHtml($message);
        $this->type = self::WARNING_CLASS;
        return $this;
    }

    /**
     * @param string $message
     * @return Message
    */
    public function info(string $message): Message
    {
        $this->text = clearHtml($message);
        $this->type = self::INFO_CLASS;
        return $this;
    }

    /**
     * @param string $message
     * @return Message
    */
    public function error(string $message): Message
    {
        $this->text = clearHtml($message);
        $this->type = self::ERROR_CLASS;
        return $this;
    }

    /**
     * @return string
    */
    public function render(): string
    {
        $html = "<div class=\"" . self::DEFAULT_CLASS . " " . self::DEFAULT_CLASS . "-{$this->type}\" role=\"alert\">";
        $html .= html_entity_decode($this->text);
        $html .= "</div>";
        return $html;
    }

    /**
     * @return string
    */
    public function toJson(): string
    {
        return json_encode(['message' => $this->text]);
    }
}
