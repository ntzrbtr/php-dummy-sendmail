<?php

declare(strict_types=1);

use PDS\SendMailCommand;
use PDS\PDSApplication;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Output\OutputInterface;
use PHPUnit\Framework\TestCase;

/**
 * Description of SendMailCommandTest
 *
 * @author Zaahid Bateson <zbateson@gmail.com>
 */
class SendMailCommandTest extends TestCase
{
    protected $application;
    protected $command;

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct();
        $application = new PDSApplication;
        $application->add(new SendMailCommand());
        $this->command = $application->find('sendmail');
    }

    /**
     * Test normal execution (i.e. writing mails to a file).
     */
    public function testReadWrite(): void
    {
        $tester = new CommandTester($this->command);
        $tester->execute([
            '--directory' => dirname(__DIR__) . '/output',
            '--input-file' => '../data/email.txt'
        ], ['verbosity' => OutputInterface::VERBOSITY_DEBUG]);

        $this->assertRegExp('/\[Debug\] outFile=.*/', $tester->getDisplay());
        preg_match('/\[Debug\] outFile=(.*)/', $tester->getDisplay(), $matches);

        $file = $matches[1];
        $this->assertFileEquals(dirname(__DIR__) . '/output/' . $file, dirname(__DIR__) . '/data/email.txt');
    }

    /**
     * Test verbose execution (i.e. printing to the console)..
     */
    public function testPrint(): void
    {
        $tester = new CommandTester($this->command);

        // PHPUnit's expectOutputString doesn't apply cause we're not using echo it seems
        $tester->execute([
            '--directory' => dirname(__DIR__) . '/output',
            '--input-file' => '../data/email.txt',
            '--print' => true
        ], ['verbosity' => OutputInterface::VERBOSITY_DEBUG]);

        $this->assertContains('[Debug] printing to php://stdout', $tester->getDisplay());
        $this->assertContains('[Debug] outFile=php://stdout', $tester->getDisplay());
    }

    /**
     * Test file extension.
     */
    public function testFormat(): void
    {
        $tester = new CommandTester($this->command);
        $tester->execute([
            '--directory' => dirname(__DIR__) . '/output',
            '--input-file' => '../data/email.txt',
            '--timestamp' => 'YmdHisu',
            '--file-extension' => '.mime'
        ], ['verbosity' => OutputInterface::VERBOSITY_DEBUG]);

        $this->assertRegExp('/\[Debug\] outFile=\d{20}\.mime/', $tester->getDisplay());
        preg_match('/\[Debug\] outFile=(.*)/', $tester->getDisplay(), $matches);
    }

    /**
     * Test incrementing file names.
     */
    public function testIncrement(): void
    {
        $tester = new CommandTester($this->command);
        $randIncr = 'incr-test-' . mt_rand();
        $incrFile = dirname(__DIR__) . '/output/randIncr';

        $next = 1;
        if (file_exists($incrFile)) {
            $next = file_get_contents($incrFile);
        } else {
            $next = intval($next);
        }

        for ($i = 0; $i < 5; ++$i) {
            $tester->execute([
                '--directory' => dirname(__DIR__) . '/output',
                '--input-file' => '../data/email.txt',
                '--increment-file' => $randIncr
            ], ['verbosity' => OutputInterface::VERBOSITY_DEBUG]);
            $this->assertContains("[Debug] outFile=$next.mime", $tester->getDisplay());
            $this->assertFileEquals(dirname(__DIR__) . "/output/$next.mime", dirname(__DIR__) . '/data/email.txt');
            ++$next;
        }
    }

    /**
     * Test with non-existent parameters.
     */
    public function testWithNonExistentParameters(): void
    {
        $tester = new CommandTester($this->command);
        $tester->execute([
            '--directory' => dirname(__DIR__) . '/output',
            '--input-file' => '../data/email.txt',
            '-f' => 'test@example.com'
        ], ['verbosity' => OutputInterface::VERBOSITY_DEBUG]);

        $this->assertRegExp('/\[Debug\] outFile=.*/', $tester->getDisplay());
        preg_match('/\[Debug\] outFile=(.*)/', $tester->getDisplay(), $matches);

        $file = $matches[1];
        $this->assertFileEquals(dirname(__DIR__) . '/output/' . $file, dirname(__DIR__) . '/data/email.txt');
    }
}
