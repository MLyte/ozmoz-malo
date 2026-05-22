<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', 'ozmoz_theme_setup');
add_action('wp_enqueue_scripts', 'ozmoz_enqueue_assets');

function ozmoz_theme_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    register_nav_menus([
        'primary' => __('Navigation principale', 'ozmoz-malo'),
    ]);
}

function ozmoz_enqueue_assets(): void
{
    wp_enqueue_style('ozmoz-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
    wp_enqueue_script('ozmoz-site', get_template_directory_uri() . '/assets/js/site.js', [], wp_get_theme()->get('Version'), true);
}

function ozmoz_asset(string $path): string
{
    return esc_url(get_template_directory_uri() . '/assets/' . ltrim($path, '/'));
}

function ozmoz_socials(): array
{
    if (function_exists('ozmoz_get_social_options')) {
        return ozmoz_get_social_options();
    }

    return [
        'booking_email' => 'ozmozmalo@gmail.com',
        'booking_phone' => '',
        'instagram' => 'https://www.instagram.com/ozmoz_techno',
        'tiktok' => 'https://www.tiktok.com/@ozmoz_techno',
        'youtube' => 'https://youtube.com/@ozmoztechno?feature=shared',
        'soundcloud' => 'https://on.soundcloud.com/GjKBvHxxDjMkaidr6',
        'spotify' => '',
        'presskit' => '',
    ];
}

function ozmoz_social_icon(string $key): string
{
    $icons = [
        'instagram' => '<svg aria-hidden="true" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"></rect><circle cx="12" cy="12" r="4"></circle><circle cx="17" cy="7" r="1"></circle></svg>',
        'tiktok' => '<svg aria-hidden="true" viewBox="0 0 24 24"><path d="M14 3v11.2a4.2 4.2 0 1 1-4.2-4.2"></path><path d="M14 6.5c1.4 2.3 3.2 3.4 5.5 3.5"></path></svg>',
        'youtube' => '<svg aria-hidden="true" viewBox="0 0 24 24"><rect x="3" y="6" width="18" height="12" rx="3"></rect><path d="m10 9 5 3-5 3Z"></path></svg>',
        'soundcloud' => '<svg aria-hidden="true" viewBox="0 0 24 24"><path d="M5 17h13a3 3 0 0 0 0-6 5.5 5.5 0 0 0-10.6-1.8"></path><path d="M4 14v3"></path><path d="M7 12v5"></path><path d="M10 10v7"></path></svg>',
    ];

    return $icons[$key] ?? '';
}

function ozmoz_social_link(string $key, string $label, bool $with_icon = false): void
{
    $socials = ozmoz_socials();
    if (empty($socials[$key])) {
        return;
    }

    $icon = $with_icon ? ozmoz_social_icon($key) : '';
    $class = $with_icon && $icon ? 'social-link social-link--brand' : 'social-link';

    printf(
        '<a class="%1$s" href="%2$s" target="_blank" rel="noopener">%3$s%4$s</a>',
        esc_attr($class),
        esc_url($socials[$key]),
        $icon,
        esc_html($label)
    );
}

function ozmoz_events_query(string $direction = 'future'): WP_Query
{
    $today = wp_date('Y-m-d');
    $compare = $direction === 'past' ? '<' : '>=';
    $order = $direction === 'past' ? 'DESC' : 'ASC';

    return new WP_Query([
        'post_type' => 'ozmoz_event',
        'posts_per_page' => -1,
        'meta_key' => '_ozmoz_event_date',
        'orderby' => 'meta_value',
        'order' => $order,
        'meta_query' => [
            [
                'key' => '_ozmoz_event_date',
                'value' => $today,
                'compare' => $compare,
                'type' => 'DATE',
            ],
        ],
    ]);
}

function ozmoz_tracks_query(bool $featured_only = false): WP_Query
{
    $meta_query = [];
    if ($featured_only) {
        $meta_query[] = [
            'key' => '_ozmoz_track_featured',
            'value' => '1',
            'compare' => '=',
        ];
    }

    return new WP_Query([
        'post_type' => 'ozmoz_track',
        'posts_per_page' => 6,
        'post_status' => 'publish',
        'meta_key' => '_ozmoz_track_order',
        'orderby' => [
            'meta_value_num' => 'ASC',
            'date' => 'DESC',
        ],
        'meta_query' => $meta_query,
    ]);
}

function ozmoz_home_tracks_query(): WP_Query
{
    $featured_tracks = ozmoz_tracks_query(true);
    if ($featured_tracks->have_posts()) {
        return $featured_tracks;
    }

    wp_reset_postdata();

    return ozmoz_tracks_query(false);
}

function ozmoz_render_track_card(int $post_id): void
{
    $track_url = get_post_meta($post_id, '_ozmoz_track_url', true);
    $is_featured = (bool) get_post_meta($post_id, '_ozmoz_track_featured', true);
    ?>
    <article class="track-card">
        <?php if (has_post_thumbnail($post_id)) : ?>
            <a class="track-card__image" href="<?php echo esc_url($track_url ?: get_permalink($post_id)); ?>" <?php echo $track_url ? 'target="_blank" rel="noopener"' : ''; ?>>
                <?php echo get_the_post_thumbnail($post_id, 'medium_large'); ?>
            </a>
        <?php endif; ?>
        <?php if ($is_featured) : ?>
            <p class="track-card__tag"><?php esc_html_e('Featured', 'ozmoz-malo'); ?></p>
        <?php endif; ?>
        <h3><?php echo esc_html(get_the_title($post_id)); ?></h3>
        <?php if (has_excerpt($post_id)) : ?>
            <p class="muted"><?php echo esc_html(get_the_excerpt($post_id)); ?></p>
        <?php elseif (get_post_field('post_content', $post_id)) : ?>
            <p class="muted"><?php echo esc_html(wp_trim_words(get_post_field('post_content', $post_id), 24)); ?></p>
        <?php endif; ?>
        <?php if ($track_url) : ?>
            <a class="social-link" href="<?php echo esc_url($track_url); ?>" target="_blank" rel="noopener"><?php esc_html_e('Listen', 'ozmoz-malo'); ?></a>
        <?php endif; ?>
    </article>
    <?php
}

function ozmoz_render_event_card(int $post_id): void
{
    $date = get_post_meta($post_id, '_ozmoz_event_date', true);
    $time = get_post_meta($post_id, '_ozmoz_event_time', true);
    $venue = get_post_meta($post_id, '_ozmoz_venue', true);
    $city = get_post_meta($post_id, '_ozmoz_city', true);
    $country = get_post_meta($post_id, '_ozmoz_country', true);
    $format = get_post_meta($post_id, '_ozmoz_format', true);
    $ticket_url = get_post_meta($post_id, '_ozmoz_ticket_url', true);
    $date_label_override = get_post_meta($post_id, '_ozmoz_event_date_label', true);
    $date_label = $date_label_override ?: ($date ? wp_date('d M Y', strtotime($date)) : __('TBA', 'ozmoz-malo'));
    $card_classes = ['event-card'];
    if ($date && strtotime($date) < strtotime(wp_date('Y-m-d'))) {
        $card_classes[] = 'event-card--past';
    }
    $location = trim($city . ' ' . ozmoz_country_flag_markup($country));
    $meta_parts = array_filter([
        esc_html($time),
        ozmoz_link_instagram_handles((string) $venue),
        $location,
        esc_html($format),
    ]);
    ?>
    <article class="<?php echo esc_attr(implode(' ', $card_classes)); ?>">
        <div class="event-date"><?php echo esc_html($date_label); ?></div>
        <div>
            <h3 class="event-title"><a href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo esc_html(get_the_title($post_id)); ?></a></h3>
            <p class="event-meta">
                <?php echo wp_kses_post(implode(' / ', $meta_parts)); ?>
            </p>
        </div>
        <?php if ($ticket_url) : ?>
            <a class="event-link" href="<?php echo esc_url($ticket_url); ?>" target="_blank" rel="noopener"><?php esc_html_e('Info', 'ozmoz-malo'); ?></a>
        <?php endif; ?>
    </article>
    <?php
}

function ozmoz_country_flag(string $country): string
{
    $country = strtoupper(trim($country));
    $flags = [
        'BE' => '🇧🇪',
        'BELGIUM' => '🇧🇪',
        'BELGIQUE' => '🇧🇪',
        'DE' => '🇩🇪',
        'GERMANY' => '🇩🇪',
        'ALLEMAGNE' => '🇩🇪',
        'FR' => '🇫🇷',
        'FRANCE' => '🇫🇷',
        'LU' => '🇱🇺',
        'LUXEMBOURG' => '🇱🇺',
        'IT' => '🇮🇹',
        'ITALY' => '🇮🇹',
        'ITALIE' => '🇮🇹',
    ];

    return $flags[$country] ?? $country;
}

function ozmoz_country_flag_markup(string $country): string
{
    $country = strtoupper(trim($country));
    $classes = [
        'BE' => ['flag-be', 'Belgium'],
        'BELGIUM' => ['flag-be', 'Belgium'],
        'BELGIQUE' => ['flag-be', 'Belgium'],
        'DE' => ['flag-de', 'Germany'],
        'GERMANY' => ['flag-de', 'Germany'],
        'ALLEMAGNE' => ['flag-de', 'Germany'],
        'FR' => ['flag-fr', 'France'],
        'FRANCE' => ['flag-fr', 'France'],
        'LU' => ['flag-lu', 'Luxembourg'],
        'LUXEMBOURG' => ['flag-lu', 'Luxembourg'],
        'NL' => ['flag-nl', 'Netherlands'],
        'NETHERLANDS' => ['flag-nl', 'Netherlands'],
        'PAYS-BAS' => ['flag-nl', 'Netherlands'],
        'ES' => ['flag-es', 'Spain'],
        'SPAIN' => ['flag-es', 'Spain'],
        'ESPAGNE' => ['flag-es', 'Spain'],
        'IT' => ['flag-it', 'Italy'],
        'ITALY' => ['flag-it', 'Italy'],
        'ITALIE' => ['flag-it', 'Italy'],
    ];

    if (!isset($classes[$country])) {
        return esc_html($country);
    }

    [$class, $label] = $classes[$country];

    return sprintf('<span class="flag %s" aria-label="%s"></span>', esc_attr($class), esc_attr($label));
}

function ozmoz_link_instagram_handles(string $text): string
{
    $escaped = esc_html($text);

    return preg_replace_callback('/@([A-Za-z0-9._]{2,30})/', static function (array $matches): string {
        $handle = $matches[1];
        $url = 'https://www.instagram.com/' . rawurlencode($handle) . '/';

        return sprintf(
            '<a href="%s" target="_blank" rel="noopener">@%s</a>',
            esc_url($url),
            esc_html($handle)
        );
    }, $escaped) ?: $escaped;
}
