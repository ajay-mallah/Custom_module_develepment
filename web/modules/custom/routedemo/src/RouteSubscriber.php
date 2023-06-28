<?php
namespace Drupal\routedemo;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * @class RouteSubscriber
 * @package Drupal\Routedemo
 */
class RouteSubscriber extends RouteSubscriberBase {
  /**
   * @param RouteCollection $collection
   */
  public function alterRoutes(RouteCollection $collection) {
    // Disable editor role access.
    if ($route = $collection->get('routedemo.example')) {
      $route->setRequirement('_role', 'administrator');
    }
  }


}