<?php

namespace App\Service;

use App\Entity\Drink;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class DuplicateChecker
{
    public function __construct(private LoggerInterface $logger) {}
    
    public function duplicateID(int $id, EntityManager $entityManager){
        //Create a repository to check for duplicate IPs
        $repository= $entityManager->getRepository(Drink::class);

       if($repository->find($id)){
           $this->logger->warning('There is already a row in the database with the id ' . $id);
           return true;
       }
       else return false;
   }
}