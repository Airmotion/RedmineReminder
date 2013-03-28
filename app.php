<?php
require 'vendor/autoload.php';

use Airmotion\RedmineReminder\Reminder;

$app = new Reminder();
$app->run();