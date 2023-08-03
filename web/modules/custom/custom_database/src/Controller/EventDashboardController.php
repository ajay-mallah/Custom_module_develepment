<?php

namespace Drupal\custom_database\Controller;

use Drupal\Core\Controller\ControllerBase;
use Exception;

/**
 * Dashboard Controller, return render array for the dashboard page.
 */
class EventDashboardController extends ControllerBase {
  
  /**
   * Returns render array for for event dashboard.
   */
  public function getEventSummary() {
    $event_data = $this->getEventData();
    return [
      '#theme' => 'event_dashboard',
      '#event_type' => $event_data['event_type'],
      '#year' => $event_data['year'],
      '#quarter' => $event_data['quarter'],
    ];
  }

  /**
   * Returns an array containing event data.
   */
  public function getEventData() {
    // Fetching event data from database.
    try {
      $db = \Drupal::database();
      $results = $db->select('node__field_event_date', 'fd');
      $results->join('node__field_event_type', 'ft', 'fd.entity_id = ft.entity_id');
      $results->join('taxonomy_term_field_data', 't', 'ft.field_event_type_target_id = t.tid');
      $results->addExpression('YEAR(field_event_date_value)', 'year');
      $results->addExpression('QUARTER(field_event_date_value)', 'quarter');
      $results = $results
      ->fields('t', ['name'])
      ->execute()->fetchAll();
    }
    catch (Exception $e) {
      echo 'Error: ' . $e->getMessage();
    }

    $dashboard = [
      'event_type' => [],
      'year' => [],
      'quarter' => [],
    ];
    // mapping fetched data into an array.
    foreach($results as $result) {
      foreach($result as $key => $value) {
        $key = $key == 'name' ? 'event_type' : $key;
        if (!isset($dashboard[$key][$value])) {
          $dashboard[$key][$value]=1;
        }
        else {
          $dashboard[$key][$value]++;
        }
      }
    }
    return $dashboard;
  }
}