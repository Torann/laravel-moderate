<?php

namespace Torann\Moderate\Events;

use Illuminate\Database\Eloquent\Model;

class Moderated
{
    /**
     * Model instance.
     *
     * @var Model
     */
    protected $model;

    /**
     * Create new event instance.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Return Model instance.
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }
}