<?php
get_header();
$socials = ozmoz_socials();
?>

<section class="hero" id="top">
    <div class="hero__bg" aria-hidden="true">
        <img src="<?php echo ozmoz_asset('img/hero-b2b.webp'); ?>" alt="">
    </div>
    <div class="hero__inner">
        <h1 class="hero__wordmark" aria-label="ØZMØZ / MALØ">
            <span>ØZMØZ</span>
            <span class="hero__slash">/</span>
            <span>MALØ</span>
        </h1>
        <p class="eyebrow">Hard techno / Industrial techno</p>
        <p class="hero__statement">B2B only. Hard techno / industrial techno.</p>
        <div class="hero__meta">
            <p>Two producers and DJs joining forces for a B2B hard techno and industrial techno project built for high-pressure rooms.</p>
            <div class="button-row">
                <a class="button button--red" href="#dates">Dates</a>
                <a class="button" href="#listen">Listen</a>
                <a class="button" href="#booking">Booking</a>
            </div>
        </div>
    </div>
</section>

<section class="section section--split" id="signal">
    <div class="section__inner intro-grid">
        <div>
            <p class="section-kicker">Signal</p>
            <h2 class="signal-title">Raw industrial pressure.</h2>
            <p class="lead">ØZMØZ and MALØ are two distinct artists presenting one B2B project: massive kicks, metallic textures and a brutal sense of club pressure.</p>
        </div>
        <div>
            <ul class="proof-list">
                <li>A B2B-only proposal built from two equal artistic signatures.</li>
                <li>Hard techno and industrial techno selected for peak-time pressure.</li>
                <li>Productions, releases and support across the European hard techno circuit.</li>
            </ul>
        </div>
    </div>
</section>

<section class="section" id="artists">
    <div class="section__inner">
        <p class="section-kicker">Two artists</p>
        <h2>Two artists. One B2B pressure zone.</h2>
        <div class="artist-grid">
            <article class="artist-card">
                <img src="<?php echo ozmoz_asset('img/artist-ozmoz.webp'); ?>" alt="ØZMØZ portrait">
                <div class="artist-card__body">
                    <p class="artist-role">Producer / DJ</p>
                    <h3>ØZMØZ</h3>
                    <p>Hard techno and industrial techno artist with a raw, metallic signature, built around tension, precision and original productions.</p>
                </div>
            </article>
            <article class="artist-card">
                <img src="<?php echo ozmoz_asset('img/artist-malo.webp'); ?>" alt="MALØ portrait">
                <div class="artist-card__body">
                    <p class="artist-role">Producer / DJ</p>
                    <h3>MALØ</h3>
                    <p>Producer and DJ with his own hard techno and industrial techno identity, bringing direct club impact, speed and contrast to the shared project.</p>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="section" id="listen">
    <div class="section__inner">
        <p class="section-kicker">Music</p>
        <h2>Tracks tested under pressure.</h2>
        <div class="track-grid">
            <?php
            $tracks = ozmoz_home_tracks_query();
            if ($tracks->have_posts()) :
                while ($tracks->have_posts()) :
                    $tracks->the_post();
                    ozmoz_render_track_card(get_the_ID());
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <div class="empty-state empty-state--wide">Add published sounds in WordPress to feature tracks here.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section section--split" id="dates">
    <div class="section__inner">
        <p class="section-kicker">Tour log</p>
        <h2 class="signal-title">Upcoming / Past dates.</h2>
        <div class="event-list">
            <?php
            $future_events = ozmoz_events_query('future');
            if ($future_events->have_posts()) :
                while ($future_events->have_posts()) :
                    $future_events->the_post();
                    ozmoz_render_event_card(get_the_ID());
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <div class="empty-state">New dates announced soon. For booking requests, contact the team.</div>
            <?php endif; ?>
        </div>

        <?php $past_events = ozmoz_events_query('past'); ?>
        <?php if ($past_events->have_posts()) : ?>
            <h3 class="section-kicker" style="margin-top:32px;">Past pressure</h3>
            <div class="event-list event-list--past">
                <?php
                while ($past_events->have_posts()) :
                    $past_events->the_post();
                    ozmoz_render_event_card(get_the_ID());
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section" id="press">
    <div class="section__inner press-grid">
        <div>
            <p class="section-kicker">Press kit</p>
            <h2>Built for bookers, clubs and festivals.</h2>
            <p class="lead">Two hard techno and industrial techno identities, one shared B2B pressure zone. ØZMØZ and MALØ bring dark, energetic and uncompromising B2B sets to clubs and festivals.</p>
            <div class="button-row">
                <?php if (!empty($socials['presskit'])) : ?>
                    <a class="button button--red" href="<?php echo esc_url($socials['presskit']); ?>" target="_blank" rel="noopener">Download press kit</a>
                <?php endif; ?>
                <a class="button" href="#booking">Contact booking</a>
            </div>
        </div>
        <div class="gallery-grid" aria-label="<?php esc_attr_e('Visuels presse', 'ozmoz-malo'); ?>">
            <div class="gallery-card"><img src="<?php echo ozmoz_asset('img/press-b2b.webp'); ?>" alt="ØZMØZ and MALØ press portrait"></div>
            <div class="gallery-card"><img src="<?php echo ozmoz_asset('img/art-a.webp'); ?>" alt="Hard techno and industrial techno identity artwork"></div>
            <div class="gallery-card"><img src="<?php echo ozmoz_asset('img/soundcloud-banner.webp'); ?>" alt="Hard techno and industrial techno SoundCloud banner artwork"></div>
        </div>
    </div>
</section>

<section class="section section--split" id="socials">
    <div class="section__inner">
        <p class="section-kicker">Connect</p>
        <h2 class="signal-title">Follow the signal.</h2>
        <div class="social-grid">
            <?php
            ozmoz_social_link('instagram', 'Instagram', true);
            ozmoz_social_link('tiktok', 'TikTok', true);
            ozmoz_social_link('youtube', 'YouTube', true);
            ozmoz_social_link('soundcloud', 'SoundCloud', true);
            ozmoz_social_link('spotify', 'Spotify');
            ozmoz_social_link('presskit', 'Press kit');
            ?>
        </div>
    </div>
</section>

<section class="section" id="booking">
    <div class="section__inner booking-grid">
        <div>
            <p class="section-kicker">Booking</p>
            <h2>Club, festival and B2B requests.</h2>
        </div>
        <div class="booking-card">
            <h3>Booking request</h3>
            <?php if (isset($_GET['booking_status'])) : ?>
                <?php if ($_GET['booking_status'] === 'sent') : ?>
                    <p class="form-status form-status--success"><?php esc_html_e('Request sent. We will get back to you soon.', 'ozmoz-malo'); ?></p>
                <?php else : ?>
                    <p class="form-status form-status--error"><?php esc_html_e('The request could not be sent. Please check the fields and try again.', 'ozmoz-malo'); ?></p>
                <?php endif; ?>
            <?php endif; ?>
            <form class="booking-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                <input type="hidden" name="action" value="ozmoz_booking_request">
                <?php wp_nonce_field('ozmoz_booking_request', 'ozmoz_booking_nonce'); ?>
                <label>
                    <span>Name</span>
                    <input type="text" name="ozmoz_booking_name" autocomplete="name" required>
                </label>
                <label>
                    <span>Email</span>
                    <input type="email" name="ozmoz_booking_email" autocomplete="email" required>
                </label>
                <label>
                    <span>Event / city</span>
                    <input type="text" name="ozmoz_booking_event" autocomplete="organization">
                </label>
                <label>
                    <span>Message</span>
                    <textarea name="ozmoz_booking_message" rows="5" required></textarea>
                </label>
                <label class="booking-hp" aria-hidden="true">
                    <span>Website</span>
                    <input type="text" name="ozmoz_booking_website" tabindex="-1" autocomplete="off">
                </label>
                <button class="button button--red" type="submit">Send request</button>
            </form>
        </div>
    </div>
</section>

<?php get_footer(); ?>
