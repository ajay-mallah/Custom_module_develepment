<?php declare(strict_types = 1);

namespace Drupal\routedemo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Messenger\Messenger;

/**
 * Returns responses for SimpleRouteController routes.
 */
final class RouteDemoController extends ControllerBase {

  /**
   * Returns a render-able array for a test page.
   */
  public function content() {

    // checking if current user is allowed to view page.
    $this->accessCheck();

    // Do something with your variables here.
    $myText = 'This is not just a default text!';
    $myNumber = 1;
    $myArray = [1, 2, 3];

    return [
      // Your theme hook name.
      '#theme' => 'routedemo_theme_hook',
      // Your variables.
      '#variable1' => $myText,
      '#variable2' => $myNumber,
      '#variable3' => $myArray,
    ];
  }

  /**
   * Checks whether current user has access to the page or not.
   */
  public function accessCheck() {
    $action = \Drupal::currentUser()->hasPermission('access the custom page') ? 'allowed' : 'denied';
    \Drupal::messenger()->addMessage(t("Your access for the current page has been @action", ['@action' => $action]));
  }
}
