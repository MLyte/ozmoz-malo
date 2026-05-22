<?php
/**
 * Exemple de wp-config.php pour ozmoz-malo.eu.
 *
 * Utilisation:
 * 1. Installer WordPress chez l'hebergeur.
 * 2. Copier ce fichier a la racine WordPress sous le nom wp-config.php si tu
 *    ne passes pas par l'assistant d'installation WordPress.
 * 3. Remplacer uniquement les valeurs PLACEHOLDER_* par celles fournies par
 *    l'hebergeur.
 *
 * Ne versionne jamais un vrai wp-config.php contenant les identifiants DB.
 */

// Base de donnees fournie par l'hebergeur.
define('DB_NAME', 'PLACEHOLDER_DATABASE_NAME');
define('DB_USER', 'PLACEHOLDER_DATABASE_USER');
define('DB_PASSWORD', 'PLACEHOLDER_DATABASE_PASSWORD');
define('DB_HOST', 'PLACEHOLDER_DATABASE_HOST');

// Encodage standard WordPress.
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

/**
 * Clefs de securite.
 *
 * A generer depuis:
 * https://api.wordpress.org/secret-key/1.1/salt/
 *
 * Remplace toutes les valeurs PLACEHOLDER_* en production.
 */
define('AUTH_KEY',         'PLACEHOLDER_GENERATE_UNIQUE_SALT');
define('SECURE_AUTH_KEY',  'PLACEHOLDER_GENERATE_UNIQUE_SALT');
define('LOGGED_IN_KEY',    'PLACEHOLDER_GENERATE_UNIQUE_SALT');
define('NONCE_KEY',        'PLACEHOLDER_GENERATE_UNIQUE_SALT');
define('AUTH_SALT',        'PLACEHOLDER_GENERATE_UNIQUE_SALT');
define('SECURE_AUTH_SALT', 'PLACEHOLDER_GENERATE_UNIQUE_SALT');
define('LOGGED_IN_SALT',   'PLACEHOLDER_GENERATE_UNIQUE_SALT');
define('NONCE_SALT',       'PLACEHOLDER_GENERATE_UNIQUE_SALT');

/**
 * Prefixe des tables.
 *
 * Garde un prefixe court, en minuscules, avec underscore final.
 * Exemple: ozm_ ou wp_ si l'hebergeur l'impose.
 */
$table_prefix = 'ozm_';

// URL cible. Utile pour eviter les mauvaises URLs lors d'un deploiement.
define('WP_HOME', 'https://ozmoz-malo.eu');
define('WP_SITEURL', 'https://ozmoz-malo.eu');

// Forcer l'admin en SSL si le certificat HTTPS est actif.
define('FORCE_SSL_ADMIN', true);

// Desactiver l'edition de fichiers depuis l'admin WordPress.
define('DISALLOW_FILE_EDIT', true);

// Desactiver le debug visible en production.
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
define('WP_DEBUG_LOG', false);

// Limite memoire raisonnable pour un petit site vitrine.
define('WP_MEMORY_LIMIT', '128M');

// Nettoyage automatique des revisions pour limiter la taille de la base.
define('WP_POST_REVISIONS', 10);
define('EMPTY_TRASH_DAYS', 14);

/**
 * Chemin WordPress.
 * Ne modifie pas ce bloc sauf cas tres specifique.
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
