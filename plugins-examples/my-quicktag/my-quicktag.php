<?php
/*
 * Plugin Name: My Quicktag
 * Plugin URI: http://www.example.com
 * Description: Plugin developed to insert custom quicktag.
 * Version: 1.0
 * Author: Cláudio Hilário
 * Author URI: http://www.example.com
 * Text Domain: my-quicktag
 * License: MIT
 */
class My_Quicktag {
  private static $instance;

  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {
    add_action( 'admin_print_footer_scripts', array($this, 'my_quicktag'));
  }

  public function my_quicktag() {
      if(wp_script_is('quicktags')) {

    ?>

        <script type="text/javascript">
            //Function to get selected text
            function getSel() {
                var txtarea = document.getElementById("content");
                var start = txtarea.selectionStart;
                var finish = txtarea.selectionEnd;

                return txtarea.value.substring(start, finish);
            }

            QTags.addButton('btn_personalizado', 'Short Code Twitter', get_t);

            function get_t() {
                var selected_text = getSel(); 
                QTags.insertContent('[twitter]');
            }
        </script>

    <?php

      }
  }


}

My_Quicktag::getInstance();