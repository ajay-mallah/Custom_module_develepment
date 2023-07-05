<?php

/**
 * @file
 * Form for taking user information.
 */

namespace Drupal\customform\Form;

use Drupal\Core\Database\Database;
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
  protected function getEditableConfigNames() {
    return 'customform.admin_settings';
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
      '#pattern' => '[+][9][1]([0-9]+){10}',
      '#description' => $this->t('Enter country code followed by 10 digit phone number.'),
      '#required' => TRUE,
      '#maxlength' => 13,
    ];
    // Phone Number render element
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email Id'),
      '#size' => 100,
      '#pattern' => '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$',
      '#required' => TRUE,
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
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('submit'),
    ];
    // node id.
    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => $nid,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validating phone number
    $phone_number = $form_state->getValue('phone_number');
    if (! preg_match('/[+][9][1]([0-9]+){10}/', $phone_number)) {
      $form_state->setErrorByName('phone_number', $this->t('Add +91 followed by 10 digit number'));
    }

    // Validating email address
    $email_address = $form_state->getValue('email');
    $email_domain = explode('@', $email_address)[1];
    if (! (\Drupal::service('email.validator')->isValid($email_address))) {
      $form_state->setErrorByName('email', $this->t('It appers that %mail is not a valid email. Please try again.',
      ['%mail' => $email_address]));
    }
    elseif (explode('.', $email_domain)[1] != 'com') {
      $form_state->setErrorByName('email', $this->t('Only .com domains extension is allowed.'));
    }
    elseif (!$this->isPublicDomain(explode('.', $email_domain)[0])) {
      $form_state->setErrorByName('email', $this->t('%domain is not public domain.', ['%domain' => $email_domain]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $conn = Database::getConnection();

    $form_value = $form_state->getValues();
    $form_data['form_fullname'] = $form_value['full_name']; 
    $form_data['form_phone_number'] = $form_value['phone_number']; 
    $form_data['form_email'] = $form_value['email']; 
    $form_data['form_gender'] = $form['gender']['#options'][$form_value['gender']]; 

    $conn->insert('customform_config')->fields($form_data)->execute();

    $submitted_name = $form_state->getValue('full_name');
    $this->messenger()->addMessage($this->t("Congrats @user Your Form submitted Successfully", ['@user' => $submitted_name]));
  }

  /**
   * Checks for the public domain.
   * 
   *  @param $email_domain
   *    email address domain.
   * 
   *  @return bool
   *    returns true if email contain public domain.
   */
  private function isPublicDomain(string $email_domain) {
    $public_domains = [
      'yahoo',
      'gmail',
      'outlook',
      'aol',
      'proton',
    ];

    foreach($public_domains as $domain) {
      if ($domain == $email_domain) {
        return true;
      }
    }
    return false;
  }
}