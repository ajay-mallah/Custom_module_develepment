<?php declare(strict_types = 1);

namespace Drupal\custom_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'custom_color_field' field type.
 *
 * @FieldType(
 *   id = "custom_color_field",
 *   label = @Translation("Custom color field"),
 *   category = @Translation("General"),
 *   default_widget = "custom_field_rgb",
 *   default_formatter = "custom_field_text",
 * )
 */
final class ColorFieldType extends FieldItemBase {
  
  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $length = $field_definition->getSetting('length') ?? 255;
    return [
      'columns' => [
        'color' => [
          'type' => 'varchar',
          'length' => $length,
          'not null' => FALSE,
        ]
      ]
    ]; 
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [
      'length' => 255,
    ] + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $element = [];
    $element['length'] = [
      '#type' => 'number',
      '#title' => $this->t('Length of the text field'),
      '#required' => TRUE,
      '#default_value' => $this->getSetting('length'),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return [
      'field_info' => 'field information',
    ] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    return [
      'field_info' => [
        '#type' => 'textfield',
        '#title' => $this->t('Description for the color field'),
        '#required' => TRUE,
        '#default_value' => $this->getSetting('field_info'),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    return [
      'color' => DataDefinition::create('string')
        ->setLabel(t('Color'),)
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraint_manager = \Drupal::TypedDataManager()->getValidationConstraintManager();
    $constraints = parent::getConstraints();

    $constraints[] = $constraint_manager->create('ComplexData', [
      'color' => [
        'Regex' => [
          'pattern' => '/^#?(([0-9a-fA-F]{2}){3}|([0-9a-fA-F]){3})$/i',
        ],
      ],
    ]);
    
    return $constraints;
  }
}
