# Plugins recommandes

Le site est volontairement leger. Installer seulement les plugins utiles.

## Indispensables ou tres utiles

- SEO: SEOPress ou Yoast SEO.
  - Un seul plugin SEO suffit.
  - Sert aux titres, descriptions, sitemap et partage social.
- Cache/performance:
  - LiteSpeed Cache si l'hebergement utilise LiteSpeed.
  - Sinon Cache Enabler, WP Rocket ou solution fournie par l'hebergeur.
- Sauvegardes:
  - UpdraftPlus si l'hebergeur ne fournit pas de sauvegardes fiables.
- SMTP:
  - WP Mail SMTP ou equivalent si WordPress doit envoyer des emails fiables.
- Securite:
  - Plugin de limitation de tentatives de connexion si non fourni par l'hebergeur.
  - Activer la double authentification si possible.

## Images

- ShortPixel, Imagify ou Optimole si beaucoup de photos sont ajoutees via l'admin.
- Les images deja dans le theme ont ete optimisees; ne pas uploader les sources HD
  sans compression.

## A eviter au depart

- Builders lourds type Elementor si le site reste une vitrine simple.
- Plugins de calendrier complexes si le menu `Dates` du plugin `ØZMØZ Core` suffit.
- Plugins de flux Instagram/TikTok tant que de simples liens sociaux suffisent.
- Plusieurs plugins qui font la meme chose: cache, SEO, securite ou optimisation.

## Evolution possible

- ACF peut etre ajoute plus tard si l'accueil doit devenir tres modulable.
- The Events Calendar peut remplacer le CPT maison si les besoins deviennent plus
  complexes: calendrier mensuel, imports, recurrence, billetterie avancee.
