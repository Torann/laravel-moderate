<?php namespace Torann\Moderate;

use Event;
use Moderate;

trait HasModerate
{
    /**
     * Register eloquent event handlers.
     *
     * @return void
     */
    public static function bootModerate()
    {
        static::creating(function ($model)
        {
            $failure = 0;

            foreach($model->moderate as $id => $moderation)
            {
                $rules = explode('|', $moderation);

                foreach($rules as $rule)
                {
                    $action = explode(':', $rule);
                    $options = isset($action[1]) ? $action[1] : null;

                    if(Moderate::$action[0]($model->$id, $options))
                    {
                        $failure++;
                    }
                }
            }

            $model->moderated = $failure > 0;
        });

        // Was it moderated
        static::created(function ($model)
        {
            if ($model->isModerated())
            {
                Event::fire('moderation.moderated', $model);
            }
        });
    }

    /**
     * Check if the resource is moderated.
     *
     * @return bool
     */
    public function isModerated()
    {
        return (int) $this->moderated === 1;
    }
}
