<?php declare(strict_types = 1);

namespace Drupal\custom_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'custom_field_rgb' field widget.
 *
 * @FieldWidget(
 *   id = "custom_field_rgb",
 *   label = @Translation("RGB"),
 *   field_types = {"custom_color_field"},
 * )
 */
final class RGBWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state): array {
    $element['color'] = [
      'r' => [
        '#type' => 'number',
        '#size' => 3,
        '#maxlength' => 3,
        '#min' => 0,
        '#max' => 255,
        '#placeholder' => 'R',
        // Fetching r value of rgb from $item variable.
        '#default_value' => !is_null($items[$delta]->color) ? base_convert(substr($items[$delta]->color, 1, 2), 16, 10) : NULL,
      ],
      'g' => [
        '#type' => 'number',
        '#size' => 3,
        '#maxlength' => 3,
        '#min' => 0,
        '#max' => 255,
        '#placeholder' => 'G',
        // Fetching g value of rgb from $item variable.
        '#default_value' => !is_null($items[$delta]->color) ? base_convert(substr($items[$delta]->color, 3, 2), 16, 10) : NULL,
      ],
      'b' => [
        '#type' => 'number',
        '#size' => 3,
        '#maxlength' => 3,
        '#min' => 0,
        '#max' => 255,
        '#placeholder' => 'B',
        // Fetching b value of rgb from $item variable.
        '#default_value' => !is_null($items[$delta]->color) ? base_convert(substr($items[$delta]->color, 5, 2), 16, 10) : NULL,
      ],
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    $massaged_values = $values;
    foreach ($values as $key => $value) {
      // Fetching rgb values.
      $r = (int)$value['color']['r'];
      $g = (int)$value['color']['g'];
      $b = (int)$value['color']['b'];
      // Converting rgb values into hex values.
      $color = sprintf("#%02x%02x%02x", $r, $g, $b);
      $massaged_values[$key]['color'] = $color;
    }

    return $massaged_values;
  }

}
