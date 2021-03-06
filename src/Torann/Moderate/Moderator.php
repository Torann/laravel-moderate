<?php

namespace Torann\Moderate;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Cache\Factory as CacheContract;

class Moderator
{
    /**
     * Package Config
     *
     * @var array
     */
    protected $config = [];

    /**
     * System local.
     *
     * @var string
     */
    protected $locale = null;

    /**
     * \Torann\Moderate\Cache
     *
     * @var array
     */
    protected $cache;

    /**
     * Blacklist driver
     *
     * @var Drivers\AbstractDriver
     */
    protected $driver;

    /**
     * Blacklist Regex
     *
     * @var string
     */
    protected $blackListRegex = null;

    /**
     * Create a new moderator instance.
     *
     * @param  array         $config
     * @param  CacheContract $cache
     * @param  string        $locale
     */
    function __construct(array $config, CacheContract $cache, $locale)
    {
        $this->config = $config;
        $this->cache = $cache;

        // Set locale if supported
        $this->locale = $this->getConfig('support_locales', false) ? $locale : null;

        // Load blacklist
        $this->loadBlacklist();
    }

    /**
     * Reload blacklist data
     */
    public function reloadBlacklist()
    {
        $this->cache->flush($this->getCacheKey());

        $this->blackListRegex = null;

        $this->loadBlacklist();
    }

    /**
     * Check model using the moderate rules.
     *
     * @param object $model
     *
     * @return bool
     */
    public function check($model)
    {
        $moderated = false;

        foreach ($model->getModerateList() as $id => $moderation) {
            $rules = explode('|', $moderation);

            foreach ($rules as $rule) {
                $action = explode(':', $rule);
                $options = isset($action[1]) ? $action[1] : null;

                $method = $action[0];
                if ($this->$method($model->$id, $options)) {
                    return $moderated = true;
                }
            }

            // No reason to keep going
            if ($moderated === true) {
                return $moderated;
            }
        }

        return $moderated;
    }

    /**
     * Checks the text if it contains any word that is blacklisted.
     *
     * @param string $text
     *
     * @return bool
     */
    public function blacklist($text)
    {
        // No blacklist regex then there isn't much to do
        if ($this->blackListRegex === null) {
            return false;
        }

        // Normalize string before passing
        $text = $this->prepare($text);

        // Look for matches
        return preg_match($this->blackListRegex, $text) > 0;
    }

    /**
     * Checks the text if it contains any word that is blacklisted.
     *
     * @param string $text
     * @param string $limit
     *
     * @return bool
     */
    public function links($text, $limit = null)
    {
        // Set link limit
        $limit = is_null($limit) ? $this->getConfig('defaultMaxLinks', 10) : $limit;

        // Normalize string before passing
        $text = $this->prepare($text);

        // Get link matches
        preg_match_all("!((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)!", $text, $matches);

        return (count($matches[0]) >= (int) $limit);
    }

    /**
     * Get moderation driver.
     *
     * @return Drivers\AbstractDriver
     */
    public function getDriver()
    {
        if ($this->driver) {
            return $this->driver;
        }

        // Get driver configuration
        $config = $this->getConfig('drivers.' . $this->getConfig('driver'), []);

        // Get driver class
        $driver = Arr::pull($config, 'class');

        // Create driver instance
        return $this->driver = new $driver($config, $this->locale);
    }

    /**
     * Get cache key with locale appended if applicable.
     *
     * @return string
     */
    public function getCacheKey()
    {
        return $this->getConfig('cache.key', 'moderate.blacklist')
            . ($this->locale ? ".{$this->locale}" : '');
    }

    /**
     * Get configuration value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getConfig($key, $default = null)
    {
        return Arr::get($this->config, $key, $default);
    }

    /**
     * Used to normalize string before passing
     * it to detectors
     *
     * @param string $text
     *
     * @return string
     */
    protected function prepare($text)
    {
        if ($this->getConfig('asciiConversion', true)) {
            setlocale(LC_ALL, 'en_us.UTF8');

            $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        }

        $text = trim(strtolower($text));
        $text = str_replace(["\t", "\r\n", "\r", "\n"], "", $text);

        // Convert some characters that 'MAY' be used as alias
        $text = str_replace(["@", "$", "[dot]", "(dot)"], ["at", "s", ".", "."], $text);

        // Strip multiple dots (.) to one. eg site......com to site.com
        $text = preg_replace("/\.{2,}/", ".", $text);

        // Remove special characters
        return preg_replace("/[^a-zA-Z0-9-\.\s]/", "", $text);
    }

    /**
     * Load blacklist data
     *
     * @return string
     */
    protected function loadBlacklist()
    {
        // Check if caching is enabled
        if ($this->getConfig('cache.enabled', false) === false) {
            return $this->blackListRegex = $this->createRegex();
        }

        // Get Black list items
        return $this->blackListRegex = $this->cache->rememberForever($this->getCacheKey(), function () {
            return $this->createRegex();
        });
    }

    /**
     * Create blacklist regex from driver data.
     *
     * @return string
     */
    protected function createRegex()
    {
        // Load list from driver
        if (empty($list = $this->getDriver()->getList())) {
            return null;
        }

        return sprintf('/\b(%s)\b/i', implode('|', array_map(function ($value) {
            if (isset($value[0]) && $value[0] == '[') {
                return $value;
            }
            else if (preg_match("/\r\n|\r|\n/", $value)) {
                return preg_replace("/\r\n|\r|\n/", "|", $value);
            }

            return preg_quote($value);

        }, $list)));
    }
}
