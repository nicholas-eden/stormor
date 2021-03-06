<?php

require_once 'lib/custom-fields.php';

if (!class_exists('Timber')) {
  add_action('admin_notices', function () {
    echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
  });

  add_filter('template_include', function ($template) {
    return get_stylesheet_directory() . '/static/no-timber.html';
  });

  return;
}

Timber::$dirname = array('templates', 'views');

class StarterSite extends TimberSite
{

  function __construct()
  {
    add_theme_support('post-formats');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('html5', array('search-form', 'gallery', 'caption'));
    add_filter('timber_context', array($this, 'add_to_context'));
    add_filter('get_twig', array($this, 'add_to_twig'));
    add_action('init', array($this, 'register_post_types'));
    add_action('init', array($this, 'register_taxonomies'));

    add_image_size( 'banner', '940', '300', array( "center", "center") );

    wp_deregister_script('jquery');
    wp_register_script('jquery', "https://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js", false, null);
    wp_enqueue_script('jquery');
    parent::__construct();
  }

  function register_post_types()
  {
    //this is where you can register custom post types
  }

  function register_taxonomies()
  {
    //this is where you can register custom taxonomies
  }

  function add_to_context($context)
  {
    $context['main_menu'] = new \Timber\Menu('main-nav');
    $context['mini_menu'] = new \Timber\Menu('mini-nav');
    $context['site'] = $this;
    $context['is_front_page'] = is_front_page();
    $context['is_home'] = is_home();
    $context['options'] = get_field_objects('options');

    return $context;
  }

  function myfoo($text)
  {
    $text .= ' bar!';
    return $text;
  }

  function add_to_twig($twig)
  {
    /* this is where you can add your own functions to twig */
    $twig->addExtension(new Twig_Extension_StringLoader());
    $twig->addFilter('myfoo', new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));
    return $twig;
  }

}

new StarterSite();
