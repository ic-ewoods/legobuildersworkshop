<?php

namespace Drupal\lbw_donation\Stripe;

use Drupal\lbw_donation\Form\DonationForm;

/**
 * Class ConfigState.
 *
 * An invariant to access Stripe configuration data in a performant way
 *
 * @package Drupal\lbw_donation\Stripe
 */
class StripeConfig
{
    private $config;

    public function __construct()
    {
        $this->config = \Drupal::config('stripe.settings');
    }

    /**
     * @param $key
     * @return array|mixed|null
     */
    public function getKey($key)
    {
        $stripe_environment = $this->getStripeEnvironment();
        $key = $this->config->get("apikey.{$stripe_environment}.{$key}");

        return $key;
    }

    /**
     * @return array|mixed|null
     */
    public function getStripeEnvironment()
    {
        return $this->config->get('environment');
    }
}
