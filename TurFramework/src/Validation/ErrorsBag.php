<?php

namespace TurFramework\Validation;


class ErrorsBag
{
    protected $errors = [];


    /**
     * Add an error message to the bag.
     *
     * @param string $key
     * @param string $message
     * @return void
     */
    public function add($key, $message)
    {

        $this->errors[$key][] = $message;
    }

    /**
     * Determine if the message bag has any messages.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return !$this->any();
    }

    /**
     * Determine if the message bag has any messages.
     *
     * @return bool
     */
    public function isNotEmpty()
    {
        return $this->any();
    }
    /**
     * Determine if any errors exist in the bag.
     *
     * @return bool
     */
    public function any()
    {
        return count($this->errors) > 0;
    }

    /**
     * Get all errors from the bag.
     *
     * @return array
     */
    public function all()
    {
        return $this->errors;
    }


    /**
     * Determine if errors exist for a given key.
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->errors[$key]);
    }

    /**
     * Get the first error message for a given key.
     *
     * @param string $key
     * @return string|null
     */
    public function first($key)
    {

        return isset($this->errors[$key]) ? $this->errors[$key][0] : null;
    }
}
