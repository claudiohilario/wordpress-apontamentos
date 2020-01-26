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
require dirname(__FILE__).'/lib/class-tgm-plugin-activation.php';

class Movies_reviews {
     private static $instance;
     private $text_domain = 'movies-reviews';
     private $field_prefix = "mr_";
     public static $field_prefix_1 = 'mr_';

     public static function getInstance() {
         if(!self::$instance) {
             self::$instance = new self();
         }

         return self::$instance;
     }

     private function __construct() {
        add_action( 'init', 'Movies_reviews::register_post_type' );
        add_action('init', 'Movies_reviews::register_taxonomies');
        add_action('tgmpa_register', array($this, 'check_required_plugins'));
        add_filter( 'rwmb_meta_boxes', array($this, 'metabox_custom_fields'));

        /** TEMPLETE CUSTOM */
        add_action('template_include', array($this, 'add_cpt_template'));
        add_action('wp_enqueue_scripts', array($this, 'add_style_scripts'));
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

    public static function register_taxonomies() {
        register_taxonomy('movie_types', array('movies_reviews'), 
            array(
                'labels' => array(
                    'name' => __('Movies Types'),
                    'singular_name' => __('Movie Type'),   
                ),
                'public' => true,
                'hierarchical' => true,
                'rewrite' => array('slug' => 'movie-types'),

            )
        );
    }

    /**
     * Checks required plugins
     */
    function check_required_plugins() {
        $plugins = array(
            array(
                'name' => 'Meta Box',
                'slug' => 'meta-box',
                'required' => true,
                'force_activation' => false,
                'force_desactivation' => false,
            ),
        );

        $config  = array(
            'domain'           => $this->text_domain,
            'default_path'     => '',
            'parent_slug'      => 'plugins.php',
            'capability'       => 'update_plugins',
            'menu'             => 'install-required-plugins',
            'has_notices'      => true,
            'is_automatic'     => false,
            'message'          => '',
            'strings'          => array(
              'page_title'                      => __( 'Install Required Plugins', $this->text_domain ),
              'menu_title'                      => __( 'Install Plugins', $this->text_domain ),
              'installing'                      => __( 'Installing Plugin: %s', $this->text_domain ),
              'oops'                            => __( 'Something went wrong with the plugin API.', $this->text_domain ),
              'notice_can_install_required'     => _n_noop( 'The Movie Reviews plugin depends on the following plugin: %1$s.', 'The Movie Reviews plugin depends on the following plugins: %1$s.' ),
              'notice_can_install_recommended'  => _n_noop( 'The Movie Reviews plugin recommends the following plugin: %1$s.', 'The Movie Reviews plugin recommends the following plugins: %1$s.' ),
              'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ),
              'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ),
              'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ),
              'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ),
              'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ),
              'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ),
              'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
              'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
              'return'                          => __( 'Return to Required Plugins Installer', $this->text_domain ),
              'plugin_activated'                => __( 'Plugin activated successfully.', $this->text_domain ),
              'complete'                        => __( 'All plugins installed and activated successfully. %s', $this->text_domain ),
              'nag_type'                        => 'updated',
            )
          );
          tgmpa( $plugins, $config );
    }

    /**
     * META BOX (metabox.io)
     */
    public function metabox_custom_fields() {
        $metabox[] = array(
            'id' => 'date_movie',
            'title' => __('Additional Informations', $this->text_domain),
            'pages' => array('movies_reviews', 'post'),
            'context' => 'normal',
            'priority' => 'high',

            'fields' => array(
                array(
                    'name' => __('Release year', $this->text_domain),
                    'desc' => __('Year the film was released', $this->text_domain),
                    'id' => $this->field_prefix.'movie_year',
                    'type' => 'number',
                    'std' => date('Y'),
                    'min' => '1880',
                ),
                array(
                    'name' => __('Director', $this->text_domain),
                    'desc' => __('Who directed the film', $this->text_domain),
                    'id' => $this->field_prefix.'movie_director',
                    'type' => 'text',
                    'std' => '',
                ),
                array(
                    'name' => __('Site', $this->text_domain),
                    'desc' => __('The site of the movie', $this->text_domain),
                    'id' => $this->field_prefix.'movie_site',
                    'type' => 'url',
                    'std' => '',
                )
            )
        );

        $metabox[] = array(
            'id' => 'review_data',
            'title' => __('Movie Review', $this->text_domain),
            'pages' => array('movies_reviews'),
            'context' => 'side',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name' => __('Rating', $this->text_domain),
                    'desc' => __('Em uma escala de 1 - 10, sendo 10 a melhor nota', $this->text_domain),
                    'id' => $this->field_prefix.'review_rating',
                    'type' => 'select',
                    'options' => array(
                        '' => __('Avaliar', $this->text_domain),
                        1 => __('1 - Gostei um pouco', $this->text_domain),
                        2 => __('2 - Gostei mais ou menos', $this->text_domain),
                        3 => __('3 - Não recomendo', $this->text_domain),
                        4 => __('4 - Deu para assistir', $this->text_domain),
                        5 => __('5 - Filme decente', $this->text_domain),
                        6 => __('6 - Bom filme ', $this->text_domain),
                        7 => __('7 - Bom, recomendo', $this->text_domain),
                        8 => __('8 - O meu favorito', $this->text_domain),
                        9 => __('9 - Gostei muito, um dos melhores filmes', $this->text_domain),
                        10 => __('10 - O melhor filme, recomendo', $this->text_domain),
                    ),
                    'std' => '',
                )
            )
        );

        return $metabox;
    }

    function add_cpt_template($template) {
        if(is_singular('movies_reviews')) {

            if(file_exists(get_stylesheet_directory().'single-movie_review.php')) {
                return get_stylesheet_directory().'single-movie_review.php';
            }

            return plugin_dir_path( __FILE__ ).'single-movie_review.php';
        }

        return $template;
    }

    function add_style_scripts() {
        wp_enqueue_style( 'movie-review-style', plugin_dir_url(__FILE__).'movies-reviews.css');
    }

    public static function activate() {
        self::register_post_type();
        self::register_taxonomies();
        flush_rewrite_rules(); // https://developer.wordpress.org/reference/functions/flush_rewrite_rules/
    }
 }

 Movies_reviews::getInstance();

 register_deactivation_hook(__FILE__, 'flush_rewrite_rules');
 register_activation_hook(__FILE__, 'Movies_reviews::activate');