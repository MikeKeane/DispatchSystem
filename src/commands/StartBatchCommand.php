<?php
/**
 * Created by PhpStorm.
 * User: sales
 * Date: 03/09/2020
 * Time: 14:29
 */

namespace Commands;

require_once __DIR__ . "/../../vendor/autoload.php";

use App\DispatchPeriod;
use Exceptions\BatchException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartBatchCommand - starts the day's Dispatch Period
 *
 * The Start Batch Command is used to start the day's Dispatch Period.
 *
 * @package Commands
 */
class StartBatchCommand extends Command {
    /**
     * The name used to run the Command
     * @var string
     */
    protected static $defaultName = "start";

    /**
     * Configures the Command
     */
    protected function configure() {
        //add a description to the command
        $this
            ->setDescription("Starts the day's dispatch period.");
    }

    /**
     * Executes the Command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        //get the active Dispatch Period - if it does not exist, it is created
        $dp = DispatchPeriod::get();

        //try to start the Dispatch Period
        //confirm start of Dispatch Period or warn that there is already an active Dispatch Period
        try {
            $dp->start();
            $output->writeln("<info>Dispatch period started @ " . $dp->getStartTime() . ".</info>");

        } catch(BatchException $e) {
            $output->writeln("<error>" . $e->getMessage() . "</error>");
            $output->writeln("<info>You must end this dispatch period before starting new one.</info>");
            $output->writeln("<info>To end this dispatch period run 'php bin/dispatchConsole.php stop'.</info>");

        }

        return Command::SUCCESS;

    }
}
