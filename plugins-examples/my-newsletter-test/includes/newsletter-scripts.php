<?php

function ns_add_scripts() {
    wp_enqueue_style( 'ns-main-style', plugins_url().'/my-newsletter-test/css/style.css' );
    wp_enqueue_script( 'ns-main-script', plugins_url().'/my-newsletter-test/js/main.js', array('jquery') );
}

add_action('wp_enqueue_scripts', 'ns_add_scripts' );