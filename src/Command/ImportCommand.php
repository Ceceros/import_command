<?php
namespace App\Command;

use App\Entity\Drink;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;

#[AsCommand(
    name: 'app:import',
    description: 'Importiert Daten von einer Datei in die Datenbank',
    hidden: false
)]
class ImportCommand extends Command{

    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->logger=$logger;
    }

    protected function configure() : void
    {
        //Adds a requirement to input the name of the file after the command
        $this
        ->addArgument('file',InputArgument::REQUIRED, '');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file=$input->getArgument('file');
        if(!is_file($file)){
            $output->writeln('Unable to find a file. Please make sure that the correct file is placed in the /import_command directory.');
            return Command::INVALID;
        }
        //take the file string, and get the part behind the last dot to get the extension
        $array= explode(".",$file);
        $extension= $array[sizeof($array)-1];

        //Check file extension to trigger the correct function. Support for additional file-types can easily be added with additional if-statements
        if (!strcmp($extension,'xml')){  
            $this->importXml($file);
            $output->writeln('Finsihed importing the file.');
            return Command::SUCCESS;
        }
        //If non of the if-Statements is triggered, it means we are dealing with a non-supported format
        $output->writeln('Sadly, this format is currently not supported. We only support .xml');
        return Command::INVALID;
    }

    //Transfer data from an xml into a doctrine entity before adding it to the database.
    //By using doctrine, we can change the database in .env without having to adapt the code.
    public function importXml(String $data) : void {
        //Create a repository to check for duplicate IPs later
        $repository= $this->entityManager->getRepository(Drink::class);
        //Translate the xml into an iterable object
        $xml=simplexml_load_file($data);
        //Count the number of entries that have been persisted but not flushed to the database. A flush should happen every 100 entries.
        $count=0;
        foreach ($xml->children() as $row) {
            //Create a new Drink entity and insert our data into it
            $drink = new Drink();
            $id=(int)$row->entity_id;
            //If the ID already exists in the database, send a warning and don't add it
            if($repository->find($id)){
                $this->logger->warning('There is already a row in the database with the id ' . $id);
                continue;
            }
            //Any $row element is a list of simplexmlElement. These can be inserted into fields demanding strings, but must be cast into other types like int.
            $drink->setId($id);
            $drink->setCategoryName($row->CategoryName);
            $drink->setSku($row->sku);
            $drink->setName($row->name);
            $drink->setDescription($row->description);
            $drink->setShortdesc($row->shortdesc);
            $drink->setPrice((float)$row->price);
            $drink->setLink($row->link);
            $drink->setImage($row->image);
            $drink->setBrand($row->Brand);
            $drink->setRating((int)$row->Rating);
            $drink->setCaffeine($row->CaffeineType);
            $drink->setCount((int)$row->Count);
            $drink->setFlavored($row->Flavored);
            $drink->setSeasonal($row->Seasonal);
            $drink->setInstock($row->Instock);
            $drink->setFacebook((int)$row->Facebook);
            $drink->setIsKCup((int)$row->IsKCup);
            //Save the current entity in our local storage
            $this->entityManager->persist($drink);
            $count++;
            //After processing 100 entries, add all the entries to the database and empty the entityManager
            if ($count >= 100)
            {
                $this->entityManager->flush();
                $this->entityManager->clear();
                $count = 0;
            }
        }
        //add the remaining rows
        $this->entityManager->flush();  
        $this->entityManager->clear(); 
    }
}