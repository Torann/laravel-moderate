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
            $failure = 0;

            foreach ($model->moderate as $id => $moderation) {
                $rules = explode('|', $moderation);

                foreach ($rules as $rule) {
                    $action = explode(':', $rule);
                    $options = isset($action[1]) ? $action[1] : null;

                    if (app(Moderator::class)->$action[0]($model->$id, $options)) {
                        $failure++;
                    }
                }
            }

            $model->moderated = $failure > 0;
        });

        static::created(function ($model) {
            if ($model->isModerated()) {
                event(new Events\Moderated($model));
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
        return $this->moderated == 1;
    }
}
