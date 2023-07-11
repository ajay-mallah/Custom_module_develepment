<?php

namespace Drupal\customblock\Plugin\Block;

use Drupal\Core\Block\BlockBase;
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
    $artifacts = \Drupal::config('customblock_artifact_config.settings')->get('artifacts');
    return [
      '#theme' => 'artifacts_theme',
      '#artifacts' => $artifacts,
    ];
  }
}