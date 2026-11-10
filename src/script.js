// Sneltoetsen: 1 = aanvallen, 2 = ontwijken, 3 = repareren
document.addEventListener('keydown', function (event) {
    const buttons = document.querySelectorAll('.actions button');
    const number = parseInt(event.key);

    if (number >= 1 && number <= 3 && buttons.length > 0) {
        buttons[number - 1].click();
    }
});