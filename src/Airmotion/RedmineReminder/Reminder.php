<?php
namespace Airmotion\RedmineReminder;

use Symfony\Component\Console\Application;
use Airmotion\RedmineReminder\Command\ListResolvedIssuesCommand;
use Airmotion\RedmineReminder\Command\SendResolvedIssuesCommand;
use Airmotion\RedmineReminder\Configuration;
/**
 * RedmineReminder application.
 *
 * @author Fridolin Koch <fridolin.koch@airmotion.de>
 */
class Reminder extends Application
{

    /**
     * Reminder constructor.
     * @param array  $config
     * @param string $rootPath
     */
    public function __construct($config)
    {
        parent::__construct('Airmotion Redmine Reminder (ARR)', '1.0');

        //load config
        Configuration::getInstance()->loadFromArray($config);

        //init twig
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/Resources/Emails');

        //set twig
        Configuration::getInstance()->setTwig(new \Twig_Environment($loader));

        //add command
        $this->add(new ListResolvedIssuesCommand());
        $this->add(new SendResolvedIssuesCommand());
    }
}