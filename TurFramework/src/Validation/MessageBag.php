<?php

namespace TurFramework\Validation;

class MessageBag
{
    protected $messages = [];


    /**
     * Determine if any messages exist in the bag.
     *
     * @return bool
     */
    public function any()
    {
        return count($this->all()) > 0;
    }
    /**
     * Add an error message to the bag.
     *
     * @param string $key
     * @param string $message
     * @return void
     */
    public function add($key, $message)
    {

        $this->messages[$key] = $message;
    }
    /**
     * Get all messages from the bag.
     *
     * @return array
     */
    public function all()
    {

        $messages = [];

        foreach ($this->messages as $key => $value) {
            $messages[$key] = $value[0];
        }

        return $messages;
    }


    /**
     * Determine if messages exist for a given key.
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->messages[$key]);
    }

    /**
     * Get the first error message for a given key.
     *
     * @param string $key
     * @return string|null
     */
    public function first($key)
    {
        return isset($this->messages[$key]) ? $this->get($key)[0] : '';
    }
    /**
     * Get the errors message for a given key.
     *
     * @param string $key
     * @return string|null|array
     */
    public function get($key)
    {
        return isset($this->messages[$key]) ? $this->messages[$key] : '';
    }
}
