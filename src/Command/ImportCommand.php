<?php
namespace App\Command;

use App\Entity\Drink;
use App\Service\DuplicateChecker;
use App\Service\DrinkService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SimpleXMLElement;
use XMLReader;

#[AsCommand(
    name: 'app:import',
    description: 'Importiert Daten von einer Datei in die Datenbank',
    hidden: false
)]
class ImportCommand extends Command{

    private EntityManagerInterface $entityManager;
    private DuplicateChecker $duplicateChecker;
    private DrinkService $drinkService;
    //Declares how many rows of data will be added to the table at once
    public int $batchSize;
    //Used to count the number of rows added. This is needed for the batched upload of data, but can also be used for other purposes like counting the number of rows uploaded
    public int $count;

    public function __construct(EntityManagerInterface $entityManager, DuplicateChecker $duplicateChecker, DrinkService $drinkService) {
        parent::__construct();
        $this->drinkService = $drinkService;
        $this->duplicateChecker = $duplicateChecker;
        $this->entityManager = $entityManager;
        $this->batchSize=100;
        $this->count=0;
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
            $output->writeln('Finished importing the file. ' . $this->count . ' rows have been added.');
            return Command::SUCCESS;
        }
        //If non of the if-Statements is triggered, it means we are dealing with a non-supported format
        $output->writeln('This format is currently not supported. We only support .xml');
        return Command::INVALID;
    }

    //Transfer data from an xml into a doctrine entity before adding it to the database.
    //By using doctrine, we can change the database in .env without having to adapt the code.
    public function importXml(String $data) : void {
        
        //Use XMLReader to read the .xml file
        $xml = new XMLReader();
        $xml->open($data);

        //Iterate through everything until the first item tag is reached, indicating our first input
        while($xml->read() && $xml->name != 'item');
        
        while($xml->name == 'item') {
            $row=new SimpleXMLElement($xml->readOuterXml());
            //If the ID already exists in the database, it is skipped
            if ($this->duplicateChecker->duplicateID((int)$row->entity_id,$this->entityManager)){
                //Move pointer to the next item and continue with the while loop
                $xml->next('item');
	            unset($row);
                continue;
            } 
            $drink = $this->drinkService->xmlToDrink($row);
            //Save the current entity in our local storage
            $this->entityManager->persist($drink);
            $this->count++;
            //Seperate function to add our drink to the DB
            $this->addBatchToDb();
            //Move to the next item
            $xml->next('item');
	        unset($row);
        }
        //add the remaining rows
        $this->entityManager->flush();  
    }

    //Checks if there is already data with that ID in the databse. Will log the relevant ID
    

    //Uses modulo operator to add our data to the datavase after batchSize rows of data were added
    public function addBatchToDb(){
        if ($this->count % $this->batchSize == 0)
            {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
    }
}