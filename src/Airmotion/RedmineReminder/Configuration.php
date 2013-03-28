<?php
namespace Airmotion\RedmineReminder;

/**
 * The configuration class, not the best way to solve this but its a fast way
 *
 * @author Fridolin Koch <fridolin.koch@airmotion.de>
 */
class Configuration
{
    /**
     * @var Configuration
     */
    private static $instance;

    /**
     * @var string
     */
    protected $redmineUrl;

    /**
     * @var string
     */
    protected $redmineToken;

    /**
     * Constructor is private
     */
    private function __construct()
    {
    }

    /**
     * @return Configuration
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * @param array $config
     */
    public function loadFromArray(array $config)
    {
        if (array_key_exists('redmineUrl', $config)) {
            $this->redmineUrl = $config['redmineUrl'];
        }

        if (array_key_exists('redmineToken', $config)) {
            $this->redmineUrl = $config['redmineToken'];
        }
    }

    /**
     * @param string $redmineToken
     */
    public function setRedmineToken($redmineToken)
    {
        $this->redmineToken = $redmineToken;
    }

    /**
     * @return string
     */
    public function getRedmineToken()
    {
        return $this->redmineToken;
    }

    /**
     * @param string $redmineUrl
     */
    public function setRedmineUrl($redmineUrl)
    {
        $this->redmineUrl = $redmineUrl;
    }

    /**
     * @return string
     */
    public function getRedmineUrl()
    {
        return $this->redmineUrl;
    }


}
