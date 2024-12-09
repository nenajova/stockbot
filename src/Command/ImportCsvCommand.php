<?php

namespace App\Command;

use App\Entity\Stock;
use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-csv',
    description: 'Import data from CSV files',
)]
class ImportCsvCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->addArgument('folder', InputArgument::REQUIRED, 'path to the file');

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $folder = $input->getArgument('folder');

        if (!is_dir($folder)) {
            $io->error("The folder '$folder' does not exist or is not accessible.");
            return Command::FAILURE;
        }

        $io->title("Importing CSV files from folder: '$folder'");

        // Get all CSV files from the folder
        $files = glob($folder . '/*.csv');

        if (empty($files)) {
            $io->warning('No CSV files found in the folder.');
            return Command::SUCCESS;
        }

        $io->section("Found " . count($files) . " csv files in folder '$folder':");


        $repository = $this->entityManager->getRepository(Company::class);

        foreach ($files as $index =>  $file) {

            $company =  $repository->findOneBy(['name' => pathinfo($file, PATHINFO_FILENAME)]);

            if (empty($company)) {
                continue;
            }

            $totalLines = intval(exec("wc -l '$file'"));
            $io->text($index+1 . ". Importing '" . pathinfo($file, PATHINFO_BASENAME) . "' $totalLines records");

            $progressBar = new ProgressBar($output, $totalLines);

            // Read CSV file
            if (($handle = fopen($file, 'r')) !== false) {
                $headers = fgetcsv($handle); 

                while (($data = fgetcsv($handle)) !== false) {
                    // Map headers to data
                    $record = array_combine($headers, $data);
                    $progressBar->advance();

                    // Create an entity and persist
                    $stock = new Stock();
                    $stock->setDate(new \DateTime($record['Date']));
                    $stock->setOpen(floatval($record['Open']));
                    $stock->setClose(floatval($record['Close']));
                    $stock->setLow(floatval($record['Low']));
                    $stock->setHigh(floatval($record['High']));
                    $stock->setAdjClose(floatval($record['Close']));
                    $stock->setVolume(intval($record['Volume']));
                    $stock->setCompany($company);

                    $this->entityManager->persist($stock);
                }
                
                $this->entityManager->flush();
                $progressBar->finish();
                fclose($handle);
                $io->text("Done.\n");
                
            } else {
                $io->error("Unable to open file: $file");
            }
        }

        $io->success('CSV import completed successfully!');

        return Command::SUCCESS;
    }
}
