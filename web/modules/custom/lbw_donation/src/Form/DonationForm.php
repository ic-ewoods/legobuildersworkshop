<?php

namespace Drupal\lbw_donation\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\lbw_donation\Stripe\StripeCharge;
use Drupal\lbw_donation\Stripe\StripeConfig;

/**
 * Class DonationForm.
 *
 * @package Drupal\lbw_donation\Form
 */
class DonationForm extends FormBase
{
    /** @var StripeConfig */
    private $stripe_config;

    /** @var StripeCharge */
    private $stripe_charge;

    public function __construct()
    {
        // Note: FormBase does not have a constructor

        // For unit testing these should be injectable
        $this->stripe_config = new StripeConfig();
        $this->stripe_charge = new StripeCharge($this->stripe_config);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'lbw_donation_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['description'] = [
            '#type' => 'inline_template',
            '#template' => $this->getFieldTemplate('description'),
        ];

        $form['donation_amount'] = [
            '#type' => 'number',
            '#title' => $this->t('Amount'),
            '#default_value' => 10,
            '#step' => 'any'
        ];

        $form['stripe_checkout'] = [
            '#type' => 'inline_template',
            '#template' => $this->getFieldTemplate('stripe-checkout'),
            '#context' => $this->getStripeCheckoutContext(),
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
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        // @todo Log this instead of reporting to the front end
        if ($this->stripe_config->getStripeEnvironment() == 'test') {
            // Reporting during development workflow
            foreach ($form_state->getValues() as $key => $value) {
                drupal_set_message($key . ': ' . $value);
            }
        }

        if ($this->processCharge($form_state)) {
            drupal_set_message($this->t('Thank you for your donation of $@amount', ['@amount' => $form_state->getValue('donation_amount')]));
        } else {
            $error_msg = $this->getChargeError();
            drupal_set_message($this->t('There was an error processing your donation. Please contact us. <br> [@error]', ['@error' => $error_msg]), 'error');
        }

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

    /**
     * @return array
     */
    private function getStripeCheckoutContext()
    {
        $variables = [
            'stripe_key' => $this->stripe_config->getKey('public'),
            'site_name' => \Drupal::config('system.site')->get('name'),
            'description' => 'Donation',
        ];

        return $variables;
    }

    /**
     * @param FormStateInterface $form_state
     * @return bool
     */
    private function processCharge(FormStateInterface $form_state)
    {
        $stripe_token = $form_state->getValue('stripeToken');
        $donation_amount = $form_state->getValue('donation_amount') * 100;

        return $this->stripe_charge->create($donation_amount, $stripe_token);
    }

    /**
     * @return mixed
     */
    private function getChargeError()
    {
        return $this->stripe_charge->getErrorMessage();
    }

}
