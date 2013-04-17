<?php
namespace Airmotion\RedmineReminder\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Redmine\Client;

use Airmotion\RedmineReminder\Configuration;

/**
 * Class ListResolvedIssuesCommand
 *
 * @author Fridolin Koch <fridolin.koch@airmotion.de>
 */
class ListResolvedIssuesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('issues:resolved:list')
            ->setDescription('Show resolved issues that have not been updated for a while (Default 5 days)')
            ->addOption('last-updated', 'l', InputOption::VALUE_OPTIONAL, 'Show only issues that have not been updated for X day (Default: 5, minimum: 1)', 5);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = Configuration::getInstance();

        $age = $input->getOption('last-updated');

        $client = new Client($config->getRedmineUrl(), $config->getRedmineToken());

        /** @var \Redmine\Api\Issue $issue  */
        $issue = $client->api('issue');

        /** @var \Redmine\Api\User $user  */
        $user = $client->api('user');

        //request all resolved issues
        $resolvedIssues = $issue->all(array('status_id' => 3,'limit' => 100))['issues'];
        //array to store old resolved issues
        $badIssues = [];

        foreach ($resolvedIssues as $issue) {

            $updated = \DateTime::createFromFormat('Y/m/d H:i:s O', $issue['updated_on']);

            $now = new \DateTime('now');

            $diff = $updated->diff($now);

            if ($diff->days >= $age) {
                if (!isset($badIssues[$issue['author']['id']])) {
                    $badIssues[$issue['author']['id']] = array();
                }
                $badIssues[$issue['author']['id']][] = $issue;
            }
        }

        foreach ($badIssues as $userId => $issues) {

            //get user
            $usr = $user->show($userId);
            //output result
            $output->writeln(sprintf('%s<%s> has %d resolved issues', $usr['user']['firstname'], $usr['user']['mail'], count($issues)));

        }

    }
}