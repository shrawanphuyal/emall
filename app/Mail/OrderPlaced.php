<?php

namespace App\Mail;

use App\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPlaced extends Mailable
{
	use Queueable, SerializesModels;
	public $order;
	public $products;
	public $total;
	public $type;
	public $conversionRate;

	/**
	 * Create a new message instance.
	 *
	 * @param $order
	 * @param $products
	 * @param $total
	 * @param $type
	 */
	public function __construct($order, $products, $total, $type)
	{
		$this->order    = $order;
		$this->products = $products;
		$this->total    = $total;
		$this->type     = $type;
		$this->conversionRate = (float) Company::firstOrFail()->conversion_rate;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->subject('Your Order')->markdown('emails.order_placed');
	}
}
