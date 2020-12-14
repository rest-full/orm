<?php

namespace Restfull\ORM\Validate;

use Restfull\Event\Event;

/**
 * Class validate
 * @package Restfull\ORM\Validate
 */
class validate
{

    /**
     * @var array
     */
    protected $error = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @param array $data
     * @return validate
     */
    public function setData(array $data): Validate
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        return count($this->error) > 0;
    }

    /**
     * @param string $key
     * @return string
     */
    public function getRules(string $key): string
    {
        return $this->rules[$key];
    }

    /**
     * @param array $rules
     * @return validate
     */
    public function setRules(array $rules): Validate
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * @return array
     */
    public function error(): array
    {
        return $this->error;
    }

    /**
     * @param string $event
     * @param array|null $data
     * @return mixed|null
     */
    public function eventProcessVerification(string $event, array $data = null)
    {
        $event = $this->dispatchEvent(MVC[2]['restfull'] . "." . $event, $data);
        if ($event->getResult() instanceof Response) {
            return null;
        }
        return $event->getResult();
    }

    /**
     * @param Event $event
     * @param Validation $validation
     * @param array $datas
     * @return null
     */
    public function beforeValidator(Event $event, Validation $validation, array $datas)
    {
        return null;
    }

    /**
     * @param Event $event
     * @param Validation $validation
     * @return null
     */
    public function afterValidator(Event $event, Validation $validation)
    {
        return null;
    }
}