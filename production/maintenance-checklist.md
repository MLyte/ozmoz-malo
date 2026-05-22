# Checklist mise en production

Cette checklist part du principe que l'hebergement WordPress OVH et le nom de domaine
`ozmoz-malo.eu` sont achetes, mais que rien n'est encore configure.

## 1. Hebergement

- Verifier PHP 8.1 ou superieur.
- Creer ou noter la base MySQL/MariaDB: nom, utilisateur, mot de passe, host.
- Activer HTTPS/SSL dans le panneau hebergeur.
- Verifier l'acces FTP/SFTP ou le gestionnaire de fichiers.
- Activer les sauvegardes automatiques si l'hebergeur les propose.

## 2. DNS

- Faire pointer `ozmoz-malo.eu` vers l'hebergement.
- Ajouter `www.ozmoz-malo.eu` et le rediriger vers le domaine principal.
- Attendre la propagation DNS avant de forcer HTTPS.

## 3. Installation WordPress

- Installer WordPress core via le module 1-clic OVH.
- Creer le compte administrateur principal.
- Choisir une URL HTTPS: `https://ozmoz-malo.eu`.
- Verifier que le back-office fonctionne: `/wp-admin`.

## 4. Fichiers du projet

- Copier `wp-content/themes/ozmoz-malo` dans `wp-content/themes/`.
- Copier `wp-content/plugins/ozmoz-core` dans `wp-content/plugins/`.
- Option rapide: utiliser `production/ozmoz-malo-wordpress-upload.zip`, qui contient uniquement le theme et le plugin a envoyer.
- Ne pas uploader `_source-assets`: ce dossier contient les sources lourdes.
- Ne pas remplacer `wp-content/uploads` si WordPress contient deja des medias.

## 5. Activation WordPress

- Activer le theme `ØZMØZ / MALØ`.
- Activer le plugin `ØZMØZ Core`.
- Aller dans `Reglages > Permaliens` et enregistrer une fois.
- Creer une page `Accueil`.
- Dans `Reglages > Lecture`, definir `Accueil` comme page d'accueil.

## 6. Contenu initial

- Encoder les liens sociaux dans `Reglages > ØZMØZ Reseaux`.
- Verifier l'email booking: `ozmozmalo@gmail.com`.
- Ajouter les sons publies dans `Sons`.
- Ajouter les dates futures et passees dans `Dates`.
- Ajouter le lien public du press kit si disponible.
- Ajouter les pages legales: mentions legales, politique de confidentialite.

## 7. Verification avant annonce

- Tester la page d'accueil sur mobile et desktop.
- Tester chaque lien social.
- Tester l'email de booking.
- Installer et configurer WP Mail SMTP si Gmail ne recoit pas le test.
- Verifier qu'aucun fichier source lourd n'est accessible publiquement.
- Verifier que le site force bien HTTPS.
- Faire une sauvegarde complete apres configuration.
