<?php

namespace Drupal\lbw_donation\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DonationForm.
 *
 * @package Drupal\lbw_donation\Form
 */
class DonationForm extends FormBase {


    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'lbw_donation_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $form['description'] = [
            '#type' => 'inline_template',
            '#template' => $this->getFieldTemplate('description'),
        ];

        $form['donation_amount'] = [
            '#type' => 'number',
            '#title' => 'Amount',
        ];

        $form['stripe_checkout'] = [
            '#type' => 'inline_template',
            '#template' => $this->getFieldTemplate('stripe-checkout'),
            '#context' => $this->getSwipeCheckoutContext(),
        ];

        $form['stripeToken'] = [
            '#type' => 'hidden'
        ];

        $form['stripeEmail'] = [
            '#type' => 'hidden'
        ];

        $form['stripeBillingName'] = [
            '#type' => 'hidden'
        ];

        $form['stripeBillingAddressLine1'] = [
            '#type' => 'hidden'
        ];

        $form['stripeBillingAddressCity'] = [
            '#type' => 'hidden'
        ];

        $form['stripeBillingAddressState'] = [
            '#type' => 'hidden'
        ];

        $form['stripeBillingAddressZip'] = [
            '#type' => 'hidden'
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // Display result.
        foreach ($form_state->getValues() as $key => $value) {
            drupal_set_message($key . ': ' . $value);
        }

        $this->processCharge($form_state);

    }

    /**
     * @param $field
     *
     * @return bool|string
     */
    private function getFieldTemplate($field)
    {
        $templates_path = drupal_get_path('module', 'lbw_donation') . '/templates/';
        $template = file_get_contents($templates_path . "donation-form--{$field}.html.twig");

        return $template;
    }

    private function getSwipeCheckoutContext()
    {
        $variables = [
            'stripe_key' => $this->getStripeKey('public'),
            'site_name' => \Drupal::config('system.site')->get('name'),
            'description' => 'Donation',
        ];

        return $variables;
    }

    /**
     * @param $key
     * @return array|mixed|null
     */
    private function getStripeKey($key)
    {
        $stripe_config = \Drupal::config('stripe.settings');
        $stripe_environment = $stripe_config->get('environment');
        $stripe_public_key = $stripe_config->get("apikey.{$stripe_environment}.{$key}");

        return $stripe_public_key;
    }

    /**
     * @param FormStateInterface $form_state
     */
    private function processCharge(FormStateInterface $form_state)
    {
        \Stripe\Stripe::setApiKey($this->getStripeKey('secret'));

        $stripe_token = $form_state->getValue('stripeToken');
        $donation_amount = $form_state->getValue('donation_amount') * 100;

        $charge = \Stripe\Charge::create([
            'amount' => $donation_amount,
            'currency' => 'usd',
            'description' => 'Donation',
            'source' => $stripe_token,
        ]);
    }

}
