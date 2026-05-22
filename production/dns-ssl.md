# DNS et SSL pour ozmoz-malo.eu

Ce guide reste volontairement generique, car chaque hebergeur affiche ses valeurs
DNS dans son propre panneau.

## Choix recommande

- Domaine principal: `ozmoz-malo.eu`
- Variante `www`: redirigee vers `ozmoz-malo.eu`
- HTTPS obligatoire sur les deux variantes.

## DNS

Dans le gestionnaire DNS du nom de domaine:

- Ajouter ou modifier l'enregistrement `A` de `ozmoz-malo.eu` vers l'adresse IP
  fournie par l'hebergeur.
- Ajouter ou modifier `www`:
  - soit en `CNAME` vers `ozmoz-malo.eu`,
  - soit en `A` vers la meme IP si l'hebergeur le demande.
- Ne pas multiplier les enregistrements contradictoires pour le meme nom.

## Propagation

- La propagation DNS peut prendre de quelques minutes a 24 heures.
- Pendant ce temps, le domaine peut fonctionner sur certains reseaux et pas sur
  d'autres.
- Ne pas conclure trop vite a une erreur si le changement vient d'etre fait.

## SSL / HTTPS

- Activer le certificat SSL dans le panneau hebergeur.
- Attendre que le certificat soit valide avant de forcer la redirection HTTPS.
- Dans WordPress, verifier:
  - Adresse web de WordPress: `https://ozmoz-malo.eu`
  - Adresse web du site: `https://ozmoz-malo.eu`
- Si besoin, utiliser le bloc HTTPS commente dans `.htaccess.wordpress`.

## Redirection www

Option conseillee:

- `https://www.ozmoz-malo.eu` redirige vers `https://ozmoz-malo.eu`.

Cela evite deux URLs concurrentes pour le meme site.

## Verification finale

- Ouvrir `https://ozmoz-malo.eu`.
- Ouvrir `https://www.ozmoz-malo.eu` et verifier la redirection.
- Verifier que le cadenas HTTPS est valide.
- Tester `/wp-admin`.
- Tester les permaliens des dates.
