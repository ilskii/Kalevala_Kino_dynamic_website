// Top-navin hakukentän toiminnallisuus ja top-navin nykyisen sivun alleviivaus
document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('nav-searchBar').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const resultsList = document.getElementById('resultsList');
        resultsList.innerHTML = '';

        if (query) {
            const results = [
                // Etusivu
                { name: 'Suosituimmat elokuvat', id: 'top-films-title', url: '../../pages/01-etusivu/etusivu.html' },
                { name: 'Ajankohtaista', id: 'news-title', url: '../../pages/01-etusivu/etusivu.html' },
                // Elokuvat
                { name: 'Elokuvat ja näytökset', id: 'otsikko', url: '../../pages/02-elokuvat/varaus.html' },
                { name: 'Leffaeväät', id: 'leffaevaat', url: '../../pages/02-elokuvat/varaus.html' },
                // Elokuvawiki
                { name: 'Elokuvawiki', id: 'movie-searchbar-container', url: '../../pages/03-elokuvawiki/elokuvawiki.html' },
                // Elokuvakerho
                { name: 'Elokuvakerho', id: 'kerho-container', url: '../../pages/04-elokuvakerho/elokuvakerho.html' },
                { name: 'Elokuvaraati', id: 'elokuvaraati', url: '../../pages/04-elokuvakerho/elokuvakerho.html' },
                // Tietoa meistä
                { name: 'Tietoa meistä', id: 'about-content', url: '../../pages/05-tietoameista/tietoameista.html' },
                { name: 'Yhteystiedot', id: 'address', url: '../../pages/05-tietoameista/tietoameista.html' },
                // Rekisteröidy
                { name: 'Rekisteröidy', id: 'register-container', url: '../../pages/06-rekisteroityminen/rekisteroityminen.html' },
                /* Lisää omat sivut samaan tyyliin:
                Name: miltä hakutulos näyttää
                id: HTML koodista ID, mihin haluat että hakutulos vie
                url: millä sivulla kyseinen hakutulos on */
        ];

            const filteredResults = results.filter(result => result.name.toLowerCase().includes(query));

            filteredResults.forEach(result => {
                const li = document.createElement('li');
                li.textContent = result.name;
                li.addEventListener('click', function() {
                    if (result.url === window.location.pathname) {
                        // Scrollaa oikeaan kohtaan sivulla hakutuloksen mukaan
                        scrollToElement(result.id);
                    } else {
                        // Navigoi toiselle sivulle hakutuloksen mukaan
                        window.location.href = `${result.url}#${result.id}`;
                    }
                    resultsList.style.display = 'none';
                    document.getElementById('nav-searchBar').value = '';
                });
                resultsList.appendChild(li);
            });

            resultsList.style.display = 'block';
        } else {
            resultsList.style.display = 'none';
        }
    });

    // Tämä scrollaa hakutulokseen sivunvaihdon jälkeen
    window.addEventListener('load', () => {
        if (window.location.hash) {
            const elementId = window.location.hash.substring(1);
            scrollToElement(elementId);
        }
    });

    // Tämä scrollaa hakutulokseen siten, että tulos tulee kunnolla esiin
    function scrollToElement(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            const offset = 100; // Muuta arvoa tarpeen mukaan
            const elementPosition = element.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - offset;
            window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
        }
    }  
});

// Aktiivinen sivu näkyy navigaatiopalkissa alleviivattuna (+ CSS-muotoilu.)

document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.top-nav a');
    
    navLinks.forEach(link => {
        if (link.href === window.location.href) {
            link.classList.add('active');
        }
    });
});

// Kuvakaruselli-scripti

let currentSlide = 0;

function changeSlide(direction) {
    const slides = document.querySelectorAll(".carousel-slides .slide");
    const totalSlides = slides.length;

    // Piilota nykyinen dia
    slides[currentSlide].style.display = "none";

    // Päivitä nykyinen dia
    currentSlide = (currentSlide + direction + totalSlides) % totalSlides;

    // Näytä seuraava dia
    slides[currentSlide].style.display = "block";
}

function reserveMeal(mealName) {
    alert(`Olet varannut: ${mealName}`);
}

// Näytä vain ensimmäinen dia aluksi
document.addEventListener("DOMContentLoaded", () => {
    const slides = document.querySelectorAll(".carousel-slides .slide");
    slides.forEach((slide, index) => {
        slide.style.display = index === 0 ? "block" : "none";
    });
});

// Elokuva tietokannan API

// OMDb API-tiedot
const apiKey = '989bb24e';
const apiUrl = 'http://www.omdbapi.com/?i=tt3896198&apikey=989bb24e';


// Hakukentän toiminnallisuus
document.getElementById('movie-search').addEventListener('input', async (event) => {
    const query = event.target.value; // Käyttäjän hakutermi
    if (query.length > 2) { // Suorita haku vain, jos hakutermi on pidempi kuin 2 merkkiä
        const response = await fetch(`${apiUrl}?apikey=${apiKey}&s=${query}`);
        const data = await response.json();

        if (data.Search) {
            const suggestionsElement = document.getElementById('search-suggestions');
            suggestionsElement.textContent = ''; // Tyhjennä edelliset hakuehdotukset

            // Lisää elokuvat hakuehdotuksiin
            data.Search.forEach(movie => {
                const option = document.createElement('option');
                option.value = movie.Title; // Elokuvan otsikko
                suggestionsElement.appendChild(option);
            });
        }
    }
});

// Valitun elokuvan tiedot
document.getElementById('movie-search').addEventListener('change', async (event) => {
    const selectedMovie = event.target.value; // Käyttäjän valitsema elokuva
    const response = await fetch(`${apiUrl}?apikey=${apiKey}&t=${selectedMovie}`);
    const data = await response.json();

    const searchResultTitle = document.getElementById('search-result-title');
    const searchResultImage = document.querySelector('.search-result-image');
    const searchResultText = document.querySelector('.search-result-text');

    if (data.Response === "True") {
        // Päivitä elokuvan otsikko
        searchResultTitle.textContent = data.Title;

        // Päivitä elokuvan kansikuva
        searchResultImage.src = data.Poster !== "N/A" ? data.Poster : "path_to_default_image.png";
        searchResultImage.alt = `${data.Title} movie poster`;

        // Päivitä elokuvan tiedot (vuosi, genre, juoni)
        searchResultText.textContent = ''; // Tyhjennä edelliset tiedot
        const year = document.createElement('p');
        year.textContent = `Year: ${data.Year}`;
        const genre = document.createElement('p');
        genre.textContent = `Genre: ${data.Genre}`;
        const plot = document.createElement('p');
        plot.textContent = `Plot: ${data.Plot}`;

        searchResultText.appendChild(year);
        searchResultText.appendChild(genre);
        searchResultText.appendChild(plot);
    } else {
        // Näytä viesti, jos elokuvaa ei löydy
        searchResultTitle.textContent = 'Movie Not Found';
        searchResultImage.src = 'path_to_default_image.png';
        searchResultImage.alt = 'Default movie poster';
        searchResultText.textContent = 'No details available for this movie.';
    }
});

