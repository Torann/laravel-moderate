<?php namespace Torann\Moderate;

use Closure;

class Cache {

    /**
     * Caching enabled
     *
     * @var bool
     */
    protected $enabled = true;

    /**
     * Timestamp key.
     *
     * @var string
     */
    protected $timestampKey = 'TorannModerateTimeStamp';

    /**
     * Timestamp Manager
     *
     * @var null|\Torann\Moderate\Timestamps\TimestampInterface
     */
    protected $timestampManager;

    /**
     * Path to the cached file.
     *
     * @var string
     */
    protected $path;

    /**
     * Collection of cached entries.
     *
     * @var array
     */
    protected $entries = array();

    /**
     * Create a new instance.
     *
     * @param  string $manifestPath
     * @param  array $config
     */
    public function __construct($manifestPath, array $config)
    {
        // Enabled
        $this->enabled = array_get($config, 'cacheBlacklist', true);

        // Set path
        $this->path = $manifestPath.DIRECTORY_SEPARATOR.'torann_moderate.json';

        // Timestamp manager class
        $timestampManager = array_get($config, 'timestamp_manager', '');

        // Instantiate timestamp manager
        if (class_exists($timestampManager))
        {
            $this->timestampManager = new $timestampManager();
        }

        // Load values
        if ($this->enabled && file_exists($this->path))
        {
            $this->entries = json_decode(file_get_contents($this->path), true);
        }
    }

    /**
     * Get an item from the cache, or store the default value forever.
     *
     * @param  \Closure  $callback
     * @return mixed
     */
    public function remember(Closure $callback)
    {
        // If the item exists in the cache we will just return this immediately
        // otherwise we will execute the given Closure and cache the result.
        if ($this->expired() === false)
        {
            return $this->entries;
        }

        $this->save($callback());

        return $this->entries;
    }

    /**
     * Remove all cached entries.
     *
     * @return bool
     */
    public function flush()
    {
        return $this->save(null);
    }

    /**
     * Get last updated timestamp.
     *
     * @return string
     */
    public function getTimestamp()
    {
        return array_get($this->entries, $this->timestampKey, null);
    }

    /**
     * Check if cached as expired.
     *
     * @return bool
     */
    public function expired()
    {
        // Update if empty or just a timestamp
        if (empty($this->entries) || count($this->entries) <= 1)
        {
            return true;
        }

        // Check timestamps
        if ($this->timestampManager)
        {
            return $this->timestampManager->check($this->getTimestamp());
        }

        return false;
    }

    /**
     * Store values to cache.
     *
     * @param  array $values
     * @return bool
     */
    private function save($values)
    {
        // Set values
        $this->entries = $values;

        // No saving when disabled
        if ($this->enabled === false) {
            return true;
        }

        // Update time - now
        $updated = time();

        // Update timestamp
        $this->entries[$this->timestampKey] = $updated;

        // Update timestamp manager
        if ($this->timestampManager)
        {
            $this->timestampManager->update($updated);
        }

        return (bool) file_put_contents($this->path, json_encode($this->entries));
    }
}
