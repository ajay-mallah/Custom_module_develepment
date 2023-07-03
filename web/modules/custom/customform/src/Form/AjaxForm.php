<?php

/**
 * @file
 * Form for taking user information.
 */

namespace Drupal\customform\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\MessageCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class AjaxForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'basic_ajax_form';
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
    // Full Name render element
    $form['full_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Full Name'),
      '#size' => 100,
      '#description' => $this->t('Full name of the user'),
      '#required' => TRUE,
    ];
    // Phone Number render element
    $form['phone_number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone number'),
      '#size' => 13,
      '#description' => $this->t('Enter country code followed by 10 digit phone number.'),
      '#required' => TRUE,
      '#maxlength' => 13,
      '#suffix' => '<div id="error-phone-number" class="custom-error"></div>',
    ];
    // Phone Number render element
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email Id'),
      '#size' => 100,
      '#required' => TRUE,
      '#suffix' => '<div id="error-email" class="custom-error"></div>',
    ];
    // Radio button element for the gender
    $form['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gender'),
      '#default_value' => 0,
      '#options' => [0 => 'male', 1 => 'female', 2 => 'other'],
      '#required' => TRUE,
    ];
    // Submit button element.
    $form['actions'] = [
      '#type' => 'button',
      '#value' => $this->t('submit'),
      '#ajax' => [
        'callback' => '::ajaxValidation'
      ]
    ];
    // node id.
    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => $nid,
    ];
    return $form;
  }

  /**
   * Validates user input values and throws error.
   */
  public function ajaxValidation(array &$form, FormStateInterface $form_state) {
    // A boolean value which stores form validation state.
    $valid = TRUE;
    $response = new AjaxResponse();
    // Reseting error message.
    $response->addCommand(new HtmlCommand('.custom-error', ''));
    // Validating phone number
    $phone_number = $form_state->getValue('phone_number');
    if (!preg_match('/[+][9][1]([0-9]+){10}/', $phone_number)) {
      $response->addCommand(new HtmlCommand('#error-phone-number', 
        $this->t('Add +91 followed by 10 digit number')));
      $valid = FALSE;
    }
      
    // Validating email address
    $email_address = $form_state->getValue('email');
    if (!preg_match('/^[a-zA-z0-9._-]+@(gmail|outlook|yahoo).com$/', $email_address)) {
      $response->addCommand(new HtmlCommand('#error-email', $this->t('Enter email in RFC format, 
        only public domain (like Yahoo, Gmail, Outlook, etc.) and end with .com')));
      $valid = FALSE;
    }

    if ($valid) {
      $response->addCommand(new MessageCommand('Form submitted successfully', NULL));
    }
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Nothing to do, Form is handled by ajaxRequest.
  }

}