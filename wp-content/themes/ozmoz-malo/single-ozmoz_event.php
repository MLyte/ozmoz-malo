<?php
get_header();
?>
<section class="section" style="padding-top:120px;">
    <div class="section__inner">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <p class="section-kicker"><?php esc_html_e('Date', 'ozmoz-malo'); ?></p>
            <h2><?php the_title(); ?></h2>
            <?php ozmoz_render_event_card(get_the_ID()); ?>
            <div class="lead" style="margin-top:32px;"><?php the_content(); ?></div>
        <?php endwhile; ?>
    </div>
</section>
<?php
get_footer();
