<?php
/*
 * Plugin Name: My Youtube
 * Plugin URI: http://www.example.com
 * Description: Plugin developed to show button.
 * Version: 1.0
 * Author: Cláudio Hilário
 * Author URI: http://www.example.com
 * Text Domain: my-youtube
 * License: MIT
 */
class My_Youtube {
  private static $instance;

  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {
      add_shortcode( 'btn-youtube', array($this, 'youtube'));
   
  }

  public function youtube($atts) {
      $a = shortcode_atts( array('chanel' => ''), $atts);
      $chanel = $a['chanel'];

      return '
        <script src="https://apis.google.com/js/platform.js"></script>
        <div class="g-ytsubscribe" data-channel="'.$chanel.'" data-layout="default" data-count="default"></div>';
  }

}

My_Youtube::getInstance();