<?php 
/**
 * Plugin Name: Movies Reviews
 * Plugin URI: http://www.pluginsite.com
 * Description: Plugin to post movies reviews
 * Version: 1.0
 * Author: Cláudio Hilário
 * Author URI: http://www.ontech.pt
 * Text Domain: movies-reviews
 * License: MIT
 */

 class Movies_reviews {
     private static $instance;

     public static function getInstance() {
         if(!self::$instance) {
             self::$instance = new self();
         }

         return self::$instance;
     }

     private function __construct() {
        add_action( 'init', 'Movies_reviews::register_post_type' );
    }

    public static function register_post_type() {
        register_post_type('movies_reviews', array(
            'labels' => array(
                'name' => 'Movies Reviews',
                'singular_name' => 'Movie Review',
            ),
            'description' => 'Post movie reviews',
            'supports' => array(
                'title',
                'editor',
                'excerpt',
                'author',
                'revisions',
                'thumbnail',
                'custom-fields'
            ),
            'public' => true,
            'menu_icon' => 'dashicons-format-video', // Icons Menu Full List: https://developer.wordpress.org/resource/dashicons/#format-video
            'menu_position' => 3,
        ));
    }

    public static function activate() {
        self::register_post_type();
        flush_rewrite_rules(); // https://developer.wordpress.org/reference/functions/flush_rewrite_rules/
    }
 }

 Movies_reviews::getInstance();

 register_deactivation_hook(__FILE__, 'flush_rewrite_rules');
 register_activation_hook(__FILE__, 'Movies_reviews::activate');