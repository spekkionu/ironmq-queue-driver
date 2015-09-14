<?php
namespace Spekkionu\PMG\Queue\Test;

use PHPUnit_Framework_TestCase;
use Spekkionu\PMG\Queue\Iron\Driver\IronDriver;
use \Mockery as m;

class IronDriverTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function testConstructor()
    {
        $client = m::mock('IronMQ\IronMQ');
        $serializer = m::mock('PMG\Queue\Serializer\Serializer');
        $queueUrls = [];
        $driver = new IronDriver($client, $serializer);

        $this->assertInstanceOf('Spekkionu\PMG\Queue\Iron\Driver\IronDriver', $driver);
    }

    public function testEnqueue()
    {
        $queueName = 'q';
        $messageId = 123;
        $messageBody = 'message body';
        $serializedMessageBody = json_encode($messageBody);
        $client = m::mock('IronMQ\IronMQ');
        $job = new \stdClass;
        $job->id = $messageId;
        $client->shouldReceive('postMessage')->with($queueName, $serializedMessageBody, [])->once()->andReturn($job);
        $serializer = m::mock('PMG\Queue\Serializer\Serializer');
        $serializer->shouldReceive('serialize')->once()->andReturn($serializedMessageBody);
        
        $driver = new IronDriver($client, $serializer);

        $message = m::mock('PMG\Queue\Message');

        $env = $driver->enqueue($queueName, $message);
        $this->assertInstanceOf('Spekkionu\PMG\Queue\Iron\Envelope\IronEnvelope', $env);
        $this->assertEquals($messageId, $env->getId());
    }

    public function testDequeue()
    {
        $queueName = 'q';
        $messageId = 123;
        $messageBody = 'message body';
        $serializedMessageBody = json_encode($messageBody);
        $job = new \stdClass;
        $job->id = $messageId;
        $job->reserved_count = 1;
        $job->body = $serializedMessageBody;
        $message = new \PMG\Queue\SimpleMessage('SimpleMessage', $messageBody);
        $wrapped = new \PMG\Queue\DefaultEnvelope($message, 1);
        $client = m::mock('IronMQ\IronMQ');
        $client->shouldReceive('getMessage')->with($queueName)->once()->andReturn($job);
        $serializer = m::mock('PMG\Queue\Serializer\Serializer');
        $serializer->shouldReceive('unserialize')->with($serializedMessageBody)->once()->andReturn($wrapped);
    
        
        $driver = new IronDriver($client, $serializer);
        $env = $driver->dequeue('q');
        $this->assertInstanceOf('Spekkionu\PMG\Queue\Iron\Envelope\IronEnvelope', $env);
        $this->assertEquals($messageId, $env->getId());
    }

    public function testAck()
    {
        $queueName = 'q';
        $messageId = 123;
        $serializer = m::mock('PMG\Queue\Serializer\Serializer');
        $client = m::mock('IronMQ\IronMQ');
        $client->shouldReceive('deleteMessage')->with($queueName, $messageId)->once();
        $env = m::mock('Spekkionu\PMG\Queue\Iron\Envelope\IronEnvelope');
        $env->shouldReceive('getId')->once()->andReturn($messageId);

        $driver = new IronDriver($client, $serializer);
        $driver->ack('q', $env);
    }

    public function testAckWithInvalidEnvelope()
    {
        $queueName = 'q';
        $messageId = 123;
        $serializer = m::mock('PMG\Queue\Serializer\Serializer');
        $client = m::mock('IronMQ\IronMQ');
        $client->shouldNotReceive('deleteMessage');
        $env = m::mock('PMG\Queue\Envelope');
        $env->shouldNotReceive('getId');

        $this->setExpectedException('PMG\Queue\Exception\InvalidEnvelope');
        $driver = new IronDriver($client, $serializer);
        $driver->ack('q', $env);
    }

    public function testRetry()
    {
        $queueName = 'q';
        $serializer = m::mock('PMG\Queue\Serializer\Serializer');
        $client = m::mock('IronMQ\IronMQ');
        $env = m::mock('Spekkionu\PMG\Queue\Iron\Envelope\IronEnvelope');
        $env->shouldReceive('retry')->once()->andReturn('retried');

        $driver = new IronDriver($client, $serializer);
        $this->assertEquals('retried', $driver->retry('q', $env));
    }

    public function testFail()
    {
        $queueName = 'q';
        $messageId = 123;
        $serializer = m::mock('PMG\Queue\Serializer\Serializer');
        $client = m::mock('IronMQ\IronMQ');
        $client->shouldReceive('deleteMessage')->with($queueName, $messageId)->once();
        $env = m::mock('Spekkionu\PMG\Queue\Iron\Envelope\IronEnvelope');
        $env->shouldReceive('getId')->once()->andReturn($messageId);

        $driver = new IronDriver($client, $serializer);
        $driver->fail('q', $env);
    }
}
