<?php

namespace App\Custom\Payment;

class PaymentFactory {
	/**
	 * Generate respective payment type class from the given name
	 *
	 * @param String $name
	 *
	 * @return PaymentContract
	 * @throws \Exception
	 */
    public static function make($name) : PaymentContract {
        $name = ucfirst($name);

        $className =  "\\App\\Custom\\Payment\\{$name}";

        if(!class_exists($className)) {
            throw new \Exception("Class: {$className}, does not exist.");
        }

        return new $className;
    }
}