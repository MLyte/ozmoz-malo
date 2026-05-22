# ØZMØZ / MALØ WordPress

Première base WordPress pour `ozmoz-malo.eu`.

## Structure

- `wp-content/themes/ozmoz-malo`: thème vitrine custom. C'est la partie visuelle du site.
- `wp-content/plugins/ozmoz-core`: plugin métier pour les dates, sons, réseaux et booking.
- `production/`: modèles et guides commentés pour préparer la mise en production WordPress.
- `_source-assets`: assets sources non optimisés, conservés localement et ignorés par Git.

Le dossier `wp-content` n'est pas une installation WordPress complète. Il doit être copié
dans une vraie installation WordPress fournie par l'hébergeur ou installée manuellement.
Une archive prête à uploader est disponible dans `production/ozmoz-malo-wordpress-upload.zip`.

## Installation

1. Installer WordPress.
2. Copier ce dossier `wp-content` dans l'installation WordPress.
3. Activer le thème `ØZMØZ / MALØ`.
4. Activer le plugin `ØZMØZ Core`.
5. Créer une page `Accueil`, choisir le modèle par défaut, puis la définir comme page d'accueil dans `Réglages > Lecture`.

## Gestion éditoriale

- Les artistes ajoutent les shows dans `Dates`.
- Les artistes ajoutent les sons dans `Sons`.
- Les liens sociaux et booking se gèrent dans `Réglages > ØZMØZ Réseaux`.
- Le formulaire booking envoie par défaut vers `ozmozmalo@gmail.com`.
- Les dates futures et passées sont séparées automatiquement selon le champ `Date`.

## Mise en production

Le dossier `production/` contient des fichiers d'aide:

- `wp-config.sample.php`: modèle commenté de configuration WordPress, sans secret réel.
- `.htaccess.wordpress`: règles WordPress standard et protections simples.
- `robots.txt`: base de robots.txt pour le site public.
- `maintenance-checklist.md`: étapes de mise en ligne.
- `content-to-fill.md`: contenus à rassembler et encoder.
- `events.csv`: dates 2025/2026 reçues, prêtes à recopier dans le menu `Dates`.
- `plugins-recommandes.md`: plugins utiles et plugins à éviter au départ.
- `dns-ssl.md`: rappel DNS, `www` et HTTPS.

Les identifiants base de données, mots de passe et salts WordPress doivent être saisis
côté serveur ou via l'assistant WordPress. Ils ne doivent pas être stockés dans ce dépôt.

## À confirmer avant mise en ligne

- Crédits et droits des photos/vidéos.
- Dates passées et à venir.
- Sons à publier ou mettre en avant.
- Lien press kit public.
- Liens Spotify/Beatport/Resident Advisor si disponibles.
- Mentions légales et politique de confidentialité.
