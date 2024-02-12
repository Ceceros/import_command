<?php

namespace App\Tests\Repository;

use App\Entity\Drink;
use App\Service\DuplicateChecker;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Psr\Log\LoggerInterface;

class ProductRepositoryTest extends KernelTestCase
{
    private EntityManager $entityManager;
    private LoggerInterface $log;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->log = $this->createMock(LoggerInterface::class);
    }

    public function testImport(): void
    {

        $kernel = self::bootKernel();
        $application = new Application(self::$kernel);
        $command = $application->find('app:import');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'file' => 'testFile.xml'
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('5 rows have been added.', $output);


        $drink = $this->entityManager
            ->getRepository(Drink::class)
            ->findOneBy(['id' => 340]);

        $this->assertSame(41.6000, $drink->getPrice());

        $drink2 = $this->entityManager
            ->getRepository(Drink::class)
            ->findOneBy(['id' => 342]);

        $this->assertSame(5, $drink2->getRating());

        $check = new DuplicateChecker($this->log);
        $this->assertEquals(
            true,
            $check->duplicateID(342,$this->entityManager)
        );
               
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}