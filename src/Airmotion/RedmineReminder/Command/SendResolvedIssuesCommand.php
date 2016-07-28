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
 * Class SendResolvedIssuesReminderCommand
 *
 * @author Fridolin Koch <fridolin.koch@airmotion.de>
 */
class SendResolvedIssuesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('issues:resolved:send')
            ->setDescription('Send a reminder email to all authors with resolved issues (Default 5 days)')
            ->addOption('last-updated', 'l', InputOption::VALUE_OPTIONAL, 'Show only issues that have not been updated for X day (Default: 5, minimum: 1)', 5)
            ->addOption('override-receiver', null, InputOption::VALUE_OPTIONAL, 'Override the receiver address for debug purposes.', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = Configuration::getInstance();

        $age = $input->getOption('last-updated');

        $receiver = $input->getOption('override-receiver');

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

            $updated = \DateTime::createFromFormat(\DateTime::ISO8601, $issue['updated_on']);
            $now = new \DateTime('now');

            $diff = $updated->diff($now);

            if ($diff->days >= $age) {
                if (!isset($badIssues[$issue['author']['id']])) {
                    $badIssues[$issue['author']['id']] = array();
                }
                $badIssues[$issue['author']['id']][] = $issue;
            }
        }

        $twig = $config->getTwig();

        $transport = \Swift_SendmailTransport::newInstance();

        // Create the Mailer using your created Transport
        $mailer = \Swift_Mailer::newInstance($transport);

        foreach ($badIssues as $userId => $issues) {

            //get user
            $usr = $user->show($userId);
            //if user has no email or is not active
            if (!isset($usr['user']['mail']) || (isset($usr['user']['status']) && $usr['user']['status'] != 1)) {
                continue;
            }

            //output result
            $messageText = $twig->render('resolvedIssuesReminder.html.twig', [
                'name'      => $usr['user']['firstname'],
                'issues'    => $issues,
                'age'       => $age,
                'baseUrl'   => $config->getRedmineUrl()
            ]);

            // Create the message
            $message = \Swift_Message::newInstance()
                ->setSubject('Erinnerung: Gelöste Tickets schließen')
                ->setPriority(1)
                ->setFrom(array('redmine@airmotion.de' => 'Airmotion Redmine'))
                ->setTo(array($usr['user']['mail'] => $usr['user']['firstname'].' '.$usr['user']['lastname']))
                ->setBody($messageText, 'text/html');

            $mailer->send($message);

            //info
            $output->writeln(sprintf('E-Mail sent to %s %s<%s>', $usr['user']['firstname'], $usr['user']['lastname'], $usr['user']['mail']));

        }

    }
}
