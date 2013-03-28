<?php
namespace Airmotion\RedmineReminder;

class Configuration
{
    private static $instance;

    private function __construct() {}

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            echo 'Erstelle neue Instanz.';
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
}
