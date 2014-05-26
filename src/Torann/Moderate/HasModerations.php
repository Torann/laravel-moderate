<?php namespace Torann\Moderate;

use Moderate;
use Event;

trait HasModerations
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::bootModerations();
    }

    /**
     * Register eloquent event handlers.
     *
     * @return void
     */
    public static function bootModerations()
    {
        static::creating(function ($model) {

            $failure = 0;

            foreach($model->moderations as $id => $moderation) {

                $rules = explode('|', $moderation);

                foreach($rules as $rule) {

                    $action = explode(':', $rule);
                    $options = isset($action[1]) ? $action[1] : null;

                    if(Moderate::$action[0]($model->$id, $options)) {
                        $failure++;
                    }
                }
            }

            $model->moderated = $failure > 0;
        });

        // Was it moderated
        static::created(function ($model) {
            if ($model->moderated) {
                Event::fire('moderations.moderated', $model);
            }
        });
    }

    /**
     * Check is the item is moderated.
     *
     * @return bool
     */
    public function isModerated()
    {
        return $this->moderated == true;
    }
}
