<?php defined('BASEPATH') or exit('No direct script access allowed');
// Hooks helper
// ------------------------------------------------------------------------
// make add_action function
// add_action( string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1 )
// link https://developer.wordpress.org/reference/functions/add_action/
// ------------------------------------------------------------------------
if (!function_exists('add_action')) {
    function add_action($hook_name, $callback, $priority = 10, $accepted_args = 1)
    {
        hooks()->add_action($hook_name, $callback, $priority, $accepted_args);
    }
}
// ------------------------------------------------------------------------
// make has_action function
// has_action( string $hook_name, callable $callback )
// link https://developer.wordpress.org/reference/functions/has_action/
// ------------------------------------------------------------------------
if (!function_exists('has_action')) {
    function has_action($hook_name, $callback)
    {
        hooks()->has_action($hook_name, $callback);
    }
}
// ------------------------------------------------------------------------
// make do_action function
// do_action( string $hook_name, mixed $arg = '' )
// link https://developer.wordpress.org/reference/functions/do_action/
// ------------------------------------------------------------------------
if (!function_exists('do_action')) {
    function do_action($hook_name, $arg = '')
    {
        hooks()->do_action($hook_name, $arg);
    }
}
// ------------------------------------------------------------------------
// make do_action_ref_array function
// do_action_ref_array( string $hook_name, array $args )
// link https://developer.wordpress.org/reference/functions/do_action_ref_array/
// ------------------------------------------------------------------------
if (!function_exists('do_action_ref_array')) {
    function do_action_ref_array($hook_name, $args)
    {
        hooks()->do_action_ref_array($hook_name, $args);
    }
}
// ------------------------------------------------------------------------
// make remove_action function
// remove_action( string $hook_name, callable $callback, int $priority = 10 )
// link https://developer.wordpress.org/reference/functions/remove_action/
// ------------------------------------------------------------------------
if (!function_exists('remove_action')) {
    function remove_action($hook_name, $callback, $priority = 10)
    {
        hooks()->remove_action($hook_name, $callback, $priority);
    }
}
// ------------------------------------------------------------------------
// make remove_all_actions function
// remove_all_actions( string $hook_name )
// link https://developer.wordpress.org/reference/functions/remove_all_actions/
// ------------------------------------------------------------------------
if (!function_exists('remove_all_actions')) {
    function remove_all_actions($hook_name)
    {
        hooks()->remove_all_actions($hook_name);
    }
}
// ------------------------------------------------------------------------
// make current_action function
// current_action( string $hook_name )
// link https://developer.wordpress.org/reference/functions/current_action/
// ------------------------------------------------------------------------
if (!function_exists('current_action')) {
    function current_action($hook_name)
    {
        hooks()->current_action($hook_name);
    }
}
// ------------------------------------------------------------------------
// make doing_action function
// doing_action( string $hook_name )
// link https://developer.wordpress.org/reference/functions/doing_action/
// ------------------------------------------------------------------------
if (!function_exists('doing_action')) {
    function doing_action($hook_name)
    {
        hooks()->doing_action($hook_name);
    }
}
// ------------------------------------------------------------------------
// make did_action function
// did_action( string $hook_name )
// link https://developer.wordpress.org/reference/functions/did_action/
// ------------------------------------------------------------------------
if (!function_exists('did_action')) {
    function did_action($hook_name)
    {
        hooks()->did_action($hook_name);
    }
}
// ------------------------------------------------------------------------
// make add_filter function
// add_filter( string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1 )
// link https://developer.wordpress.org/reference/functions/add_filter/
// ------------------------------------------------------------------------
if (!function_exists('add_filter')) {
    function add_filter($hook_name, $callback, $priority = 10, $accepted_args = 1)
    {
        hooks()->add_filter($hook_name, $callback, $priority, $accepted_args);
    }
}
// ------------------------------------------------------------------------
// make has_filter function
// has_filter( string $hook_name, callable $callback )
// link https://developer.wordpress.org/reference/functions/has_filter/
// ------------------------------------------------------------------------
if (!function_exists('has_filter')) {
    function has_filter($hook_name, $callback)
    {
        hooks()->has_filter($hook_name, $callback);
    }
}
// ------------------------------------------------------------------------
// make remove_filter function
// remove_filter( string $hook_name, callable $callback, int $priority = 10 )
// link https://developer.wordpress.org/reference/functions/remove_filter/
// ------------------------------------------------------------------------
if (!function_exists('remove_filter')) {
    function remove_filter($hook_name, $callback, $priority = 10)
    {
        hooks()->remove_filter($hook_name, $callback, $priority);
    }
}
// ------------------------------------------------------------------------
// make remove_all_filters function
// remove_all_filters( string $hook_name )
// link https://developer.wordpress.org/reference/functions/remove_all_filters/
// ------------------------------------------------------------------------
if (!function_exists('remove_all_filters')) {
    function remove_all_filters($hook_name)
    {
        hooks()->remove_all_filters($hook_name);
    }
}
// ------------------------------------------------------------------------
// make current_filter function
// current_filter()
// link https://developer.wordpress.org/reference/functions/current_filter/
// ------------------------------------------------------------------------
if (!function_exists('current_filter')) {
    function current_filter()
    {
        hooks()->current_filter();
    }
}
// ------------------------------------------------------------------------
// make apply_filters function
// apply_filters( string $hook_name, mixed $value )
// link https://developer.wordpress.org/reference/functions/apply_filters/
// ------------------------------------------------------------------------

if (!function_exists('apply_filters')) {
    function apply_filters($hook_name, $value)
    {
        return hooks()->apply_filters($hook_name, $value);
    }
}


// ------------------------------------------------------------------------
// make apply_filters_ref_array function
// apply_filters_ref_array( string $hook_name, array $args )
// link https://developer.wordpress.org/reference/functions/apply_filters_ref_array/
// ------------------------------------------------------------------------
if (!function_exists('apply_filters_ref_array')) {
    function apply_filters_ref_array($hook_name, $args)
    {
        return hooks()->apply_filters_ref_array($hook_name, $args);
    }
}
// ------------------------------------------------------------------------
// make do_action function













