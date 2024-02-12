<?php

namespace App\Tests;

use PHPUnit\Framework\MockObject\RuntimeException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportCommandTest extends KernelTestCase
{
    
    public function testFileNotFound(): void
    {
        $kernel = self::bootKernel();
        $application = new Application(self::$kernel);
        $command = $application->find('app:import');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'file' => 'NonexistantFile.xml',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Unable to find a file', $output);
    }

    public function testWrongFileType(): void
    {
        $kernel = self::bootKernel();
        $application = new Application(self::$kernel);
        $command = $application->find('app:import');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'file' => '.env.test'
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('This format is currently not supported.', $output);
    }

   public function testDuplicateID(): void
    {
        $kernel = self::bootKernel();
        $application = new Application(self::$kernel);
        $command = $application->find('app:import');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'file' => 'duplicateIDTest.xml'
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('1 rows have been added.', $output);
    } 
}
