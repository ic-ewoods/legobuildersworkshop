<?php

namespace Drupal\lbw_donation\Test;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class StripeCheckoutForm.
 *
 * @package Drupal\lbw_donation\Test
 */
class TemplateForm extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'lbw_donation_test_checkout_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $template_path = drupal_get_path('module', 'lbw_donation') . "/templates/test/content.html.twig";
        $template = file_get_contents($template_path);

        $variables = $this->getSwipeCheckoutContext();

        $form['description'] = [
            '#type' => 'item',
            '#markup' => $this->t('Template Example Form'),
        ];

        $form['template'] = [
            '#type' => 'inline_template',
            '#template' => $template,
            '#context' => $variables,
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
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        /*
         * This would normally be replaced by code that actually does something
         */
        foreach ($form_state->getValues() as $key => $value) {
            drupal_set_message($key . ': ' . $value);
        }
    }

    /**
     * @return array
     */
    private function getSwipeCheckoutContext()
    {
        $variables = [
            'module' => 'lbw_donation',
        ];

        return $variables;
    }
}
