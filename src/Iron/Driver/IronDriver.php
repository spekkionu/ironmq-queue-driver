<?php
namespace Spekkionu\PMG\Queue\Iron\Driver;

use Spekkionu\PMG\Queue\Iron\Envelope\IronEnvelope;
use IronMQ\IronMQ;
use PMG\Queue\Driver\AbstractPersistanceDriver;
use PMG\Queue\Exception;
use PMG\Queue\Serializer\Serializer;
use PMG\Queue\DefaultEnvelope;
use PMG\Queue\Envelope;
use PMG\Queue\Message;
use PMG\Queue\Exception\InvalidEnvelope;

class IronDriver extends AbstractPersistanceDriver
{
    /**
     * @var IronMQ
     */
    private $iron;
    /**
     * @var array
     */
    private $options;

    /**
     * @param IronMQ $iron
     * @param Serializer $serializer
     * @param array $options Properties to pass with the message
     */
    public function __construct(IronMQ $iron, Serializer $serializer = null, array $options = [])
    {
        parent::__construct($serializer);
        $this->iron = $iron;
        $this->options = $options;
    }

    /**
     * @inheritDoc
     */
    public function enqueue($queueName, Message $message)
    {
        $env = new DefaultEnvelope($message);
        $data = $this->serialize($env);

        $job = $this->iron->postMessage($queueName, $data, $this->options);

        return new IronEnvelope($job->id, $env);

    }

    /**
     * @inheritDoc
     */
    public function dequeue($queueName)
    {
        $job = $this->iron->getMessage($queueName);

        if ($job) {
            $wrapped = $this->unserialize($job->body);
            $message = new DefaultEnvelope($wrapped->unwrap(), $job->reserved_count);
            $env = new IronEnvelope($job->id, $message);

            return $env;
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function ack($queueName, Envelope $envelope)
    {
        if (!$envelope instanceof IronEnvelope) {
            throw new InvalidEnvelope(sprintf(
                '%s requires that envelopes be instances of "%s", got "%s"',
                __CLASS__,
                IronEnvelope::class,
                get_class($envelope)
            ));
        }

        $this->iron->deleteMessage($queueName, $envelope->getId());
    }

    /**
     * @inheritDoc
     */
    public function retry($queueName, Envelope $envelope)
    {
        return $envelope->retry();
    }

    /**
     * @inheritDoc
     */
    public function fail($queueName, Envelope $envelope)
    {
        return $this->ack($queueName, $envelope);
    }
}
