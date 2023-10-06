<?php

namespace App\Command;

use App\Core\InboundPort\SegmentServiceInterface;
use DateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'lleego:avail',
    description: 'Requests a fake provider',
)]
class AvailCommand extends Command
{
    /**
     * @var SegmentServiceInterface The interface that permits interact with the core
     */
    protected SegmentServiceInterface $segmentService;

    public function __construct(SegmentServiceInterface $segmentService)
    {
        parent::__construct();

        $this->segmentService = $segmentService;
    }

    /**
     * Allows to add the arguments to the command
     */
    protected function configure(): void
    {
        $this
            ->addArgument('origin', InputArgument::REQUIRED, 'The origin.')
            ->addArgument('destination', InputArgument::REQUIRED, 'The destination.')
            ->addArgument('date', InputArgument::REQUIRED, 'The date for the flight availability.');
    }

    /**
     * @param  InputInterface  $input  The variable that handles the input
     * @param  OutputInterface $output The variable that handles the output
     * @return The state in which the command finishes
     *
     * It executes part of the test
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $origin = $input->getArgument('origin');
        $destination = $input->getArgument('destination');
        $date = DateTime::createFromFormat('Y-m-d', $input->getArgument('date'));

        // Here it would go the implementation of $input validation abstracted
        // into another class to make use of the SAME RULES here AND in the endpoint
        // If you want me to implement the validation just please let me know

        $segments = $this->segmentService->getSegmentsInArray($origin, $destination, $date);

        $this->printSegmentInTables($output, $segments);

        $io->success('Success.');

        return Command::SUCCESS;
    }


    /**
     * @param OutputInterface $output
     * @param array           $segments
     *
     * I wanted to leave Symfony OUT of the core, so that's why I format to table here. If we wanted
     * this eventually to be reused somewhere else, we could look for other options. Or not use the
     * symfony library and would put this into the core
     */
    protected function printSegmentInTables(OutputInterface $output, array $segments): void
    {
        $table = new Table($output);

        $rows = array_map(
            function ($item) {
                return [
                $item['originCode'],
                $item['originName'],
                $item['destinationCode'],
                $item['destinationName'],
                $item['start'],
                $item['end'],
                $item['transportNumber'],
                $item['companyCode'],
                $item['companyName'],
                ];
            }, $segments
        );

        $table
            ->setHeaders(
                [
                'Origin Code',
                'Origin Name',
                'Destination Code',
                'Destination Name',
                'Start',
                'End',
                'Transport Number',
                'Company Code',
                'Company Name',
                ]
            )
            ->setRows($rows);
        $table->render();
    }
}
