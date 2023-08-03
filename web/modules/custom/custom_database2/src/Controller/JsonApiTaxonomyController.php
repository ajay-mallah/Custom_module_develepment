<?php

namespace Drupal\custom_database2\Controller;

use Drupal;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\Element\EntityAutocomplete;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handles autocompletion form request.
 */
class JsonApiTaxonomyController extends ControllerBase
{
  public function handleAutocomplete(Request $request) {
    $results = [];
    // extracting query parameter.
    $input = $request->query->get('q');
    if (!$input) {
      return new JsonResponse($results);
    }

    // Fetching taxonomy term ids.
    $ids = \Drupal::entityQuery('taxonomy_term')
    ->accessCheck(TRUE)
    ->condition('name', $input, 'CONTAINS')
    ->execute();

    // fetching Term object.
    $terms = $ids ? \Drupal\taxonomy\Entity\Term::loadMultiple($ids) : [];

    foreach ($terms as $term) {
      $results[] = [
        'value' => EntityAutocomplete::getEntityLabels([$term]),
        'label' => $term->getName().' ('.$term->id().')',
      ];
    }

    return new JsonResponse($results);
  }
}