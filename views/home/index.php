<?php
$this->Title = 'Головна сторінка';
?>

<style>
  body {
    background-color: #121212;
    color: #e0e0e0;
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-size: cover;
    background-position: center center;
    background-attachment: fixed;
  }

  .nav-menu a {
    margin-left: 20px;
    color: #f5f5f5;
    text-decoration: none;
    font-size: 18px;
    transition: color 0.3s ease;
  }

  .nav-menu a:hover {
    color: #ffbc00;
  }

  .hero {
    position: relative;
    min-height: 70vh;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    background: linear-gradient(135deg, 
      rgba(32, 37, 55, 0.95) 0%,
      rgba(41, 50, 78, 0.95) 50%,
      rgba(32, 37, 55, 0.95) 100%),
      url('/site/assets/images/foto123.jpg') center center/cover no-repeat;
    overflow: hidden;
  }

  .hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
      radial-gradient(circle at 20% 50%, rgba(76, 110, 245, 0.1) 0%, transparent 50%),
      radial-gradient(circle at 80% 50%, rgba(94, 114, 235, 0.1) 0%, transparent 50%);
    animation: gradientShift 15s ease infinite;
  }

  @keyframes gradientShift {
    0% {
      transform: scale(1);
      opacity: 0.5;
    }
    50% {
      transform: scale(1.2);
      opacity: 0.7;
    }
    100% {
      transform: scale(1);
      opacity: 0.5;
    }
  }

  .hero-content {
    position: relative;
    z-index: 1;
    padding: 2rem;
    max-width: 1300px;
  }

  .hero-content h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: none;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.8s ease forwards;
  }

  .hero-content p {
    font-size: 1.5rem;
    color: #b8c2cc;
    margin-bottom: 2rem;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.8s ease forwards 0.2s;
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .info-section {
    position: relative;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    padding: 4rem 10%;
    background: linear-gradient(180deg, 
      rgba(32, 37, 55, 0.95) 0%,
      rgba(25, 28, 41, 0.95) 100%);
  }

  .info-card {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    padding: 2rem;
    border-radius: 16px;
    text-align: center;
    transition: all 0.3s ease;
  }

  .info-card:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.05);
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
  }

  .info-card h2 {
    font-size: 1.75rem;
    color: #ffd700;
    margin-bottom: 1rem;
  }

  .info-card p {
    font-size: 1.1rem;
    color: #b8c2cc;
    line-height: 1.6;
  }

  footer {
    background-color: #1a1a1a;
    color: #cccccc;
    text-align: center;
    padding: 12px 0;
    margin-top: 45px;
    width: 100%;
    border-top: 1px solid #333;
  }

  @media (max-width: 768px) {
    .hero-content h1 {
      font-size: 2.5rem;
    }

    .hero-content p {
      font-size: 1.25rem;
    }

    .info-section {
      padding: 2rem 5%;
    }
  }

  #promoSlider .carousel-control-prev, #promoSlider .carousel-control-next {
    width: 60px;
    height: 60px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent !important;
    border-radius: 50%;
    border: 2px solid #222;
    opacity: 0.92;
    transition: opacity 0.2s, background 0.2s, border 0.2s;
    box-shadow: 0 2px 12px rgba(0,0,0,0.10);
    z-index: 2;
  }
  #promoSlider .carousel-control-prev:hover, #promoSlider .carousel-control-next:hover {
    opacity: 1;
    background: rgba(34,34,34,0.08) !important;
    border-color: #111;
  }
  #promoSlider .carousel-control-prev-icon, #promoSlider .carousel-control-next-icon {
    filter: none;
    width: 2.5rem;
    height: 2.5rem;
    background-color: transparent !important;
    background-image: none !important;
    mask-image: none !important;
    color: #222;
    border-radius: 50%;
    box-shadow: none;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  #promoSlider .carousel-control-prev-icon::after, #promoSlider .carousel-control-next-icon::after {
    content: '';
  }
  #promoSlider .carousel-control-prev svg, #promoSlider .carousel-control-next svg {
    color: #222;
    width: 2.5rem;
    height: 2.5rem;
  }
</style>

<main>
  <section class="hero">
    <div class="hero-content">
    
      <h1>Ласкаво просимо до магазину ТехОк</h1>
     
      <img src="/site/assets/img/logo.png" alt="logo">
      <p>Ваше місце для розумних покупок.</p>
    </div>
 
  </section>

  <?php if (!empty($promoProducts)): ?>
    <section class="promo-section" style="background: #181a20; padding: 4.5rem 0;">
      <div class="container">
        <h2 class="text-center mb-4" style="color:#ffd700; font-size:3.2rem; font-weight:900;">Акційні товари</h2>
        <div id="promoSlider" class="carousel slide mx-auto" data-bs-ride="carousel" data-bs-interval="4000" style="max-width: 900px;">
          <div class="carousel-inner">
            <?php foreach ($promoProducts as $i => $product): ?>
              <div class="carousel-item<?= $i === 0 ? ' active' : '' ?>">
                <img src="<?= htmlspecialchars($product['ImageURL']) ?>" class="d-block w-100 rounded bg-white" alt="<?= htmlspecialchars($product['ProductName']) ?>" style="max-height:540px; object-fit:contain; background:#fff; padding:36px;">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-4 mt-2" style="font-size:1.7rem;">
                  <h5 style="font-size:2rem; font-weight:800; text-shadow:0 2px 8px #000;"><?= htmlspecialchars($product['ProductName']) ?></h5>
                  <p class="mb-1" style="font-size:1.5rem; font-weight:600; text-shadow:0 2px 8px #000;"><?= number_format($product['Price'], 2, ',', ' ') ?> ₴</p>
                  <a href="/site/product/detail?id=<?= $product['ProductID'] ?>" class="btn btn-warning btn-xl" style="font-size:1.3rem; font-weight:700; padding:0.75rem 2.5rem;">Детальніше</a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#promoSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Попередній</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#promoSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Наступний</span>
          </button>
        </div>
        <div class="text-center mt-5">
          <span class="badge bg-danger fs-3 p-4" style="font-size:2rem;">Поспішайте! Акції обмежені у часі!</span>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <section class="info-section">
    <div class="info-card">
      <h2>Широкий вибір</h2>
      <p>У нас ви знайдете товари на будь-який смак.</p>
    </div>
    <div class="info-card">
      <h2>Якість</h2>
      <p>Ми пропонуємо тільки перевірені та якісні продукти.</p>
    </div>
    <div class="info-card">
      <h2>Підтримка</h2>
      <p>Наша команда завжди готова допомогти вам.</p>
    </div>
  </section>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var promoSlider = document.getElementById('promoSlider');
    if (promoSlider) {
      var carousel = bootstrap.Carousel.getOrCreateInstance(promoSlider, { interval: 4000, ride: 'carousel' });
      setInterval(function() {
        carousel.next();
      }, 4000);
    }


    function addArrowIcon(btn, direction) {
      if (!btn.querySelector('svg')) {
        btn.innerHTML = `<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="16" cy="16" r="15" stroke="#222" stroke-width="2" fill="none"/>
          <polyline points="${direction === 'prev' ? '19,10 13,16 19,22' : '13,10 19,16 13,22'}" fill="none" stroke="#222" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>`;
      }
    }
    var prevBtn = document.querySelector('#promoSlider .carousel-control-prev');
    var nextBtn = document.querySelector('#promoSlider .carousel-control-next');
    if (prevBtn) addArrowIcon(prevBtn, 'prev');
    if (nextBtn) addArrowIcon(nextBtn, 'next');
  });
</script>