<?php
namespace Spekkionu\PMG\Queue\Test;

use PHPUnit_Framework_TestCase;
use Spekkionu\PMG\Queue\Iron\Envelope\IronEnvelope;
use \Mockery as m;

class IronEnvelopeTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function testConstructor()
    {
        $message_id = 123;
        $env = m::mock('PMG\Queue\Envelope');
        $envelope = new IronEnvelope($message_id, $env);

        $this->assertInstanceOf('Spekkionu\PMG\Queue\Iron\Envelope\IronEnvelope', $envelope);
    }

    public function testUnwrap()
    {
        $message_id = 123;
        $env = m::mock('PMG\Queue\Envelope');
        $env->shouldReceive('unwrap')->once()->andReturn('unwrapped');
        $envelope = new IronEnvelope($message_id, $env);

        $this->assertEquals('unwrapped', $envelope->unwrap());
    }

    public function testAttempts()
    {
        $message_id = 123;
        $env = m::mock('PMG\Queue\Envelope');
        $env->shouldReceive('attempts')->once()->andReturn('attempted');
        $envelope = new IronEnvelope($message_id, $env);

        $this->assertEquals('attempted', $envelope->attempts());
    }

    public function testRetry()
    {
        $message_id = 123;
        $env = m::mock('PMG\Queue\Envelope');
        $env->shouldReceive('retry')->once()->andReturn('retried');
        $envelope = new IronEnvelope($message_id, $env);

        $this->assertEquals('retried', $envelope->retry());
    }

    public function testGetId()
    {
        $message_id = 123;
        $env = m::mock('PMG\Queue\Envelope');
        $envelope = new IronEnvelope($message_id, $env);

        $this->assertEquals($message_id, $envelope->getId());
    }
}
