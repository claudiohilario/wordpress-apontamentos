<?php
/*
 * Plugin Name: My Twitter
 * Plugin URI: http://www.example.com
 * Description: Plugin developed to register twitter button.
 * Version: 1.0
 * Author: Cláudio Hilário
 * Author URI: http://www.example.com
 * Text Domain: my-twitter
 * License: MIT
 */
class My_Twitter {
  private static $instance;

  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {
      add_action( 'admin_menu', array($this, 'set_custom_fields'));
      add_shortcode( 'twitter', array($this, 'twitter') );
  }

  public function set_custom_fields() {
    add_menu_page( 
        'Meu Twitter',
        'Meu Twitter',
        'manage_options',
        'meu_twitter',
        'My_Twitter::save_custom_fields',
        'dashicons-twitter',
        10
    );
  }

  public function save_custom_fields() {
    echo '<h3>'.__('Registo do twitter', 'my-twitter').'</h3>';
    echo "<form method='POST'>";
    
    $fields = array('twitter');

    foreach ($fields as $field):
        if(isset($_POST[$field])) {
            update_option( $field, $_POST[$field] );
        }

        $value = stripcslashes(get_option( $field ));
        $label = ucwords(strtolower($field));

        echo "
            <p>
                <label> $label </label> <br />
                <textarea cols='100' rows='10' name='$field'> $value </textarea>
            </p>
        ";
    endforeach;

    $btnName = (get_option('twitter') !== "") ? "Editar" : "Registar";
    echo "<input type='submit' value='".$btnName."'>";
      echo "</form>";
  }

  public function twitter($attr = null) {
    return stripslashes(get_option('twitter'));
  }
}

My_Twitter::getInstance();
