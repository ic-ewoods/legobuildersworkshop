<?php

namespace Drupal\lbw_donation\Form;

use Drupal\Core\Controller\ControllerBase;

class DonationFormComplete extends ControllerBase
{
    /**
     * @return array
     */
    public function content()
    {
        return [
            '#type' => 'inline_template',
            '#title' => 'Thank you for your donation!',
            '#template' => $this->getPageTemplate(),
        ];
    }

    public function getPageTemplate()
    {
        $templates_path = drupal_get_path('module', 'lbw_donation') . '/templates/';
        $template = file_get_contents($templates_path . "donation-form--complete.html.twig");

        return $template;
    }
}
