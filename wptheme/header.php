<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
    <!--[if lt IE 9]><script src="<?php echo get_template_directory_uri(); ?>/html5shiv.js"></script><![endif]-->
    <script src="<?php echo get_template_directory_uri() ?>/retina.js"></script>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon.png">
    <?php wp_head(); $options = get_option("brendan_options");
        if (array_key_exists('head', $options)) {
                echo $options['head'];
        }?>

  </head>

  <body <?php body_class(); ?>>
    <div id='page'>
      <header id='blog-header'>
        <div id="blog-title">
          <h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
          <span><?php bloginfo('description'); ?></span>
        </div>

        <nav id='blog-nav'>
          <ul id="top-menu">
            <?php if (!dynamic_sidebar('top-menu')): endif; ?>
          </ul>
        </nav>
      </header>
    <?php $options = get_option("brendan_options");
        if (array_key_exists('header', $options)) {
                echo $options['header'];
        }?>

