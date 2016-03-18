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
            $moderate = false;

            foreach ($model->moderate as $id => $moderation) {
                $rules = explode('|', $moderation);

                foreach ($rules as $rule) {
                    $action = explode(':', $rule);
                    $options = isset($action[1]) ? $action[1] : null;

                    if (app(Moderator::class)->$action[0]($model->$id, $options)) {
                        $moderate = true;
                        continue;
                    }
                }

                // No reason to keep going
                if ($moderate === true) {
                    continue;
                }
            }

            $model->moderated = $moderate;
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
