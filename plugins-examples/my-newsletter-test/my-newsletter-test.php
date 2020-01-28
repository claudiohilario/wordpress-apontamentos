<?php
/*
 * Plugin Name: My Newsletter Test
 * Description: Plugin of the newsletter.
 * Version: 1.0
 * Author: Cláudio Hilário
 */

 if(!defined('ABSPATH')) {
     exit;
 }

 /**
  * Load Scripts
  */
 require_once(plugin_dir_path(__FILE__)."/includes/newsletter-scripts.php");

 /**
  * Load Class
  */
  require_once(plugin_dir_path(__FILE__)."/includes/newsletter-class.php");

  /**
   * Register newsletter
   */
  function register_newsletter() {
      register_widget('My_Newsletter_Widget');
  }

  add_action( 'widgets_init',  'register_newsletter');