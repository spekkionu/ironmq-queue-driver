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
        $message_id = 'messageid';
        $env = m::mock('PMG\Queue\Envelope');
        $receiptHandle = null;
        $envelope = new IronEnvelope($message_id, $env, $receiptHandle);

        $this->assertInstanceOf('Spekkionu\PMG\Queue\Iron\Envelope\IronEnvelope', $envelope);
    }

    public function testUnwrap()
    {
        $message_id = 'messageid';
        $env = m::mock('PMG\Queue\Envelope');
        $env->shouldReceive('unwrap')->once()->andReturn('unwrapped');
        $receiptHandle = null;
        $envelope = new IronEnvelope($message_id, $env, $receiptHandle);

        $this->assertEquals('unwrapped', $envelope->unwrap());
    }

    public function testAttempts()
    {
        $message_id = 'messageid';
        $env = m::mock('PMG\Queue\Envelope');
        $env->shouldReceive('attempts')->once()->andReturn('attempted');
        $receiptHandle = null;
        $envelope = new IronEnvelope($message_id, $env, $receiptHandle);

        $this->assertEquals('attempted', $envelope->attempts());
    }

    public function testRetry()
    {
        $message_id = 'messageid';
        $env = m::mock('PMG\Queue\Envelope');
        $env->shouldReceive('retry')->once()->andReturn('retried');
        $receiptHandle = null;
        $envelope = new IronEnvelope($message_id, $env, $receiptHandle);

        $this->assertEquals('retried', $envelope->retry());
    }

    public function testGetMessageId()
    {
        $message_id = 'messageid';
        $env = m::mock('PMG\Queue\Envelope');
        $receiptHandle = null;
        $envelope = new IronEnvelope($message_id, $env, $receiptHandle);

        $this->assertEquals($message_id, $envelope->getMessageId());
    }
}
