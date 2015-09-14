<?php
namespace Spekkionu\PMG\Queue\Iron\Envelope;

use PMG\Queue\Envelope;

class IronEnvelope implements Envelope
{
   /**
     * @var int Job ID
     */
    private $id;

    /**
     * @var Envelope
     */
    private $wrapped;

    public function __construct($id, Envelope $wrapped)
    {
        $this->id = $id;
        $this->wrapped = $wrapped;
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

    public function getId()
    {
        return $this->id;
    }
}
