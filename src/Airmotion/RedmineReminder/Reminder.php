<?php
namespace Airmotion\RedmineReminder;

use Symfony\Component\Console\Application;
use Airmotion\RedmineReminder\Command\ListResolvedIssuesCommand;
/**
 * Calculator application.
 *
 * @author Fridolin Koch<fridolin.koch@airmotion.de>
 */
class Reminder extends Application {
    /**
     * Calculator constructor.
     */
    public function __construct() {

        parent::__construct('Airmotion Redmine Reminder (ARR)', '1.0');

        //add command
        $this->add(new ListResolvedIssuesCommand());
    }
}