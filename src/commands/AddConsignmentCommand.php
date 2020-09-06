<?php

namespace Commands;

require_once __DIR__ . "/../../vendor/autoload.php";

use App\DispatchPeriod;
use App\Courier;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class AddConsignmentCommand - adds a new consignment to the active Dispatch Period
 *
 * The Add Consignment Command takes the courier name, the consignment number and a
 * description of the consignment and adds details of the consignment to the active
 * Dispatch Period.
 *
 * @package Commands
 */
class AddConsignmentCommand extends Command {
    /**
     * The name used to run the Command
     * @var string
     */
    protected static $defaultName = "add-consignment";

    /**
     * Configures the Command
     */
    protected function configure() {
        //add a description to the Command
        $this
            ->setDescription("Adds a consignment to the current dispatch period.");

        //add necessary arguments to the Command
        $this
            ->addArgument("courierName", InputArgument::REQUIRED, "The courier name")
            ->addArgument("description", InputArgument::OPTIONAL, "A description of the consignment.");
    }

    /**
     * Executes the Command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        //get input arguments
        $courierName = $input->getArgument("courierName");
        $description = $input->getArgument("description");

        //get the active Dispatch Period
        $dp = DispatchPeriod::get();

        //generate consignment
        $courier = new Courier($courierName);
        $cons = $courier->generateConsignment($description);
        $cons->setCourierName($courierName);

        //add the consignment to the Dispatch Period
        $dp->addConsignment($cons);

        //output to confirm consignment was added
        $output->writeln("<info>New consignment added.</info>");
        $output->writeln("<info>Courier: " . $courierName . ", Consignment No: " . $cons->getNumber() . ", Description: " . $description . "</info>");

        return Command::SUCCESS;
    }
}
