<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="site-header__inner">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">ØZMØZ / MALØ</a>
        <nav class="main-nav" aria-label="<?php esc_attr_e('Navigation principale', 'ozmoz-malo'); ?>">
            <a href="<?php echo esc_url(home_url('/#listen')); ?>"><?php esc_html_e('Listen', 'ozmoz-malo'); ?></a>
            <a href="<?php echo esc_url(home_url('/#dates')); ?>"><?php esc_html_e('Dates', 'ozmoz-malo'); ?></a>
            <a href="<?php echo esc_url(home_url('/#press')); ?>"><?php esc_html_e('Press', 'ozmoz-malo'); ?></a>
            <a class="nav-booking" href="<?php echo esc_url(home_url('/#booking')); ?>"><?php esc_html_e('Booking', 'ozmoz-malo'); ?></a>
        </nav>
    </div>
</header>
<main id="main">
