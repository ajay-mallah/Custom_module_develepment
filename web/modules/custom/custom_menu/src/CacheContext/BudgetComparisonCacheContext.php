<?php

namespace Drupal\custom_menu\CacheContext;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;
use Drupal\Core\Config\ConfigFactory;

/**
 * Custom cache context for the movie budget form
 */
class BudgetComparisonCacheContext implements CacheContextInterface 
{

  /**
   * @var Drupal\Core\Config\ConfigFactory 
   *   Config Factory instance
   */
  protected $config;

  /**
   * @param Drupal\Core\Config\ConfigFactory $config
   */
  public function __construct(ConfigFactory $config) {
    $this->config = $config;
  }

  /**
   * {@inheritdoc}
   */
  public static function getLabel() {
    return "Movie budget comparison cache tag";
  }

  /**
   * {@inheritdoc}
   */
  public function getContext() {
    return $this->config->getEditable('budget_movie_amount.settings')->get('budget_amount');
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata() {
    return new CacheableMetadata();
  }
}