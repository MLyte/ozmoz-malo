<?php
/**
 * Plugin Name: ØZMØZ Core
 * Description: Dates, sounds, social links and booking settings for ozmoz-malo.eu.
 * Version: 0.2.0
 * Author: Codex
 * Text Domain: ozmoz-core
 */

if (!defined('ABSPATH')) {
    exit;
}

const OZMOZ_CORE_VERSION = '0.2.0';
const OZMOZ_SOCIAL_OPTION = 'ozmoz_social_links';

add_action('init', 'ozmoz_register_event_post_type');
add_action('init', 'ozmoz_register_event_meta');
add_action('init', 'ozmoz_register_track_post_type');
add_action('init', 'ozmoz_register_track_meta');
add_action('add_meta_boxes', 'ozmoz_register_event_meta_box');
add_action('add_meta_boxes', 'ozmoz_register_track_meta_box');
add_action('save_post_ozmoz_event', 'ozmoz_save_event_meta');
add_action('save_post_ozmoz_track', 'ozmoz_save_track_meta');
add_action('admin_menu', 'ozmoz_register_settings_page');
add_action('admin_init', 'ozmoz_register_settings');
add_action('wp_head', 'ozmoz_print_event_schema');
add_action('admin_post_nopriv_ozmoz_booking_request', 'ozmoz_handle_booking_request');
add_action('admin_post_ozmoz_booking_request', 'ozmoz_handle_booking_request');
add_filter('manage_ozmoz_event_posts_columns', 'ozmoz_event_admin_columns');
add_action('manage_ozmoz_event_posts_custom_column', 'ozmoz_event_admin_column_content', 10, 2);
add_filter('manage_ozmoz_track_posts_columns', 'ozmoz_track_admin_columns');
add_action('manage_ozmoz_track_posts_custom_column', 'ozmoz_track_admin_column_content', 10, 2);
register_activation_hook(__FILE__, 'ozmoz_activate');
register_deactivation_hook(__FILE__, 'flush_rewrite_rules');

function ozmoz_register_event_post_type(): void
{
    register_post_type('ozmoz_event', [
        'labels' => [
            'name' => __('Dates', 'ozmoz-core'),
            'singular_name' => __('Date', 'ozmoz-core'),
            'add_new_item' => __('Ajouter une date', 'ozmoz-core'),
            'edit_item' => __('Modifier la date', 'ozmoz-core'),
            'menu_name' => __('Dates', 'ozmoz-core'),
        ],
        'public' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title', 'editor', 'thumbnail'],
        'has_archive' => 'dates',
        'rewrite' => ['slug' => 'dates'],
        'show_in_rest' => true,
    ]);
}

function ozmoz_activate(): void
{
    ozmoz_register_event_post_type();
    ozmoz_register_track_post_type();
    flush_rewrite_rules();
}

function ozmoz_register_track_post_type(): void
{
    register_post_type('ozmoz_track', [
        'labels' => [
            'name' => __('Sons', 'ozmoz-core'),
            'singular_name' => __('Son', 'ozmoz-core'),
            'add_new_item' => __('Ajouter un son', 'ozmoz-core'),
            'edit_item' => __('Modifier le son', 'ozmoz-core'),
            'menu_name' => __('Sons', 'ozmoz-core'),
        ],
        'public' => true,
        'menu_icon' => 'dashicons-format-audio',
        'supports' => ['title', 'editor', 'thumbnail'],
        'has_archive' => 'sons',
        'rewrite' => ['slug' => 'sons'],
        'show_in_rest' => true,
    ]);
}

function ozmoz_register_event_meta(): void
{
    $fields = ['event_date', 'event_date_label', 'event_time', 'venue', 'city', 'country', 'format', 'ticket_url'];

    foreach ($fields as $field) {
        register_post_meta('ozmoz_event', "_ozmoz_{$field}", [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => $field === 'ticket_url' ? 'esc_url_raw' : 'sanitize_text_field',
            'auth_callback' => static fn() => current_user_can('edit_posts'),
        ]);
    }
}

function ozmoz_register_track_meta(): void
{
    $fields = [
        'track_url' => ['type' => 'string', 'sanitize_callback' => 'esc_url_raw'],
        'track_featured' => ['type' => 'boolean', 'sanitize_callback' => 'rest_sanitize_boolean'],
        'track_order' => ['type' => 'integer', 'sanitize_callback' => 'absint'],
    ];

    foreach ($fields as $field => $args) {
        register_post_meta('ozmoz_track', "_ozmoz_{$field}", [
            'type' => $args['type'],
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => $args['sanitize_callback'],
            'auth_callback' => static fn() => current_user_can('edit_posts'),
        ]);
    }
}

function ozmoz_register_event_meta_box(): void
{
    add_meta_box(
        'ozmoz_event_details',
        __('Détails de la date', 'ozmoz-core'),
        'ozmoz_render_event_meta_box',
        'ozmoz_event',
        'normal',
        'high'
    );
}

function ozmoz_render_event_meta_box(WP_Post $post): void
{
    wp_nonce_field('ozmoz_save_event_meta', 'ozmoz_event_nonce');

    $fields = [
        'event_date' => __('Date', 'ozmoz-core'),
        'event_date_label' => __('Libelle date public', 'ozmoz-core'),
        'event_time' => __('Heure', 'ozmoz-core'),
        'venue' => __('Lieu / club', 'ozmoz-core'),
        'city' => __('Ville', 'ozmoz-core'),
        'country' => __('Pays', 'ozmoz-core'),
        'format' => __('Format', 'ozmoz-core'),
        'ticket_url' => __('Lien tickets / info', 'ozmoz-core'),
    ];

    echo '<div class="ozmoz-admin-grid">';
    foreach ($fields as $key => $label) {
        $type = $key === 'event_date' ? 'date' : ($key === 'event_time' ? 'time' : 'text');
        $value = get_post_meta($post->ID, "_ozmoz_{$key}", true);
        printf(
            '<p><label for="%1$s"><strong>%2$s</strong></label><input class="widefat" type="%3$s" id="%1$s" name="%1$s" value="%4$s"></p>',
            esc_attr($key),
            esc_html($label),
            esc_attr($type),
            esc_attr($value)
        );
    }
    echo '</div>';
    echo '<p class="description">Les dates futures et passées sont triées automatiquement depuis le champ Date.</p>';
}

function ozmoz_register_track_meta_box(): void
{
    add_meta_box(
        'ozmoz_track_details',
        __('Détails du son', 'ozmoz-core'),
        'ozmoz_render_track_meta_box',
        'ozmoz_track',
        'normal',
        'high'
    );
}

function ozmoz_render_track_meta_box(WP_Post $post): void
{
    wp_nonce_field('ozmoz_save_track_meta', 'ozmoz_track_nonce');

    $track_url = get_post_meta($post->ID, '_ozmoz_track_url', true);
    $track_featured = (bool) get_post_meta($post->ID, '_ozmoz_track_featured', true);
    $track_order = get_post_meta($post->ID, '_ozmoz_track_order', true);
    ?>
    <p>
        <label for="track_url"><strong><?php esc_html_e('Lien principal', 'ozmoz-core'); ?></strong></label>
        <input class="widefat" type="url" id="track_url" name="track_url" value="<?php echo esc_attr($track_url); ?>" placeholder="https://soundcloud.com/...">
    </p>
    <p class="description"><?php esc_html_e('SoundCloud, Spotify, YouTube, Beatport ou autre lien public.', 'ozmoz-core'); ?></p>
    <p>
        <label>
            <input type="checkbox" name="track_featured" value="1" <?php checked($track_featured); ?>>
            <strong><?php esc_html_e('Mettre en avant sur l’accueil', 'ozmoz-core'); ?></strong>
        </label>
    </p>
    <p>
        <label for="track_order"><strong><?php esc_html_e('Ordre d’affichage', 'ozmoz-core'); ?></strong></label>
        <input class="small-text" type="number" min="0" step="1" id="track_order" name="track_order" value="<?php echo esc_attr($track_order !== '' ? $track_order : '0'); ?>">
    </p>
    <?php
}

function ozmoz_save_event_meta(int $post_id): void
{
    if (!isset($_POST['ozmoz_event_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ozmoz_event_nonce'])), 'ozmoz_save_event_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = ['event_date', 'event_date_label', 'event_time', 'venue', 'city', 'country', 'format', 'ticket_url'];
    foreach ($fields as $field) {
        $raw = isset($_POST[$field]) ? wp_unslash($_POST[$field]) : '';
        $value = $field === 'ticket_url' ? esc_url_raw($raw) : sanitize_text_field($raw);
        update_post_meta($post_id, "_ozmoz_{$field}", $value);
    }
}

function ozmoz_save_track_meta(int $post_id): void
{
    if (!isset($_POST['ozmoz_track_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ozmoz_track_nonce'])), 'ozmoz_save_track_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $track_url = isset($_POST['track_url']) ? esc_url_raw(wp_unslash($_POST['track_url'])) : '';
    $track_featured = isset($_POST['track_featured']) ? '1' : '0';
    $track_order = isset($_POST['track_order']) ? absint(wp_unslash($_POST['track_order'])) : 0;

    update_post_meta($post_id, '_ozmoz_track_url', $track_url);
    update_post_meta($post_id, '_ozmoz_track_featured', $track_featured);
    update_post_meta($post_id, '_ozmoz_track_order', $track_order);
}

function ozmoz_register_settings_page(): void
{
    add_options_page(
        __('ØZMØZ Réseaux / Booking', 'ozmoz-core'),
        __('ØZMØZ Réseaux', 'ozmoz-core'),
        'manage_options',
        'ozmoz-socials',
        'ozmoz_render_settings_page'
    );
}

function ozmoz_register_settings(): void
{
    register_setting('ozmoz_socials', OZMOZ_SOCIAL_OPTION, [
        'type' => 'array',
        'sanitize_callback' => 'ozmoz_sanitize_social_options',
        'default' => ozmoz_default_social_options(),
    ]);
}

function ozmoz_default_social_options(): array
{
    return [
        'booking_email' => 'ozmozmalo@gmail.com',
        'instagram' => 'https://www.instagram.com/ozmoz_techno',
        'tiktok' => 'https://www.tiktok.com/@ozmoz_techno',
        'youtube' => 'https://youtube.com/@ozmoztechno?feature=shared',
        'soundcloud' => 'https://on.soundcloud.com/GjKBvHxxDjMkaidr6',
        'spotify' => '',
        'presskit' => '',
    ];
}

function ozmoz_sanitize_social_options(array $input): array
{
    $defaults = ozmoz_default_social_options();
    $output = [];

    foreach ($defaults as $key => $default) {
        $raw = $input[$key] ?? '';
        if ($key === 'booking_email') {
            $output[$key] = sanitize_email($raw);
        } else {
            $output[$key] = esc_url_raw($raw);
        }
    }

    return $output;
}

function ozmoz_get_social_options(): array
{
    $saved = get_option(OZMOZ_SOCIAL_OPTION, []);
    return wp_parse_args(is_array($saved) ? $saved : [], ozmoz_default_social_options());
}

function ozmoz_render_settings_page(): void
{
    $options = ozmoz_get_social_options();
    $labels = [
        'booking_email' => __('Email booking', 'ozmoz-core'),
        'instagram' => __('Instagram', 'ozmoz-core'),
        'tiktok' => __('TikTok', 'ozmoz-core'),
        'youtube' => __('YouTube', 'ozmoz-core'),
        'soundcloud' => __('SoundCloud', 'ozmoz-core'),
        'spotify' => __('Spotify', 'ozmoz-core'),
        'presskit' => __('Lien press kit', 'ozmoz-core'),
    ];
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('ØZMØZ Réseaux / Booking', 'ozmoz-core'); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('ozmoz_socials'); ?>
            <table class="form-table" role="presentation">
                <?php foreach ($labels as $key => $label) : ?>
                    <tr>
                        <th scope="row"><label for="ozmoz_<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label></th>
                        <td>
                            <input class="regular-text" id="ozmoz_<?php echo esc_attr($key); ?>" name="<?php echo esc_attr(OZMOZ_SOCIAL_OPTION . '[' . $key . ']'); ?>" value="<?php echo esc_attr($options[$key]); ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function ozmoz_handle_booking_request(): void
{
    $redirect = wp_get_referer() ?: home_url('/#booking');
    $redirect = strtok($redirect, '#') ?: $redirect;

    if (!isset($_POST['ozmoz_booking_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ozmoz_booking_nonce'])), 'ozmoz_booking_request')) {
        wp_safe_redirect(add_query_arg('booking_status', 'error', $redirect) . '#booking');
        exit;
    }

    $honeypot = isset($_POST['ozmoz_booking_website']) ? trim((string) wp_unslash($_POST['ozmoz_booking_website'])) : '';
    if ($honeypot !== '') {
        wp_safe_redirect(add_query_arg('booking_status', 'sent', $redirect) . '#booking');
        exit;
    }

    $options = ozmoz_get_social_options();
    $recipient = sanitize_email($options['booking_email'] ?? '');
    $name = sanitize_text_field(wp_unslash($_POST['ozmoz_booking_name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['ozmoz_booking_email'] ?? ''));
    $event = sanitize_text_field(wp_unslash($_POST['ozmoz_booking_event'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['ozmoz_booking_message'] ?? ''));

    if (!$recipient || !$name || !$email || !$message) {
        wp_safe_redirect(add_query_arg('booking_status', 'error', $redirect) . '#booking');
        exit;
    }

    $subject = sprintf('ØZMØZ / MALØ booking request - %s', $name);
    $body = implode("\n\n", array_filter([
        "Name: {$name}",
        "Email: {$email}",
        $event ? "Event / city: {$event}" : '',
        "Message:\n{$message}",
    ]));
    $headers = ['Reply-To: ' . $name . ' <' . $email . '>'];

    $sent = wp_mail($recipient, $subject, $body, $headers);
    wp_safe_redirect(add_query_arg('booking_status', $sent ? 'sent' : 'error', $redirect) . '#booking');
    exit;
}

function ozmoz_print_event_schema(): void
{
    if (!is_singular('ozmoz_event')) {
        return;
    }

    $post_id = get_queried_object_id();
    $date = get_post_meta($post_id, '_ozmoz_event_date', true);
    if (!$date) {
        return;
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'MusicEvent',
        'name' => get_the_title($post_id),
        'startDate' => $date . 'T' . (get_post_meta($post_id, '_ozmoz_event_time', true) ?: '22:00'),
        'performer' => [
            ['@type' => 'MusicGroup', 'name' => 'ØZMØZ'],
            ['@type' => 'MusicGroup', 'name' => 'MALØ'],
        ],
        'location' => [
            '@type' => 'Place',
            'name' => get_post_meta($post_id, '_ozmoz_venue', true),
            'address' => trim(get_post_meta($post_id, '_ozmoz_city', true) . ', ' . get_post_meta($post_id, '_ozmoz_country', true), ', '),
        ],
    ];

    $ticket_url = get_post_meta($post_id, '_ozmoz_ticket_url', true);
    if ($ticket_url) {
        $schema['offers'] = ['@type' => 'Offer', 'url' => $ticket_url];
    }

    printf("\n<script type=\"application/ld+json\">%s</script>\n", wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
}

function ozmoz_event_admin_columns(array $columns): array
{
    $date_columns = [
        'event_date' => __('Date', 'ozmoz-core'),
        'event_city' => __('Ville', 'ozmoz-core'),
        'event_format' => __('Format', 'ozmoz-core'),
    ];

    return array_slice($columns, 0, 2, true) + $date_columns + array_slice($columns, 2, null, true);
}

function ozmoz_event_admin_column_content(string $column, int $post_id): void
{
    if ($column === 'event_date') {
        echo esc_html(get_post_meta($post_id, '_ozmoz_event_date', true) ?: 'TBA');
    }

    if ($column === 'event_city') {
        echo esc_html(implode(', ', array_filter([
            get_post_meta($post_id, '_ozmoz_city', true),
            get_post_meta($post_id, '_ozmoz_country', true),
        ])));
    }

    if ($column === 'event_format') {
        echo esc_html(get_post_meta($post_id, '_ozmoz_format', true));
    }
}

function ozmoz_track_admin_columns(array $columns): array
{
    $track_columns = [
        'track_featured' => __('Accueil', 'ozmoz-core'),
        'track_order' => __('Ordre', 'ozmoz-core'),
        'track_url' => __('Lien', 'ozmoz-core'),
    ];

    return array_slice($columns, 0, 2, true) + $track_columns + array_slice($columns, 2, null, true);
}

function ozmoz_track_admin_column_content(string $column, int $post_id): void
{
    if ($column === 'track_featured') {
        echo get_post_meta($post_id, '_ozmoz_track_featured', true) ? esc_html__('Oui', 'ozmoz-core') : esc_html__('Non', 'ozmoz-core');
    }

    if ($column === 'track_order') {
        echo esc_html((string) get_post_meta($post_id, '_ozmoz_track_order', true));
    }

    if ($column === 'track_url') {
        $url = get_post_meta($post_id, '_ozmoz_track_url', true);
        if ($url) {
            printf('<a href="%1$s" target="_blank" rel="noopener">%2$s</a>', esc_url($url), esc_html__('Ouvrir', 'ozmoz-core'));
        }
    }
}
