<?php

namespace Drupal\lbw_donation\Test;

use Drupal\Core\Controller\ControllerBase;

class ContentController extends ControllerBase
{
    /**
     * @return array
     */
    public function content()
    {
        return array(
            '#type' => 'markup',
            '#markup' => $this->t('Donate Test Complete'),
        );
    }
}
