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

        $form['stripe-checkout'] = [
            '#type' => 'inline_template',
            '#template' => $this->getFieldTemplate('stripe-checkout'),
            '#context' => $this->getSwipeCheckoutContext(),
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

    }

    /**
     * @param $field
     */
    private function getFieldTemplate($field)
    {
        $templates_path = drupal_get_path('module', 'lbw_donation') . '/templates/';
        $template = file_get_contents($templates_path . "donation-form--{$field}.html.twig");

        return $template;
    }

    private function getSwipeCheckoutContext()
    {
        $stripe_config = \Drupal::config('stripe.settings');
        $stripe_environment = $stripe_config->get('environment');

        $variables = [
            'site_name' => \Drupal::config('system.site')->get('name'),
            'stripe_key' => $stripe_config->get("apikey.{$stripe_environment}.public"),
        ];

        return $variables;
    }

}
