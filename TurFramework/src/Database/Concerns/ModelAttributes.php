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

        if ($this->hasMutator($key)) {
            $method = $this->getMethodNameForAttribute($key);
            return $this->$method($value);
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Determine if a get|set mutator exists for an attribute.
     *
     * @param  string  $key
     * @param  string  $method
     * @return bool
     */
    private function hasMutator($key, $method = 'set')
    {
        return method_exists($this, $method . ucfirst($key) . 'Attribute');
    }

    private function getMethodNameForAttribute($key, $method = 'set')
    {
        return  $method . ucfirst($key) . 'Attribute';
    }

    protected function getAttribute($key)
    {
        if (array_key_exists($key, $this->attributes) && $this->hasMutator($key, 'get')) {
            $method = $this->getMethodNameForAttribute($key, 'get');

            $value = $this->attributes[$key];

            return $this->$method($value);
        }

        return $this->attributes[$key];
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
     * Set the array of model attributes
     *
     * @param  array  $attributes
     * @param  bool  $sync
     * @return $this
     */
    public function setRawAttributes(array $attributes)
    {
        $this->attributes = $attributes;

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
