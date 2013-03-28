<?php
require 'vendor/autoload.php';

use Airmotion\RedmineReminder\Reminder;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

//read config
$yaml = new Parser();
try {
    $value = $yaml->parse(file_get_contents('./config.yml'));

    $app = new Reminder($value['config'], __DIR__);
    $app->run();

} catch (ParseException $e) {
    printf("Unable to parse the YAML string: %s", $e->getMessage());
}

