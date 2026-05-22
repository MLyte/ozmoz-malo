<?php
get_header();
?>
<section class="section" style="padding-top:120px;">
    <div class="section__inner">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <p class="section-kicker">ØZMØZ / MALØ</p>
            <h2><?php the_title(); ?></h2>
            <div class="lead"><?php the_content(); ?></div>
        <?php endwhile; ?>
    </div>
</section>
<?php
get_footer();
