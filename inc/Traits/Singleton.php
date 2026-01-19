<?php
namespace LUNIVERS_THEME\Inc\Traits;

trait Singleton {
    protected static $instance = null;

    final public static function get_instance() {
        if ( null === static::$instance ) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    final private function __clone() {}
    final public function __wakeup() {}
}