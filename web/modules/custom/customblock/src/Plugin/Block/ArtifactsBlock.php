<?php

namespace Drupal\customblock\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactory;

/**
 * Provides a field group block.
 * 
 * @Block(
 *  id = "customblock_artifacts",
 *  admin_label = @Translation("artifacts block"),
 *  category = @Translation("Custom")
 * )
 */
final class ArtifactsBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    // Collecting data from the config factory.
    $config = \Drupal::configFactory()->getEditable('customblock_artifact_config.settings');
    $artifacts = $config->get('artifacts');
    // dd($artifacts);
    return [
      '#theme' => 'artifacts_theme',
      '#artifacts' => $artifacts,
      '#cache' => [
        'tags' => $config->getCacheTags(),
      ]
    ];
  }
}