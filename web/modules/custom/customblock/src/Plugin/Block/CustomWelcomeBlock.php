<?php declare(strict_types = 1);

namespace Drupal\customblock\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Provides a custom welcome block block.
 *
 * @Block(
 *   id = "customblock_custom_welcome_block",
 *   admin_label = @Translation("custom welcome block"),
 *   category = @Translation("Custom"),
 * )
 */
final class CustomWelcomeBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $user = \Drupal::currentUser()->getRoles();
    $user_role = count($user) > 1 ? $user[1] : $user[0];
    return [
      '#markup' => $this->t('Welcome @user', ['@user' => $user_role])
    ];
  }
}
