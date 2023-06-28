<?php

/**
 * @file
 * Hello Module's front controller.
 */

namespace Drupal\hellomodule\Controller;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Controller\ControllerBase;

class FrontController extends ControllerBase {
  /**
   * @method greetUser()
   *  Fetches current user and displays greet message.
   */
  public function greetUser() {

    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $user_name = $user->getAccountName();
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello @user', ['@user' => $user_name]),
      '#cache' => [
        'tags' => $user->getCacheTags(),
      ]
    ];
  }
}