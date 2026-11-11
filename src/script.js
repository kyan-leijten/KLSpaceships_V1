// Sneltoetsen: 1 = aanvallen, 2 = ontwijken, 3 = repareren
// Zorgt dat herladen (F5) de pagina opnieuw opent in plaats van het formulier
// opnieuw te versturen. Zo krijg je geen "Formulier opnieuw verzenden?"-melding
// en reset het spel echt.
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

document.addEventListener('keydown', function (event) {
    const buttons = document.querySelectorAll('.actions button');
    const number = parseInt(event.key);

    if (number >= 1 && number <= 3 && buttons.length > 0) {
        buttons[number - 1].click();
    }
});