<?php

/**
 * @file
 * Form for taking user information.
 */

namespace Drupal\customform\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class BasicConfigForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'basic_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return 
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $node = \Drupal::routeMatch()->getParameter('node');

    if (!(is_null($node))) {
      $nid = $node->id;
    }
    else {
      $nid = 0;
    }
 
    $form['full_name'] = [
      '#type' => 'text',
      '#title' => $this->t('Full Name'),
      '#size' => 100,
      '#description' => $this->t('Full name of the user'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => $nid,
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $submitted_name = $form_state->getValue('full_name');
    $this->messenger()->addMessage($this->t("Congrats @user Your Form submitted Successfully", ['@user' => $submitted_name]));
  }
}