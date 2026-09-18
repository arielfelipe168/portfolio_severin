<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();
$profile        = fetchProfile($pdo);
$experiences    = fetchAll($pdo, 'experiences');
$formations     = fetchAll($pdo, 'formations');
$techniques     = fetchCompetences($pdo, 'technique');
$transversales  = fetchCompetences($pdo, 'transversale');
$langues        = fetchAll($pdo, 'langues');
$outils         = fetchAll($pdo, 'outils');
$loisirs        = fetchAll($pdo, 'loisirs');
$associations   = fetchAll($pdo, 'associations');

$photoFile = $profile['photo'] ?? '';
$photoPath = $photoFile && file_exists(__DIR__ . '/assets/uploads/' . $photoFile)
    ? 'assets/uploads/' . $photoFile
    : 'https://placehold.co/600x800?text=Photo';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($profile['nom_complet'] ?? 'Portfolio') ?> — Portfolio</title>
<meta name="description" content="<?= e($profile['titre'] ?? '') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<style>
  body { font-family: 'Inter', var(--sans); }
  .hero-title, .section-title, .timeline-title, .formation-degree, .nav-brand { font-family: 'Playfair Display', var(--serif); }
</style>
</head>
<body>

<nav class="nav">
  <div class="nav-inner">
    <a href="#top" class="nav-brand">Mon Portfolio</a>
    <ul class="nav-links">
      <li><a href="#about">Profil</a></li>
      <li><a href="#experience">Expérience</a></li>
      <li><a href="#formations">Formations</a></li>
      <li><a href="#competences">Compétences</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
    <button class="nav-toggle" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<main>

  <header class="hero" id="top">
    <div class="hero-bg-shape"></div>
    <div class="container hero-inner">
      <div>
        <p class="hero-eyebrow" data-hero style="opacity:0; transform: translateY(16px);">Portfolio professionnel</p>
        <h1 class="hero-title" data-hero style="opacity:0; transform: translateY(16px);"><?= e($profile['nom_complet'] ?? '') ?></h1>
        <p class="hero-role" data-hero style="opacity:0; transform: translateY(16px);"><?= e($profile['titre'] ?? '') ?></p>
        <div class="hero-cta" data-hero style="opacity:0; transform: translateY(16px);">
          <a href="#contact" class="btn btn-primary">Me contacter</a>
          <a href="#experience" class="btn btn-outline">Voir le parcours</a>
        </div>
      </div>
      <div class="hero-photo-wrap">
        <div class="hero-photo-frame" data-hero style="opacity:0; transform: scale(.92);">
          <img src="<?= e($photoPath) ?>" alt="Photo de <?= e($profile['nom_complet'] ?? '') ?>">
        </div>
        <div class="hero-badge" data-hero style="opacity:0;">
          <strong><?= count($experiences) ?>+</strong>
          expériences médias
        </div>
      </div>
    </div>
    <div class="scroll-cue" data-hero style="opacity:0;"><span class="line"></span>Défiler</div>
  </header>

  <section id="about">
    <div class="container">
      <div class="section-head reveal">
        <p class="section-eyebrow">Qui suis-je</p>
        <h2 class="section-title">Profil</h2>
      </div>
      <div class="about-grid">
        <div class="reveal">
          <p class="about-bio"><?= nl2br(e($profile['bio'] ?? '')) ?></p>
          <ul class="info-list">
            <li><span>Nationalité</span><?= e($profile['nationalite'] ?? '') ?></li>
            <li><span>Statut civil</span><?= e($profile['statut_civil'] ?? '') ?></li>
            <li><span>Mobilité</span><?= e($profile['mobilite'] ?? '') ?></li>
            <li><span>Adresse</span><?= e($profile['adresse'] ?? '') ?></li>
          </ul>
        </div>
        <div class="reveal">
          <p class="skill-subtitle">Langues</p>
          <div class="chip-row">
            <?php foreach ($langues as $l): ?>
              <span class="chip"><b><?= e($l['langue']) ?></b> — <?= e($l['niveau']) ?></span>
            <?php endforeach; ?>
          </div>
          <p class="skill-subtitle" style="margin-top:28px;">Outils bureautiques</p>
          <div class="chip-row">
            <?php foreach ($outils as $o): ?>
              <span class="chip"><?= e($o['libelle']) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="experience" class="alt-bg">
    <div class="container">
      <div class="section-head reveal">
        <p class="section-eyebrow">Parcours</p>
        <h2 class="section-title">Expériences professionnelles</h2>
      </div>
      <div class="timeline">
        <?php foreach ($experiences as $exp): ?>
          <div class="timeline-item reveal">
            <div class="timeline-period"><?= e($exp['periode']) ?></div>
            <div class="timeline-title"><?= e($exp['poste']) ?></div>
            <?php if (!empty($exp['structure'])): ?>
              <div class="timeline-org"><?= e($exp['structure']) ?></div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section id="formations">
    <div class="container">
      <div class="section-head reveal">
        <p class="section-eyebrow">Parcours académique</p>
        <h2 class="section-title">Formations</h2>
      </div>
      <?php foreach ($formations as $f): ?>
        <div class="formation-card reveal">
          <div class="formation-year"><?= e($f['annee']) ?></div>
          <div class="formation-degree"><?= e($f['diplome']) ?></div>
          <?php if (!empty($f['etablissement'])): ?>
            <div class="formation-school"><?= e($f['etablissement']) ?></div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="competences" class="alt-bg">
    <div class="container">
      <div class="section-head reveal">
        <p class="section-eyebrow">Savoir-faire</p>
        <h2 class="section-title">Compétences</h2>
      </div>
      <div class="skills-grid">
        <div class="reveal">
          <p class="skill-subtitle">Techniques</p>
          <div class="skill-tags">
            <?php foreach ($techniques as $t): ?>
              <span class="skill-tag"><?= e($t['libelle']) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="reveal">
          <p class="skill-subtitle">Transversales</p>
          <div class="skill-tags">
            <?php foreach ($transversales as $t): ?>
              <span class="skill-tag"><?= e($t['libelle']) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php if (!empty($loisirs) || !empty($associations)): ?>
  <section id="autres">
    <div class="container">
      <div class="about-grid">
        <?php if (!empty($loisirs)): ?>
        <div class="reveal">
          <p class="section-eyebrow">Hors antenne</p>
          <h2 class="section-title" style="margin-bottom:20px;">Loisirs</h2>
          <div class="pill-list">
            <?php foreach ($loisirs as $l): ?>
              <span class="pill"><?= e($l['libelle']) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($associations)): ?>
        <div class="reveal">
          <p class="section-eyebrow">Engagement</p>
          <h2 class="section-title" style="margin-bottom:20px;">Associations</h2>
          <ul class="assoc-list">
            <?php foreach ($associations as $a): ?>
              <li><?= e($a['libelle']) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <section id="contact" class="alt-bg">
    <div class="container">
      <div class="contact-box reveal">
        <p class="section-eyebrow">Restons en contact</p>
        <h2 class="section-title">Discutons de votre prochain projet média</h2>
        <div class="contact-links">
          <?php if (!empty($profile['email'])): ?>
            <a href="mailto:<?= e($profile['email']) ?>">✉ <?= e($profile['email']) ?></a>
          <?php endif; ?>
          <?php if (!empty($profile['telephone'])): ?>
            <a href="tel:<?= e($profile['telephone']) ?>">☎ <?= e($profile['telephone']) ?></a>
          <?php endif; ?>
          <?php if (!empty($profile['telephone2'])): ?>
            <a href="tel:<?= e($profile['telephone2']) ?>">☎ <?= e($profile['telephone2']) ?></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

</main>

<footer>
  &copy; <?= date('Y') ?> <?= e($profile['nom_complet'] ?? '') ?> — Tous droits réservés ·
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>
