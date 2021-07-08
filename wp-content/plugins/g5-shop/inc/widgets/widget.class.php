<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Shop_Widget')) {
    class G5Shop_Widget {
        private static $_instance;
        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function init() {
            add_action('widgets_init', array($this,'register_widgets'));
        }

        public function register_widgets() {
            register_widget('G5Shop_Widget_Product_Sorting');
            register_widget('G5Shop_Widget_Price_Filter');
        }
    }
}