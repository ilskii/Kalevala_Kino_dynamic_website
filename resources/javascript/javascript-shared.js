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

// Dokumentaatio: https://www.omdbapi.com/ + https://app.readthedocs.org/projects/omdbpy/downloads/pdf/latest/

// OMDb API-tiedot
// Käytetään api-avainta vain paikallisesti, HUOM! Avainta ei saa jakaa GitHubiin!
const apiKey = 'tähän apiKey'; // Määritetään API-avain, jota käytetään OMDb API -kutsuihin
const apiUrl = `http://www.omdbapi.com/?apikey=${apiKey}`; // Muodostetaan API:n perusosoite käyttäen API-avainta

// Hakukentän toiminnallisuus
document.getElementById('movie-search').addEventListener('input', async (event) => { // Lisätään tapahtumankuuntelija HTML-elementille, jonka id on 'movie-search'. Se reagoi käyttäjän syötteeseen (input).
    const query = event.target.value; // Tallennetaan käyttäjän syöttämä hakutermi
    if (query.length > 2) { // Suoritetaan haku vain, jos hakutermi on pidempi kuin 2 merkkiä
        try { // Try-catch-lohko virheiden käsittelyyn. Kaikki sen sisällä oleva koodi suoritetaan, ja jos virhe tapahtuu, se siirtyy catch-lohkoon.
            const response = await fetch(`${apiUrl}&s=${query}`); // Lähetetään API-kutsu hakemaan elokuvia hakutermillä
            if (!response.ok) throw new Error('Verkkovirhe API-kutsussa'); // Annetaan virhe, jos kutsu epäonnistuu
            
            const data = await response.json(); // Muunnetaan saatu vastaus JSON-muotoon. API:t lähettävät usein tietoa JSON-muodossa (JavaScript Object Notation), koska se on kevyt, helppolukuinen ja laajalti käytetty tietorakenne web-sovelluksissa.
            if (data.Search) { // Tarkistetaan, löytyykö hakutuloksia
                const suggestionsElement = document.getElementById('search-suggestions'); // Haetaan HTML-elementti, jonka id on 'search-suggestions', ja tallennetaan se muuttujaan suggestionsElement.
                suggestionsElement.textContent = ''; // Tyhjennetään edelliset hakuehdotukset

                // Lisää löydetyt elokuvat hakuehdotuksiin
                data.Search.forEach(movie => {
                    const option = document.createElement('option'); // Luodaan uusi elementti listaan
                    option.value = movie.Title; // Määritetään elokuvan nimi hakuehdotukseksi
                    suggestionsElement.appendChild(option); // Lisätään hakuehdotus DOM:iin
                });
            }
        } catch (error) {
            console.error('Virhe hakutuloksissa:', error); // Tulostetaan virhe konsoliin, jos haku epäonnistuu
        }
    }
});

// Valitun elokuvan tiedot
document.getElementById('movie-search').addEventListener('change', async (event) => {
    const selectedMovie = event.target.value; // Tallennetaan käyttäjän valitsema elokuvan nimi
    try {
        const response = await fetch(`${apiUrl}&t=${selectedMovie}`); // Lähetetään API-kutsu hakemaan yksityiskohtaiset tiedot valitusta elokuvasta
        if (!response.ok) throw new Error('Verkkovirhe API-kutsussa'); // Heitetään virhe, jos kutsu epäonnistuu
        
        const data = await response.json(); // Muunnetaan saatu vastaus JSON-muotoon
        const searchResultTitle = document.getElementById('search-result-title'); // Haetaan HTML-elementti ja tallennetaan se muuttujaksi, jotta sen sisältöä voidaan muokata myöhemmin
        const searchResultImage = document.querySelector('.search-result-image'); // -''-
        const searchResultText = document.querySelector('.search-result-text'); // -''-

        if (data.Response === "True") { // Tarkistetaan, löytyykö elokuva
            // Päivitetään elokuvan otsikko
            searchResultTitle.textContent = data.Title;

            // Päivitetään elokuvan kansikuva, jos saatavilla
            searchResultImage.src = data.Poster !== "N/A" ? data.Poster : "path/to/default_image.png";
            searchResultImage.alt = `${data.Title} movie poster`;

            // Päivitetään elokuvan lisätiedot (vuosi, genre, juoni)
            searchResultText.textContent = ''; // Tyhjennetään edelliset tiedot
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
            // Näytetään viesti, jos elokuvaa ei löydy
            searchResultTitle.textContent = 'Elokuvaa ei löytynyt';
            searchResultImage.src = 'path/to/default_image.png';
            searchResultImage.alt = 'Oletuskansikuva';
            searchResultText.textContent = 'Ei tietoja saatavilla tästä elokuvasta.';
        }
    } catch (error) {
        console.error('Virhe elokuvan tiedoissa:', error); // Tulostetaan virhe konsoliin
    }
});
