<?php

namespace Restfull\ORM\Behavior;

use Restfull\Error\Exceptions;
use Restfull\Event\Event;

/**
 * Class Behavior
 * @package Restfull\ORM\Behavior
 */
class Behavior
{

    /**
     * @var mixed
     */
    public $data;

    /**
     * @return |null
     */
    public function eventProcessVerification()
    {
        if (is_array($event)) {
            for ($a = 0; $a < count($event); $a++) {
                $event = $this->dispatchEvent(
                    MVC[2] . "." . $event[$a],
                    ['request' => $this->request, 'response' => $this->response]
                );
                if ($event->getResult() instanceof Response) {
                    return null;
                }
                return $event->getResult();
            }
        }
        $event = $this->dispatchEvent(
            MVC[2] . "." . $event,
            ['request' => $this->request, 'response' => $this->response]
        );
        if ($event->getResult() instanceof Response) {
            return null;
        }
        return $event->getResult();
    }

    /**
     * @param Behavior $behavior
     * @param string $method
     * @param mixed $data
     * @return Behavior
     * @throws Exceptions
     */
    public function checkCallMethod(Behavior $behavior, string $method, mixed $data)
    {
        if (!in_array($method, get_class_methods($behavior))) {
            throw new Exceptions("Not exist this method, please create the method: " . $method);
        }

        $this->data = $behavior->$method($data);

        return $this;
    }

    /**
     * @param Event $event
     * @param ArrayObject $data
     * @param ArrayObject $options
     * @return null
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        return null;
    }

    /**
     * @param Event $event
     * @param Query $query
     * @param ArrayObject $options
     * @param bool $primary
     * @return null
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, bool $primary)
    {
        return null;
    }

    /**
     * @param Event $event
     * @param EntityInterface $entity
     * @param ArrayObject $options
     * @return null
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        return null;
    }

    /**
     * @param Event $event
     * @param EntityInterface $entity
     * @param ArrayObject $options
     * @return null
     */
    public function beforeDelete(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        return null;
    }

    /**
     * @param Event $event
     * @param EntityInterface $entity
     * @param ArrayObject $options
     * @param string $operation
     * @return null
     */
    public function beforeRules(Event $event, EntityInterface $entity, ArrayObject $options, string $operation)
    {
        return null;
    }

    /**
     * @param Event $event
     * @param EntityInterface $entity
     * @param ArrayObject $options
     * @return null
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        return null;
    }

    /**
     * @param Event $event
     * @param EntityInterface $entity
     * @param ArrayObject $options
     * @return null
     */
    public function afterSaveCommit(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        return null;
    }

    /**
     * @param Event $event
     * @param EntityInterface $entity
     * @param ArrayObject $options
     * @return null
     */
    public function afterDelete(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        return null;
    }

    /**
     * @param Event $event
     * @param EntityInterface $entity
     * @param ArrayObject $options
     * @return null
     */
    public function afterDeleteCommit(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        return null;
    }

    /**
     * @param Event $event
     * @param EntityInterface $entity
     * @param ArrayObject $options
     * @param bool $result
     * @param string $operation
     * @return null
     */
    public function afterRules(
        Event $event,
        EntityInterface $entity,
        ArrayObject $options,
        bool $result,
        string $operation
    ) {
        return null;
    }

    /**
     * @param Event $event
     * @param Validator $validator
     * @param string $name
     * @return null
     */
    public function buildValidator(Event $event, Validator $validator, string $name)
    {
        return null;
    }

}
