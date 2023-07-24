<?php

namespace Drupal\custom_menu\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures budget amount for the recent movies.
 */
class BudgetMovieForm extends ConfigFormBase 
{
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'budget_movie_amount_setting';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return [
      'budget_movie_amount.settings'
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('budget_movie_amount.settings');

    $form['form']['budget_amount'] = [
      '#type' => 'number',
      '#placeholder' => $this->t('Budget Amount'),
      '#default_value' => $config->get('budget_amount') ?? 0,
      '#min' => 0,
    ];

    $form['form']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $budget_amount = $form_state->getValue('budget_amount');
    // Storing values into configuration file.
    $config = $this->config('budget_movie_amount.settings');
    $config->set('budget_amount', $budget_amount);
    $config->save();
    // Calling parent submit method.
    parent::submitForm($form, $form_state);
  }
}