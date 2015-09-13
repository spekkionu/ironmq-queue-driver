<?php
namespace Spekkionu\PMG\Queue\Iron\Envelope;

use PMG\Queue\Envelope;

class IronEnvelope implements Envelope
{
    /**
     * @var string Message ID
     */
    private $message_id;

    /**
     * @var Envelope
     */
    private $wrapped;
    
    /**
     * @var null
     */
    private $receiptHandle;

    /**
     * @param $message_id
     * @param Envelope $wrapped
     * @param null $receiptHandle
     */
    public function __construct($message_id, Envelope $wrapped, $receiptHandle = null)
    {
        $this->message_id = $message_id;
        $this->wrapped = $wrapped;
        $this->receiptHandle = $receiptHandle;
    }

    /**
     * {@inheritdoc}
     */
    public function unwrap()
    {
        return $this->wrapped->unwrap();
    }

    /**
     * {@inheritdoc}
     */
    public function attempts()
    {
        return $this->wrapped->attempts();
    }

    /**
     * {@inheritdoc}
     * Returns a clone of the wrapped envelope, not itself.
     */
    public function retry()
    {
        return $this->wrapped->retry();
    }

    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * @return null
     */
    public function getReceiptHandle()
    {
        return $this->receiptHandle;
    }
}
