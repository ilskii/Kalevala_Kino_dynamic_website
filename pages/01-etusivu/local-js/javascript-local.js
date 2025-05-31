document.addEventListener("DOMContentLoaded", function () {
  // Haetaan siirrettävä elementti, joka sisältää kaikki kuvat
  const slider = document.querySelector('.slide');
  
  // Haetaan kaikki kuvat sliderin sisällä
  let slides = slider.querySelectorAll('img');

  // Kloonataan ensimmäinen ja viimeinen kuva, jotta looppaava efekti saadaan aikaan
  const firstClone = slides[0].cloneNode(true);
  const lastClone = slides[slides.length - 1].cloneNode(true);

  // Lisätään kloonatut kuvat: 
  //   - lastClone lisätään sliderin alkuun, 
  //   - firstClone lisätään sliderin loppuun
  slider.insertBefore(lastClone, slides[0]);
  slider.appendChild(firstClone);

  // Päivitetään slides–lista sisältämään myös kloonatut kuvat
  slides = slider.querySelectorAll('img');

  // Asetetaan currentIndex niin, että ensimmäinen alkuperäinen kuva näkyy.
  // Nyt sliderin ensimmäinen elementti on kloonattu viimeinen kuva, joten 
  // currentIndex = 1 näyttää alkuperäisen ensimmäisen kuvan.
  window.currentIndex = 1;

  // Lasketaan yhden kuvan (sisältäen marginaalit) kokonaisleveys.
  // Oletetaan, että kaikilla kuvilla on sama leveys ja marginaalit.
  const slideStyle = getComputedStyle(slides[0]);
  const slideWidth = slides[0].offsetWidth;
  const marginLeft = parseInt(slideStyle.marginLeft) || 0;
  const marginRight = parseInt(slideStyle.marginRight) || 0;
  window.totalSlideWidth = slideWidth + marginLeft + marginRight;

  // Asetetaan aluksi oikea transformaatiotila, jotta ensimmäinen alkuperäinen kuva tulee näkyviin.
  slider.style.transition = "none";
  slider.style.transform = `translateX(-${window.currentIndex * window.totalSlideWidth}px)`;

  // Kun transition (siirto) loppuu, tarkistetaan, onko saavutettu kloonattua kuvaa
  slider.addEventListener("transitionend", function () {
    // Jos päästään "loppuun", eli ensimmäinen kloonattu (ensimmäisen kuvan klooni) on näkyvissä
    if (window.currentIndex === slides.length - 1) {
      slider.style.transition = "none"; // Poistetaan animoitu siirto tilapäisesti
      window.currentIndex = 1; // Palataan alkuperäiseen ensimmäiseen kuvaan
      slider.style.transform = `translateX(-${window.currentIndex * window.totalSlideWidth}px)`;
    }
    // Jos päästään "alkuun", eli viimeinen kloonattu (viimeisen kuvan klooni) on näkyvissä
    if (window.currentIndex === 0) {
      slider.style.transition = "none";
      window.currentIndex = slides.length - 2; // Asetetaan indeksi viimeiseksi alkuperäiseksi kuvaksi
      slider.style.transform = `translateX(-${window.currentIndex * window.totalSlideWidth}px)`;
    }
  });

  // Määritellään globaali funktio changeSlide(n), jota HTML:n inline–onclick–attribuutit kutsuvat
  window.changeSlide = function (n) {
    window.currentIndex += n;
    slider.style.transition = "transform 0.5s ease-in-out";
    slider.style.transform = `translateX(-${window.currentIndex * window.totalSlideWidth}px)`;
  };
});
