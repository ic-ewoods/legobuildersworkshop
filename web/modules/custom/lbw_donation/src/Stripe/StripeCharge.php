<?php

namespace Drupal\lbw_donation\Stripe;

use Drupal\lbw_donation\Stripe\StripeConfig;

class StripeCharge
{
    private $stripe_config;
    private $error_message;

    /**
     * StripeCharge constructor.
     * @param StripeConfig $stripe_config
     */
    public function __construct(StripeConfig $stripe_config)
    {
        $this->stripe_config = $stripe_config;
        \Stripe\Stripe::setApiKey($this->stripe_config->getKey('secret'));
    }

    /**
     * @param $donation_amount
     * @param $stripe_token
     *
     * @return bool
     */
    public function create($donation_amount, $stripe_token)
    {
        try {
            \Stripe\Charge::create([
                'amount' => $donation_amount,
                'currency' => 'usd',
                'description' => 'Donation',
                'source' => $stripe_token,
            ]);

            return true;

        } catch (\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $err = $body['error'];

            $error_msg = $err['message'];

//            print('Status is:' . $e->getHttpStatus() . "\n");
//            print('Type is:' . $err['type'] . "\n");
//            print('Code is:' . $err['code'] . "\n");
//            // param is '' in this case
//            print('Param is:' . $err['param'] . "\n");
//            print('Message is:' . $err['message'] . "\n");

        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
        } catch (\Exception $e) {
            // Something else happened, completely unrelated to Stripe
        }

        // @todo Address above conditions instead of just a generic error message
        $this->error_message = @$error_msg ?: 'A configuration or connection issue prevented processing the transaction.';

        return false;
    }

    public function getErrorMessage()
    {
        return $this->error_message;
    }
}
