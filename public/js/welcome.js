/* G7KAIH — welcome.js */
(function () {
  'use strict';

  /* ── LOADER ──────────────────────────────────────── */
  const ldr = document.getElementById('ldr');
  const LOGO_CHARS = document.querySelectorAll('.ldr-logo .char');

  // Stagger char animations
  LOGO_CHARS.forEach((c, i) => {
    c.style.animationDelay = `${0.05 + i * 0.06}s`;
  });

  // Dismiss loader after bar finishes (~1.6s)
  setTimeout(() => {
    if (ldr) ldr.classList.add('out');
  }, 1600);


  /* ── CUSTOM CURSOR ───────────────────────────────── */
  const cur  = document.getElementById('cur');
  const curR = document.getElementById('cur-r');
  let mx = -200, my = -200, rx = -200, ry = -200;
  let raf;

  document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; }, { passive: true });

  function animCursor() {
    // Dot: snaps
    if (cur) { cur.style.left = mx + 'px'; cur.style.top = my + 'px'; }
    // Ring: lags
    rx += (mx - rx) * 0.12;
    ry += (my - ry) * 0.12;
    if (curR) { curR.style.left = rx + 'px'; curR.style.top = ry + 'px'; }
    raf = requestAnimationFrame(animCursor);
  }
  animCursor();

  // Hover states
  document.querySelectorAll('a,button,[data-hover]').forEach(el => {
    el.addEventListener('mouseenter', () => document.body.classList.add('cur-hover'));
    el.addEventListener('mouseleave', () => document.body.classList.remove('cur-hover'));
  });

  // Dark sections
  document.querySelectorAll('.flow,.ft').forEach(el => {
    el.addEventListener('mouseenter', () => document.body.classList.add('cur-dark'));
    el.addEventListener('mouseleave', () => document.body.classList.remove('cur-dark'));
  });


  /* ── PARTICLE CANVAS ─────────────────────────────── */
  const cv = document.getElementById('cv');
  if (cv) {
    const ctx = cv.getContext('2d');
    let W, H, particles = [];

    function resize() {
      W = cv.width  = innerWidth;
      H = cv.height = innerHeight;
    }
    resize();
    window.addEventListener('resize', resize, { passive: true });

    function mkParticle() {
      return {
        x: Math.random() * W,
        y: Math.random() * H,
        r: Math.random() * 1.5 + 0.3,
        vx: (Math.random() - 0.5) * 0.18,
        vy: (Math.random() - 0.5) * 0.18,
        o: Math.random() * 0.4 + 0.1,
        t: Math.random() * Math.PI * 2,
        ts: (Math.random() * 0.005 + 0.003)
      };
    }

    for (let i = 0; i < 90; i++) particles.push(mkParticle());

    // Connection lines
    function drawLines() {
      for (let i = 0; i < particles.length; i++) {
        for (let j = i + 1; j < particles.length; j++) {
          const dx = particles[i].x - particles[j].x;
          const dy = particles[i].y - particles[j].y;
          const d = Math.sqrt(dx * dx + dy * dy);
          if (d < 120) {
            ctx.beginPath();
            ctx.strokeStyle = `rgba(14,165,233,${0.06 * (1 - d / 120)})`;
            ctx.lineWidth = 0.5;
            ctx.moveTo(particles[i].x, particles[i].y);
            ctx.lineTo(particles[j].x, particles[j].y);
            ctx.stroke();
          }
        }
      }
    }

    function tickParticles() {
      ctx.clearRect(0, 0, W, H);
      drawLines();

      particles.forEach(p => {
        p.t += p.ts;
        p.x += p.vx + Math.sin(p.t) * 0.05;
        p.y += p.vy + Math.cos(p.t) * 0.05;

        // Wrap
        if (p.x < -10) p.x = W + 10;
        if (p.x > W + 10) p.x = -10;
        if (p.y < -10) p.y = H + 10;
        if (p.y > H + 10) p.y = -10;

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(14,165,233,${p.o})`;
        ctx.fill();
      });

      requestAnimationFrame(tickParticles);
    }
    tickParticles();
  }


  /* ── NAV SCROLL STATE ────────────────────────────── */
  const nav = document.querySelector('.nav');
  window.addEventListener('scroll', () => {
    nav && nav.classList.toggle('on', scrollY > 20);
  }, { passive: true });


  /* ── SCROLL REVEAL ───────────────────────────────── */
  const srs = document.querySelectorAll('.sr');
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
      });
    }, { threshold: 0.07, rootMargin: '0px 0px -36px 0px' });
    srs.forEach(el => io.observe(el));
  } else {
    srs.forEach(el => el.classList.add('in'));
  }


  /* ── TEXT SCRAMBLE on hero h1 ────────────────────── */
  const CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#@!';
  function scramble(el, finalText, delay = 0) {
    let frame = 0;
    const duration = 18; // frames per char
    setTimeout(() => {
      const interval = setInterval(() => {
        el.textContent = finalText
          .split('')
          .map((c, i) => {
            if (c === ' ') return ' ';
            if (frame > i * 3) return c;
            return CHARS[Math.floor(Math.random() * CHARS.length)];
          })
          .join('');
        frame++;
        if (frame > finalText.length * 3 + duration) {
          clearInterval(interval);
          el.textContent = finalText;
        }
      }, 40);
    }, delay);
  }

  // Trigger scramble on accent word after loader
  setTimeout(() => {
    const acc = document.querySelector('.acc-scramble');
    if (acc) {
      const orig = acc.dataset.text;
      scramble(acc, orig, 0);
    }
  }, 2600);


  /* ── COUNTER ANIMATION ───────────────────────────── */
  const counters = document.querySelectorAll('[data-count]');
  const cio = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (!e.isIntersecting) return;
      const el = e.target;
      const target = el.dataset.count;
      const suf = el.dataset.suf || '';
      if (isNaN(parseFloat(target))) { el.textContent = target + suf; return; }
      const end = parseFloat(target), isInt = end % 1 === 0;
      const start = performance.now(), dur = 1400;
      const tick = now => {
        const p = Math.min((now - start) / dur, 1);
        const ease = 1 - Math.pow(1 - p, 4);
        el.textContent = isInt ? Math.round(ease * end) + suf : (ease * end).toFixed(1) + suf;
        if (p < 1) requestAnimationFrame(tick);
      };
      requestAnimationFrame(tick);
      cio.unobserve(el);
    });
  }, { threshold: 0.5 });
  counters.forEach(el => cio.observe(el));


  /* ── MAGNETIC BUTTONS ────────────────────────────── */
  document.querySelectorAll('.btn-h, .btn-p, .btn-cw, .btn-co').forEach(btn => {
    btn.addEventListener('mousemove', e => {
      const r = btn.getBoundingClientRect();
      const dx = e.clientX - (r.left + r.width / 2);
      const dy = e.clientY - (r.top + r.height / 2);
      btn.style.transform = `translate(${dx * 0.18}px, ${dy * 0.22}px) translateY(-3px)`;
    });
    btn.addEventListener('mouseleave', () => {
      btn.style.transform = '';
    });
  });


  /* ── HABIT CARDS: update big number ─────────────── */
  const bigNum = document.querySelector('.hbig');
  if (bigNum) {
    document.querySelectorAll('.hc').forEach((card, i) => {
      card.addEventListener('mouseenter', () => { bigNum.textContent = `0${i + 1}`; });
    });
    document.querySelectorAll('.hc').forEach(card => {
      card.addEventListener('mouseleave', () => { bigNum.textContent = '01'; });
    });
  }


  /* ── PARALLAX on hero blob/rings ────────────────── */
  const blob = document.querySelector('.h-blob');
  const glow = document.querySelector('.h-glow');
  window.addEventListener('scroll', () => {
    const y = scrollY;
    if (blob) blob.style.transform = `translateY(calc(-50% + ${y * 0.12}px))`;
    if (glow) glow.style.transform = `translateY(${y * 0.08}px)`;
  }, { passive: true });

})();