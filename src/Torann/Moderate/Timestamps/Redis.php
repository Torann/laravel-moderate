<?php namespace Torann\Moderate\Timestamps;

use Illuminate\Support\Facades\Redis as Timestamp;

class Redis implements TimestampInterface {

    /**
     * Check for expired cache timestamp
     *
     * @param  string $cached_at
     * @return bool
     */
    public function check($cached_at)
    {
        $timestamp = Timestamp::get('torann_moderate_updated_at');

        return ($timestamp && $timestamp > $cached_at);
    }

    /**
     * Update timestamp.
     *
     * @param  string $cached_at
     * @return bool
     */
    public function update($cached_at)
    {
        return Timestamp::set('torann_moderate_updated_at', $cached_at);
    }
}
