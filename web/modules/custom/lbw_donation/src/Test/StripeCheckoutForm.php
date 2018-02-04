<?php

namespace Drupal\lbw_donation\Test;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class StripeCheckoutForm.
 *
 * @package Drupal\lbw_donation\Test
 */
class StripeCheckoutForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'lbw_donation_test_checkout_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $form['description'] = [
            '#type' => 'item',
            '#markup' => $this->t('Stripe Example Form'),
        ];

        $form['first'] = [
            '#type' => 'textfield',
            '#title' => $this->t('First name'),
        ];
        $form['last'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Last name'),
        ];
        $form['stripe'] = [
            '#type' => 'stripe',
            '#title' => $this->t('Credit card'),
            // The selectors are gonna be looked within the enclosing form only
            "#stripe_selectors" => [
                'first_name' => ':input[name="first"]',
                'last_name' => ':input[name="last"]',
            ]
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
        /*
         * This would normally be replaced by code that actually does something
         */
        foreach ($form_state->getValues() as $key => $value) {
            drupal_set_message($key . ': ' . $value);
        }

        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
//        \Stripe\Stripe::setApiKey("sk_test_BQokikJOvBiI2HlWgH4olfQ2");

        // Charge the user's card:
//        $charge = \Stripe\Charge::create([
//            "amount" => 999,
//            "currency" => "usd",
//            "description" => "Example charge",
//            "source" => $token,
//        ]);
    }

}
