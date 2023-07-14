<?php

namespace Drupal\custom_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'text color' formatter.
 *
 * @FieldFormatter(
 *   id = "custom_field_bg_color",
 *   label = @Translation("Background Color"),
 *   field_types = {"custom_color_field"},
 * )
 */
final class TextColorFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array {
    $element = [];
    foreach ($items as $delta => $item) {
      $element[$delta] = [
        '#type' => 'inline_template',
        '#template' => '<div style="background-color: {{color}}">{{ color }}</div>',
        '#context' => [
          'color' => $item->color,
        ]
      ];
    }
    return $element;
  }

}
