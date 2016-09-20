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

    /**
     * @var string|null
     */
    private $reservation_id;

    public function __construct($id, Envelope $wrapped, $reservation_id = null)
    {
        $this->id = $id;
        $this->wrapped = $wrapped;
        $this->reservation_id = $reservation_id;
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

    public function getReservationId()
    {
        return $this->reservation_id;
    }
}
