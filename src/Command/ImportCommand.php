<?php
namespace App\Command;

use XMLReader;
use App\Entity\Drink;
use SimpleXMLElement;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use SebastianBergmann\RecursionContext\Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import',
    description: 'Importiert Daten von einer Datei in die Datenbank',
    hidden: false
)]
class ImportCommand extends Command{

    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;
    //Declares how many rows of data will be added to the table at once
    public int $batchSize;
    //Used to count the number of rows added. This is needed for the batched upload of data, but can also be used for other purposes like counting the number of rows uploaded
    public int $count;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger){
        parent::__construct();
        $this->logger=$logger;
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

        $repository= $this->entityManager->getRepository(Drink::class);
        
        //Use XMLReader to read the .xml file
        $xml = new XMLReader();
        $xml->open($data);

        //Iterate through everything until the first item tag is reached, indicating our first input
        while($xml->read() && $xml->name != 'item');
        
        while($xml->name == 'item') {
            $row=new SimpleXMLElement($xml->readOuterXml());
            $id=(int)$row->entity_id;
            //If the ID already exists in the database, it is skipped
            if($repository->find($id)){
                $this->logger->warning('There is already a row in the database with the id ' . $id);
                //Move pointer to the next item and continue with the while loop
                $xml->next('item');
	            unset($row);
                continue;
            } 
            //Create a new Drink entity and insert our data into it
            try {
                $drink = new Drink();
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
                $this->count++;
            } catch(Exception $e){
                $this->logger->error('Failed to input row with the id ' . $id . $e);
                continue;
            }
            //Seperate function to add our drink to the DB
            if ($this->count % $this->batchSize == 0)
            {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
            //Move to the next item
            $xml->next('item');
	        unset($row);
        }
        //add the remaining rows
        $this->entityManager->flush();  
    }

    public function rowDataIncorrect(SimpleXMLElement $row) : bool{
        return !(is_numeric($row->entity_id) && is_numeric($row->Rating) && is_numeric($row->Count) && is_numeric($row->isKCup) && is_numeric($row->Facebook) && is_numeric($row->Price));
    }
}