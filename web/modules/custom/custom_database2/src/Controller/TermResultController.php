<?php

namespace Drupal\custom_database2\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Exception;

/**
 * Handles request for term data.
 */
class TermResultController extends ControllerBase {
  
  /**
   * Returns render array for term data.
   */
  public static function getResult(string $term_id) {
    $result = self::getTermData($term_id);
    return [
      '#theme' => 'term_result',
      '#id' => $result['tid'],
      '#uuid' => $result['uuid'],
      '#nodes' => $result['nodes'],
    ];
  }

  /**
   * Fetches data from Taxonomy tables.
   * 
   * @param string $term_id
   *   Term ID of the taxonomy term.
   */
  private static function getTermData(string $term_id) {
    // Fetching term id, name and uuid from the database.
    $db = \Drupal::database();
    $result = $db->select('taxonomy_term_data', 'td')
    ->condition('td.tid', $term_id)
    ->fields('td', ['uuid', 'tid']);
    $result->join('taxonomy_term_field_data', 'tfd', 'td.tid = tfd.tid');
    $result = $result->fields('tfd', ['name'])
    ->execute()
    ->fetchAll();
    // storing fetched value into an associative array.
    $fetched_result = [];
    foreach ($result[0] as $key => $value) {
      $fetched_result[$key] = $value;
    }

    // Fetching nodes having the term_id as $term_id.
    $query = \Drupal::database()->select('taxonomy_index', 'ti')
    ->condition('ti.tid', $term_id);
    $query->fields('ti', ['nid']);
    $nodes = $query->execute();

    // Fetching node url and title.
    $nodes_title_url = [];
    if($nodeIds = $nodes->fetchCol()){
      $nodes = Node::loadMultiple($nodeIds);
      $aliasManager = \Drupal::service('path_alias.manager');
      foreach($nodes as $node) {
        $raw_url = "/node/" . $node->id();
        $nodes_title_url[] = [
          'title' => $node->getTitle(),
          'url' => $aliasManager->getAliasByPath($raw_url, 'en'),
        ];
      }
      $fetched_result['nodes'] = $nodes_title_url;
    }
    
    return $fetched_result;
  }
}