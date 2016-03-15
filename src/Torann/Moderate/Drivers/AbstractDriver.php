<?php

namespace Torann\Moderate\Drivers;

use Illuminate\Support\Arr;

abstract class AbstractDriver
{
    /**
     * System local.
     *
     * @var string
     */
    protected $locale = null;

    /**
     * Moderate config
     *
     * @var array
     */
    protected $config;

    /**
     * Create a new driver instance.
     *
     * @param array  $config
     * @param string $locale
     */
    public function __construct(array $config = [], $locale)
    {
        $this->config = $config;
        $this->locale = $locale;
    }

    /**
     * Get configuration value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    protected function getConfig($key, $default = null)
    {
        return Arr::get($this->config, $key, $default);
    }

    /**
     * Get system locale.
     *
     * @return string
     */
    protected function getLocal()
    {
        return $this->locale;
    }

    /**
     * Get list of blacklisted items
     *
     * @return array
     */
    abstract public function getList();
}