<?php

namespace TurFramework\Database\Concerns;

trait ModelAttributes
{
    /**
     * @var mixed attributes
     */
    protected $attributes = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    /**
     * Set model attribute 
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    protected function setAttribute($key, $value)
    {

        $method = 'set' . ucfirst($key) . 'Attribute';
        if (method_exists($this, $method)) {
            return $this->$method($value);
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    protected function getAttribute($key)
    {

        $method = 'get' . ucfirst($key) . 'Attribute';

        if (array_key_exists($key, $this->attributes)) {
            $value = $this->attributes[$key];
        }
        if (method_exists($this, $method)) {
            return $this->$method($value);
        }


        return  $value;
    }

    protected function fill($attributes)
    {

        $fillable = $this->fillableFromArray($attributes);

        foreach ($fillable as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }
    /**
     * Get the fillable attributes of a given array.
     *
     * @param  array  $attributes
     * @return array
     */
    protected function fillableFromArray(array $attributes)
    {
        if (count($this->getFillable()) > 0) {
            return array_intersect_key($attributes, array_flip($this->getFillable()));
        }

        return $attributes;
    }
    /**
     * Get all of the current attributes on the model.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
    /**
     * Get the fillable attributes for the model.
     *
     * @return array<string>
     */
    public function getFillable()
    {
        return $this->fillable;
    }
}
