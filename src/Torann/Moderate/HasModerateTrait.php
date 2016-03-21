<?php

namespace Torann\Moderate;

trait HasModerateTrait
{
    /**
     * Register eloquent event handlers.
     *
     * @return void
     */
    public static function bootHasModerateTrait()
    {
        static::creating(function ($model) {
            $model->moderated = app(Moderator::class)->check($model);
        });

        static::created(function ($model) {
            if ($model->isModerated()) {
                event(new Events\Moderated($model));
            }
        });
    }

    /**
     * Return array of attributes to check.
     *
     * @return array
     */
    public function getModerateList()
    {
        return $this->moderate;
    }

    /**
     * Get moderated attribute as a proper boolean.
     *
     * @param  bool $value
     *
     * @return bool
     */
    public function getModeratedAttribute($value)
    {
        return (bool) $value;
    }

    /**
     * Check if the resource is moderated.
     *
     * @return bool
     */
    public function isModerated()
    {
        return $this->moderated == true;
    }
}
