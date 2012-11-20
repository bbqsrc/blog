<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php wp_title('|', true, 'right'); bloginfo('name'); // Add the blog name. ?></title>
    <!--[if lt IE 9]><script src="<?php echo get_template_directory_uri(); ?>/html5shiv.js"></script><![endif]-->
    <!--[if lt IE 8]><link href="<?php echo get_template_directory_uri(); ?>/shit-browsers.css" type="text/css" rel="stylesheet"><![endif]-->
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon.png">
    <link href="<?php echo get_template_directory_uri(); ?>/style.css" type="text/css" rel="stylesheet" media="screen">
    <link href='http://fonts.googleapis.com/css?family=Scada:400italic,700italic,400,700' rel='stylesheet' type='text/css'>
    <?php wp_head(); $options = get_option("brendan_options");
        if (array_key_exists('head', $options)) {
                echo $options['head'];
        }?>

  </head>

  <body<?php if (is_home()): ?> class='home'<?php endif; ?>>
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

