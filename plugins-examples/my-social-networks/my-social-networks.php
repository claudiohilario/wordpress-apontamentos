<?php
/*
 * Plugin Name: My Social Networks
 * Plugin URI: http://www.example.com
 * Description: Plugin developed to display my social networks
 * Version: 1.0
 * Author: Cláudio Hilário
 * Author URI: http://www.example.com
 * Text Domain: my-social-networks
 * License: MIT
 */
require_once(dirname(__FILE__).'/widget/my-widget.php');
class My_Social_Networks {
  private static $instance;

  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {
    add_action( 'widgets_init', array($this, 'register_widgets') );
   
  }

  public function register_widgets() {
    register_widget('My_Widget');
  }
}

My_Social_Networks::getInstance();