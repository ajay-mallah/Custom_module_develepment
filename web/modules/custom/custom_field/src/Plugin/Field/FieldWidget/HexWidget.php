<?php declare(strict_types = 1);

namespace Drupal\custom_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'custom_field_hex' field widget.
 *
 * @FieldWidget(
 *   id = "custom_field_hex",
 *   label = @Translation("HEX"),
 *   field_types = {"custom_color_field"},
 * )
 */
final class HexWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state): array {
    $element['color'] = [
      '#title' => 'color',
      '#type' => 'textfield',
      '#size' => 7,
      '#maxlength' => 7,
      '#placeholder' => 'hex',
      '#pattern' => '#([\da-fA-F]{2})([\da-fA-F]{2})([\da-fA-F]{2})',
      '#default_value' => $items[$delta]->color ?? '#ffffff',
    ];
    return $element;
  }

}
