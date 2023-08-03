<?php

namespace Drupal\custom_database2\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Entity\Element\EntityAutocomplete;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\custom_database2\Controller\TermResultController;

/**
 * Class MyAutocompleteForm
 * @package Drupal\mymodule\Form
 */
class MyAutocompleteForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'my_autocomplete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['article'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Autocomplete Articles'),
      '$default_value' => $form_state->getValue('article') ?? null,
      '#autocomplete_route_name' => 'custom_database2.autocomplete_taxonomy',
    ];
    $form['result'] = [
      '#type' => 'markup',
      '#prefix' => '<div id="term_result">',
      '#suffix' => '</div>'
    ];
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'button',
      '#value' => $this->t('Save'),
      '#ajax' => [
        'callback' => '::submitCallback',
        'wrapper' => 'term_result',
      ]
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $term_id = EntityAutocomplete::extractEntityIdFromAutocompleteInput($form_state->getValue('article'));
  }

  /**
   * Handles Ajax callback function for submit button.
   * 
   * @param array $form
   *   Form array.
   * 
   * @param Drupal\Core\Form\FormStateInterface $form_state
   */
  public function submitCallback(array &$form, FormStateInterface $form_state) {
    // Fetching term id from article input field.
    $term_id = EntityAutocomplete::extractEntityIdFromAutocompleteInput($form_state->getValue('article'));
    // Calling controller function to get render array.
    $parking = TermResultController::getResult((string)$term_id);

    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('#term_result', $parking));
    return $response;
  }

}