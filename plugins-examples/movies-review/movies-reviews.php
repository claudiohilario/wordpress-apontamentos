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

        /** Config */
        $config = array(
            'domain' => $this->text_domain,
            'default_path' => '',
            'parent_slug' => 'plugins.php',
            'capability' => 'update_plugins',
            'menu' => 'install-required-plugins',
            'has_notices' => true,
            'is_automatic' => false,
            'message' => '',
            'strings' => array(
                'page_title' => __('Instalar os plugins requiridos', $this->text_domain ),
                'menu_title' => __('Instalar plugins', $this->text_domain),
                'installing' => __('A instalar o plugin: %s', $this->text_domain),
                'oops' => __('Alguma coisa falhou com a API do plugin.', $this->text_domain),
                'notice_can_install_required' => _n_noop( 
                    'O plugin movies reviews para funcionar corretamente, depende da instalação do plugin: %1$s.', 
                    'O plugin movies reviews para funcionar corretamente, depende da instalação dos plugins: %1$s.'),
                'notice_can_install_recommended' => _n_noop( 
                    'O plugin movies reviews para funcionar corretamente, depende da instalação do plugin: %1$s.', 
                    'O plugin movies reviews para funcionar corretamente, depende da instalação dos plugins: %1$s.'),
                'notice_cannot_install' => _n_noop( 
                    'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed', 
                    'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed'),
                'notice_can_activate_required' => _n_noop( 
                    'The following required plugin is currently inactive: %1$s.', 
                    'The following required plugins are currently inactive: %1$s.'),
                'notice_can_activate_recommended' => _n_noop( 
                    'The following recommended plugin is currently inactive: %1$s.', 
                    'The following recommended plugins are currently inactive: %1$s.'),
                'notice_cannot_activate' => _n_noop( 
                    'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on gettiong the plugin activated.', 
                    'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on gettiong the plugins activated.'),
                'notice_ask_to_update' => _n_noop( 
                    'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 
                    'The following plugins needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.'),
                'notice_cannot_update' => _n_noop( 
                    'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on gettiong the plugin activated.', 
                    'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on gettiong the plugin activated.'),
                'install_link' => _n_noop('Iniciar instalação do plugin', 'Iniciar instalação dos plugins'),
                'activate_link' => _n_noop('Ativar o plugin instalado', 'Ativar os plugins instalados'),
                'return' => __('Retornar ao plugin instalado', $this->text_domain),
                'plugin_activated' => __('Plugin ativado com sucesso', $this->text_domain),
                'complete' => __('All plugins installed and activated successfully. %s', $this->text_domain),
                'nag_type' => 'updated',
                ),
        );
        /** End Config */
        tgmpa($plugins, $config);
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