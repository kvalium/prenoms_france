<?php

namespace PrenomsApiBundle\Command;

use Doctrine\ORM\EntityManager;
use PrenomsApiBundle\Entity\FirstName;
use PrenomsApiBundle\Entity\Metrics;
use PrenomsApiBundle\Entity\Sex;
use PrenomsApiBundle\Entity\Year;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class PrenomsUpdateCommand extends ContainerAwareCommand
{
    /** @var  EntityManager */
    private $em;

    protected function configure()
    {
        $this
            ->setName('prenoms:update')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $argument = $input->getArgument('argument');

        if ($input->getOption('option')) {
            // ...
        }
        // Showing when the script is launched
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        // Importing CSV on DB via Doctrine ORM
        $this->import($input, $output);

        // Showing when the script is over
        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    protected function import(InputInterface $input, OutputInterface $output)
    {
        // Getting php array of data from CSV
        $data = $this->get($input, $output);

        if(!$data){
            $output->writeln('<error>NO CSV FILE</error>');
            exit();
        }

        // Getting doctrine manager
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        // Turning off doctrine default logs queries for saving memory
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);

        // Define the size of record, the frequency for persisting the data and the current index of records
        $size = count($data);
        $batchSize = 20;
        $i = 1;

        // Starting progress
        $progress = new ProgressBar($output, $size);
        $progress->start();

        // Processing on each row of data
        foreach ($data as $row) {
            $progress->advance();

            $name = utf8_encode($row['prenom']);
            $firstName = $this->getFirstName($name);
            $year = $this->getYear((int)$row['annee']);
            if(!$year){
                continue;
            }
            $sex = $this->getSex($row['sexe']);

            /** @var Metrics $metric */
            $metric = new Metrics();
            $metric->setFirstname($firstName);
            $metric->setSex($sex);
            $metric->setYear($year);
            $metric->setCount($row['nombre']);
            $this->em->persist($metric);
            $this->em->flush();

            // Each 20 users persisted we flush everything
            if (($i % $batchSize) === 0) {
                $this->em->clear();
            }

            $i++;
        }
        $progress->finish();

        // Flushing and clear data on queue
        $this->em->clear();

        // Ending the progress bar process
        $progress->finish();
    }

    /**
     * @param $value
     * @return FirstName
     */
    protected function getFirstName($value)
    {
        /** @var FirstName $firstName */
        $firstName = $this->em->getRepository('PrenomsApiBundle:FirstName')
            ->findOneByFirstName($value);
        // If the user doest not exist we create one
        if (!is_object($firstName)) {
            $firstName = new FirstName();
            $firstName->setFirstName($value);
            $this->em->persist($firstName);
            $this->em->flush();
        }
        return $firstName;
    }

    /**
     * @param $year
     * @return bool|Year
     */
    protected function getYear($year)
    {
        if(!$year){
            return false;
        }
        /** @var Year $yearEnt */
        $yearEnt = $this->em->getRepository('PrenomsApiBundle:Year')
            ->findOneByYear($year);
        // If the user doest not exist we create one
        if (!is_object($yearEnt)) {
            $yearEnt = new Year();
            $yearEnt->setYear($year);
            $this->em->persist($yearEnt);
            $this->em->flush();
        }

        return $yearEnt;
    }
    /**
     * @param $sex
     * @return Sex
     */
    protected function getSex($sex)
    {
        /** @var Sex $sexEnt */
        $sexEnt = $this->em->getRepository('PrenomsApiBundle:Sex')
            ->findOneById($sex);

        return $sexEnt;
    }

    protected function get(InputInterface $input, OutputInterface $output)
    {
        // Getting the CSV from filesystem
        $fileName = 'web/uploads/import/nat2015.csv';

        // Using service for converting CSV to PHP Array
        $converter = $this->getContainer()->get('import.csvtoarray');
        $data = $converter->convert($fileName);

        return $data;
    }


}
