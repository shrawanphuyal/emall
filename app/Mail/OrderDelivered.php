<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderDelivered extends Mailable
{
    use Queueable, SerializesModels;
	public $order;

	/**
	 * Create a new message instance.
	 *
	 * @param $order
	 */
    public function __construct($order)
    {
	    $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.order_delivered');
    }
}
