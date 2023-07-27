<?php

namespace Drupal\custom_menu\EventSubscriber;

use Drupal\Core\Config\ConfigFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Displays Response for the movie node based on budget friendly price 
 * comparison
 */
class CustomNodeViewEvent implements EventSubscriberInterface {
  
  /**
   * Config factory object.
   * 
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $config;

  /**
   * @param Drupal\Core\Config\ConfigFactory $config
   *   Config Factory object.
   */
  public function __construct(ConfigFactory $config) {
    $this->config = $config;
  }

  /**
   * {@inheritdoc}
   * 
   * Triggers view event.
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::VIEW][] = ['budgetCompare', 2];
    return $events;
  }

  /**
   * Compares budget price for the movie node and shows status.
   * 
   * @param Symfony\Component\HttpKernel\Event\ViewEvent $event
   *   ViewEvent object.
   */
  public function budgetCompare(ViewEvent $event) {
    $build = $event->getControllerResult();
    $request = $event->getRequest();
    $node = $request->attributes->get('node');

    // checking if entity is type of node and bundle type is movie or not.
    if ($node && $node->getType() == "movie") {
      // Price of the current movie
      $price = (float)$node->get('movie_price')->value;
      $node_response = $this->comparePrice($price);
      // Setting response for the render page.
      $build['my_additional_field'] = [
        '#type' => 'inline_template',
        '#template' => '<div class="py-2 text-primary">{{ response }}</div>',
        '#context' => [
          'response' => $node_response,
        ]  
      ];
      // Resetting controller result.
      $event->setControllerResult($build);
    }
  }

  /**
   * Checks price of the current movie with the budget Friendly movie price and
   * return text response.
   * 
   * @param float $price
   *   Price of the current movie.
   */
  function comparePrice(float $price): string {
    $this->config = $this->config->getEditable('budget_movie_amount.settings');
    $budget_price = (float)$this->config->get('budget_amount');

    if ($budget_price > $price) {
      return "The movie is under budget";
    }
    else if ($budget_price == $price) {
      return "The movie is within budget";
    }
    return "The movie is over budget.";
  }
} 