<?php
get_header();
?>
<section class="section" style="padding-top:120px;">
    <div class="section__inner">
        <p class="section-kicker"><?php esc_html_e('Archive', 'ozmoz-malo'); ?></p>
        <h2><?php echo esc_html(get_the_archive_title()); ?></h2>
        <div class="event-list">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    if (get_post_type() === 'ozmoz_event') {
                        ozmoz_render_event_card(get_the_ID());
                    } else {
                        ?>
                        <article class="track-card">
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <div class="muted"><?php the_excerpt(); ?></div>
                        </article>
                        <?php
                    }
                endwhile;
            else :
                ?>
                <div class="empty-state"><?php esc_html_e('Aucun contenu pour le moment.', 'ozmoz-malo'); ?></div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php
get_footer();
