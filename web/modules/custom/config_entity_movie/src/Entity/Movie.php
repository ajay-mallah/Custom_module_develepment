<?php declare(strict_types = 1);

namespace Drupal\config_entity_movie\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\config_entity_movie\MovieInterface;

/**
 * Defines the movie entity type.
 *
 * @ConfigEntityType(
 *   id = "movie",
 *   label = @Translation("movie"),
 *   label_collection = @Translation("movies"),
 *   label_singular = @Translation("movie"),
 *   label_plural = @Translation("movies"),
 *   label_count = @PluralTranslation(
 *     singular = "@count movie",
 *     plural = "@count movies",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\config_entity_movie\MovieListBuilder",
 *     "form" = {
 *       "add" = "Drupal\config_entity_movie\Form\MovieForm",
 *       "edit" = "Drupal\config_entity_movie\Form\MovieForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *   },
 *   config_prefix = "movie",
 *   admin_permission = "administer movie",
 *   links = {
 *     "collection" = "/admin/structure/movie",
 *     "add-form" = "/admin/structure/movie/add",
 *     "edit-form" = "/admin/structure/movie/{movie}",
 *     "delete-form" = "/admin/structure/movie/{movie}/delete",
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *      "winning_year" = "winning_year",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "winning_year"
 *   },
 * )
 */
final class Movie extends ConfigEntityBase implements MovieInterface {

  /**
   * The movie ID.
   */
  protected string $id;

  /**
   * The  movie name.
   */
  protected string $label;

}
