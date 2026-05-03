var countDownDate = new Date("May 23, 2026 00:00:00").getTime();
var x = setInterval(function () {
    var now = new Date().getTime();
    var distance = countDownDate - now;

    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    let countdownDays = document.getElementById("days");
    let countdownHours = document.getElementById("hours");
    let countdownMinutes = document.getElementById("minutes");
    let countdownSeconds = document.getElementById("seconds");

    countdownDays.textContent = days;
    countdownHours.textContent = hours;
    countdownMinutes.textContent = minutes;
    countdownSeconds.textContent = seconds;
    if (distance < 0) {
        clearInterval(x);
        countdownDays.textContent = '00';
        countdownHours.textContent = '00';
        countdownMinutes.textContent = '00';
        countdownSeconds.textContent = '00';
    }
}, 1000);
