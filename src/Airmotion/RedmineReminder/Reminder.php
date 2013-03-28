<?php
namespace Airmotion\RedmineReminder;

use Symfony\Component\Console\Application;
use Airmotion\RedmineReminder\Command\ListResolvedIssuesCommand;
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
     * @param array $config
     */
    public function __construct($config)
    {
        parent::__construct('Airmotion Redmine Reminder (ARR)', '1.0');

        //load config
        Configuration::getInstance()->loadFromArray($config);

        //add command
        $this->add(new ListResolvedIssuesCommand());
    }
}