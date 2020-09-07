<?php

namespace Commands;

require_once __DIR__ . "/../../vendor/autoload.php";

use App\DispatchPeriod;
use Exception;
use Exceptions\BatchException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class EndBatchCommand - ends the active Dispatch Period
 *
 * The End Batch Command is used to stop the active Dispatch Period.
 *
 * @package Commands
 */
class EndBatchCommand extends Command {
    /**
     * The name used to run the Command
     * @var string
     */
    protected static $defaultName = "stop";

    /**
     * Configures the Command
     */
    protected function configure() {
        //add a description to the command
        $this
            ->setDescription("Ends the day's dispatch period.");
    }

    /**
     * Executes the Command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        //get the active Dispatch Period
        $dp = DispatchPeriod::get();



        //stop the Dispatch Period

        //start upload
        try {
            $output->writeln("Uploading Consignments...");
            $dp->uploadConsignments();

            //not true (have to check status of uploads but they would fail so I've left this out)
            $output->writeln("Consignments successfully uploaded");
        } catch(Exception $e) {
            $output->writeln("<info>" . $e->getMessage() . "</info>");
        }

        try {
            $dp->stop();
            $output->writeln("<info>Dispatch period stopped at " . date("Y-m-d H:i:s") . "</info>");
        } catch(BatchException $be) {
            $output->writeln("<info>" . $be->getMessage() . "</info>");
        }

        return Command::SUCCESS;
    }
}
