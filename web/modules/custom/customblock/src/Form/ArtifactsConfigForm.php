<?php

namespace Drupal\customblock\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ArtifactsConfigForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'customblock_artifact_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['customblock_artifact_config.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('customblock_artifact_config.settings');
    $artifacts_values = $form_state->get('artifacts_values');
    
    $form['artifacts'] = array(
      '#type' => 'table',
      '#title' => 'Sample Table',
      '#header' => array('Group', '1st label','1st value', '2st label', '2st value', 'Action'),
      '#prefix' => '<div id="artifacts">',
      '#suffix' => '</div>',
    );
    // Assigning form fields value.
    if ($config->get('artifacts') && empty($artifacts_values)) {
      $form_state->set('artifacts_values', $config->get('artifacts'));
      $artifacts_values = $form_state->get('artifacts_values');
    }
    else if (empty($artifacts_values)) {
      $temp_row[] = $this->getRowValues();
      $form_state->set('artifacts_values', $temp_row);
      $artifacts_values = $form_state->get('artifacts_values');
    }
    
    $form['actions']['#type'] = 'actions';

    // Inserting field groups.
    foreach ($artifacts_values as $i => $row) {
      $form['artifacts'][$i] = [
        'group' => [
          '#type' => 'textfield',
          '#placeholder' => $this->t('group'),
          '#default_value' => $row['group'] ?? '',
        ],
        'label_1st' => [
          '#type' => 'textfield',
          '#placeholder' => $this->t('label name'),
          '#default_value' => $row['label_1st'] ?? '',
        ],
        'value_1st' => [
          '#type' => 'number',
          '#placeholder' => $this->t('value'),
          '#default_value' => $row['value_1st'] ?? '',
        ],
        'label_2nd' => [
          '#type' => 'textfield',
          '#placeholder' => $this->t('label name'),
          '#default_value' => $row['label_2nd'] ?? '',
        ],
        'value_2nd' => [
          '#type' => 'number',
          '#placeholder' => $this->t('value'),
          '#default_value' => $row['value_2nd'] ?? '',
        ],
      ];
        $form['artifacts'][$i]['remove'] = [
          '#name' => $i,
          '#type' => 'submit',
          '#value' => $this->t('Remove'),
          '#submit' => array('::remove'),
          '#ajax' => [
              'callback' => '::addMoreCallback',
              'wrapper' => 'artifacts',
          ]
      ];
    }

    $form['actions']['add_more'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add more'),
      '#submit' => array('::addOne'),
      '#ajax' => [
        'callback' => '::addMoreCallback',
        'wrapper' => 'artifacts',
      ]
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('submit'),
    ];
    
    return $form;
  }

  /**
   * Adds a new row to the table.
   *  @return void
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $artifacts_values = $form_state->get('artifacts_values');
    $artifacts_values[] = $this->getRowValues();
    $form_state->set('artifacts_values', $artifacts_values);
    $form_state->setRebuild();
  }

  /**
   * Removes a row of fields from the form table.
   *  @return void
   */
  public function remove(array &$form, FormStateInterface $form_state) {
    // Selecting triggered element.
    $element = $form_state->getTriggeringElement();
    $index = $element['#name'];
    // Collecting artifacts field values.
    $artifacts_values = $form_state->get('artifacts_values');
    unset($artifacts_values[$index]);
    $form_state->set('artifacts_values', $artifacts_values);
    $form_state->setRebuild();
  }

  /**
   * Handles add more ajax callback.
   */
  public function addMoreCallback(array &$form, FormStateInterface $form_state):array {
    return $form['artifacts'];
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $artifacts = $form_state->getValues()['artifacts'];
    foreach ($artifacts as $artifact) {
      unset($artifact['remove']);
      foreach ($artifact as $value) {
        if (empty($value)) {
          $form_state->setErrorByName('artifacts', 
            $this->t('Please fill all the fields')
          );
        }
      }
    }

    return;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('customblock_artifact_config.settings');
    $values = $form_state->getValues()['artifacts'];
    // Filtering data.
    $data = [];
    foreach ($values as $value) {
     unset($value['remove']);
     array_push($data, $value);
    }
    // Saving data into the config file.
    $config->set('artifacts', $data);
    $config->save();

    $this->redirectResult();
  }

  /**
   * Returns key and value for the field group.
   * 
   *  @return array
   */
  public function getRowValues() {
    return [
      'groups' => '',
      'label_1st' => '',
      'value_1st' => '',
      'label_2nd' => '',
      'value_2nd' => '',
    ];
  }

  /**
   * Redirects to the result page.
   *  @return void
   */
  public function redirectResult() {
    // On successfully form submit redirecting to result page.
    \Drupal::service('customblock.user_redirect')->setLoginRedirection('/artifacts-result');
  }
}