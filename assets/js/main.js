document.addEventListener('DOMContentLoaded', () => {

  // --- Nav : ombre au scroll ---
  const nav = document.querySelector('.nav');
  const onScroll = () => {
    if (window.scrollY > 20) nav.classList.add('scrolled');
    else nav.classList.remove('scrolled');
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  // --- Menu mobile ---
  const toggle = document.querySelector('.nav-toggle');
  const links = document.querySelector('.nav-links');
  if (toggle && links) {
    toggle.addEventListener('click', () => links.classList.toggle('open'));
    links.querySelectorAll('a').forEach(a =>
      a.addEventListener('click', () => links.classList.remove('open'))
    );
  }

  // --- Scroll fluide pour les ancres ---
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const target = document.querySelector(a.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // --- Entrée du hero (séquence légère) ---
  const heroEls = document.querySelectorAll('.hero [data-hero]');
  heroEls.forEach((el, i) => {
    setTimeout(() => {
      el.style.transition = 'opacity .8s ease, transform .8s ease';
      el.style.opacity = '1';
      el.style.transform = 'translateY(0) scale(1)';
    }, 150 + i * 140);
  });

  // --- Révélation au scroll (IntersectionObserver) ---
  const revealEls = document.querySelectorAll('.reveal');
  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in-view');
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });
  revealEls.forEach((el, i) => {
    el.style.transitionDelay = (i % 6) * 60 + 'ms';
    io.observe(el);
  });

  // --- Effet tilt léger sur la photo au survol (desktop) ---
  const frame = document.querySelector('.hero-photo-frame');
  if (frame && window.matchMedia('(hover: hover)').matches) {
    frame.addEventListener('mousemove', (e) => {
      const rect = frame.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;
      frame.style.transform = `scale(1.02) rotateY(${x * 6}deg) rotateX(${-y * 6}deg)`;
    });
    frame.addEventListener('mouseleave', () => {
      frame.style.transform = 'scale(1) rotateY(0) rotateX(0)';
    });
  }

  // --- Indicateur de section active dans la nav ---
  const sections = document.querySelectorAll('main section[id]');
  const navLinks = document.querySelectorAll('.nav-links a');
  const navObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        navLinks.forEach(l => l.classList.remove('active'));
        const active = document.querySelector(`.nav-links a[href="#${entry.target.id}"]`);
        if (active) active.classList.add('active');
      }
    });
  }, { threshold: 0.4 });
  sections.forEach(s => navObserver.observe(s));
});
