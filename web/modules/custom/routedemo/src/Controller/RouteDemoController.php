<?php declare(strict_types = 1);

namespace Drupal\routedemo\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for SimpleRouteController routes.
 */
final class RouteDemoController extends ControllerBase {

  /**
   * Returns a render-able array for a test page.
   */
  public function content() {

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
}
