<?php declare(strict_types = 1);

namespace Drupal\config_entity_movie;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of movies.
 */
final class MovieListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['label'] = $this->t('Movie Name');
    $header['id'] = $this->t('Machine name');
    $header['winning_year'] = $this->t('Winning Year');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /** @var \Drupal\config_entity_movie\MovieInterface $entity */
    $row['label'] = $entity->label();
    $row['id'] = $entity->id();
    $row['winning_year'] = $entity->get('winning_year') ?? '';
    return $row + parent::buildRow($entity);
  }

}
