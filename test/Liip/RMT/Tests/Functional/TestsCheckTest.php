<?php

namespace Liip\RMT\Tests\Functional;

use Exception;
use Liip\RMT\Context;
use Liip\RMT\Prerequisite\TestsCheck;

class TestsCheckTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $informationCollector = $this->getMock('Liip\RMT\Information\InformationCollector');
        $informationCollector->method('getValueFor')->with(TestsCheck::SKIP_OPTION)->willReturn(false);

        $output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');
        $output->method('write');

        $context = Context::getInstance();
        $context->setService('information-collector', $informationCollector);
        $context->setService('output', $output);
    }

    /** @test */
    public function succeeds_when_command_finished_within_the_default_configured_timeout_of_60s()
    {
        $check = new TestsCheck(array('command' => 'echo OK'));
        $check->execute();
    }

    /** @test */
    public function succeeds_when_command_finished_within_configured_timeout()
    {
        $check = new TestsCheck(array('command' => 'echo OK', 'timeout' => 0.100));
        $check->execute();
    }

    /** @test */
    public function fails_when_the_command_exceeds_the_timeout()
    {
        $this->setExpectedExceptionRegExp('Exception', '~process.*time.*out~');

        $check = new TestsCheck(array('command' => 'sleep 1', 'timeout' => 0.100));
        $check->execute();
    }
}
