<?php

namespace Drupal\customblock;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @class UserRedirect
 */
class UserRedirect {
  /**
   * The currently active request object.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $currentRequest;

  /**
   * The current active user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a new Login And Logout Redirect Per Role service object.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current active user.
   */
  public function __construct(RequestStack $request_stack, AccountProxyInterface $current_user) {
    $this->currentRequest = $request_stack->getCurrentRequest();
    $this->currentUser = $current_user;
  }

  /**
   * Redirects logged in user to give url.
   * 
   * @param string $url
   *   Destination URL.
   * 
   * @return void
   */
  public function setLoginRedirection(string $url, string $allowed_role = NULL) {
    $current_user_roles = $this->currentUser->getRoles();
    // checking if page is allowed to particular user or not.
    if ($allowed_role == NULL || in_array($allowed_role, $current_user_roles)) {
      $this->currentRequest->query->set('destination', $url);
      return;
    }
  }

}