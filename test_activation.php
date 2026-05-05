<?php
// Mock WP functions to test plugin loading without fatal errors
define('WPINC', true);

function plugin_dir_path($file) { return dirname($file) . '/'; }
function plugin_dir_url($file) { return '/'; }
function register_activation_hook($file, $callback) {}
function register_deactivation_hook($file, $callback) {}
function add_filter() {}
function add_action() {}
function load_plugin_textdomain() {}
function get_option($key, $default = false) { return $default; }
function plugin_basename($file) { return $file; }

require 'brand-oasis/brand-oasis.php';
echo "Successfully loaded!\n";
