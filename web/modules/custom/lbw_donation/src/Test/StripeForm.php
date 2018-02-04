<?php

namespace Drupal\lbw_donation\Test;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class StripeForm extends FormBase {

    /**
     * Build the test form.
     *
     * @param array $form
     *   Default form array structure.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   Object containing current form state.
     *
     * @return array
     *   The render array defining the elements of the form.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $form['description'] = [
            '#type' => 'item',
            '#markup' => $this->t('Stripe Example Form'),
        ];

        // @todo This really should just be in a template
        $stripe_attributes = [
            'src' => 'https://checkout.stripe.com/checkout.js',
            'class' => 'stripe-button',
            'data-key' => 'pk_test_6pRNASCoBOKtIshFeQd4XMUh',
            'data-amount' => '999',
            'data-name' => 'Stripe.com',
            'data-description' => 'Example charge',
            'data-image' => 'https://stripe.com/img/documentation/checkout/marketplace.png',
            'data-locale' => 'auto',
            'data-zip-code' => 'true'
        ];

        $form['stripe'] = [
            '#type' => 'html_tag',
            '#tag' => 'script',
            '#attributes' => $stripe_attributes,
        ];

        $form['actions'] = [
            '#type' => 'actions',
        ];

        // Add a submit button that handles the submission of the form.
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => 'Submit',
        ];

        return $form;
    }

    /**
     * Getter method for Form ID.
     *
     * The form ID is used in implementations of hook_form_alter() to allow other
     * modules to alter the render array built by this form controller. It must be
     * unique site wide. It normally starts with the providing module's name.
     *
     * @return string
     *   The unique ID of the form defined by this class.
     */
    public function getFormId() {
        return 'lbw_donation_test_form';
    }

    /**
     * Implements a form submit handler.
     *
     * The submitForm method is the default method called for any submit elements.
     *
     * @param array $form
     *   The render array of the currently built form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   Object describing the current state of the form.
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        /*
         * This would normally be replaced by code that actually does something
         */
        $token = $_POST['stripeToken']; // $form_state->getValue('stripeToken');
        drupal_set_message($this->t('Your token is %token.', ['%token' => $token]));

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
