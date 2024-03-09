<?php

namespace TurFramework\Database\Concerns;

trait ModelTimestamps
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The name of the "created at" column.
     *
     * @var string|null
     */
    const CREATED_AT = 'created_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    const UPDATED_AT = 'updated_at';

    /**
     * Get the name of the "created at" column.
     *
     * @return string|null
     */
    public function getCreatedAtColumn()
    {
        return static::CREATED_AT;
    }

    /**
     * Get the name of the "updated at" column.
     *
     * @return string|null
     */
    public function getUpdatedAtColumn()
    {
        return static::UPDATED_AT;
    }
    /**
     * Set the value of the "created at" attribute.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function setCreatedAt($value)
    {

        // the value of static::CREATED_AT 'created_at' = $value 
        $this->{$this->getCreatedAtColumn()} = $value;

        return $this;
    }

    /**
     * Set the value of the "updated at" attribute.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function setUpdatedAt($value)
    {
        $this->{$this->getUpdatedAtColumn()} = $value;

        return $this;
    }
    /**
     * Determine if the model uses timestamps.
     *
     * @return bool
     */
    public function usesTimestamps()
    {
        return $this->timestamps;
    }


    public function getDateNow()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Update the creation and update timestamps.
     *
     * @return $this
     */
    public function updateTimestamps()
    {


        $time = $this->getDateNow();

        $updatedAtColumn = $this->getUpdatedAtColumn();

        if (!is_null($updatedAtColumn)) {
            $this->setUpdatedAt($time);
        }

        $createdAtColumn = $this->getCreatedAtColumn();

        if (!$this->exists && !is_null($createdAtColumn)) {
            $this->setCreatedAt($time);
        }


        return $this;
    }
}
