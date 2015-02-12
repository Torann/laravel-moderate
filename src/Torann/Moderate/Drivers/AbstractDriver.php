<?php namespace Torann\Moderate\Drivers;

abstract class AbstractDriver {

    /**
     * Moderate config
     *
     * @var array
     */
    protected $config;

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        $this->config = $config;
    }

    /**
     * Get list of blacklisted items
     *
     * @return array
     */
    abstract public function getList();
}