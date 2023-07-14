<?php declare(strict_types = 1);

namespace Drupal\custom_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'custom_field_color_picker' field widget.
 *
 * @FieldWidget(
 *   id = "custom_field_color_picker",
 *   label = @Translation("Color Picker"),
 *   field_types = {"custom_color_field"},
 * )
 */
final class ColorPickerWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state): array {
    $element['color'] = [
      '#title' => 'pick color',
      '#type' => 'color',
      '#default_value' => $items[$delta]->color ?? '#ffffff',
    ];
    return $element;
  }

}
