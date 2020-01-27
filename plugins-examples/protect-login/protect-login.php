<?php
/*
 * Plugin Name: Protect Login
 * Plugin URI: http://www.example.com
 * Description: Plugin developed to protect login.
 * Version: 1.0
 * Author: Cláudio Hilário
 * Author URI: http://www.example.com
 * Text Domain: protect-login
 * License: MIT
 */

if(!defined('ABSPATH')) exit;
class Protect_Login {
  private static $instance;

  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {
    add_action( 'login_form_login', array($this, 'pt_login') );
  }

  public function pt_login() {
      if($_SERVER['SCRIPT_NAME'] == '/wordpress/wp-login.php') {
          $min = Date('i');
          if(!isset($_GET['company'.$min])) {
              header('Location: http://localhost/wordpress');
          }
      }
  }
}

Protect_Login::getInstance();
