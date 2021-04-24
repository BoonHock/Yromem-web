// https://css-tricks.com/how-to-create-an-animated-countdown-timer-with-html-css-and-javascript/

const FULL_DASH_ARRAY = 283;
const WARNING_THRESHOLD = 10;
const ALERT_THRESHOLD = 5;

const COLOR_CODES = {
    info: {
        color: "green"
    },
    warning: {
        color: "orange",
        threshold: WARNING_THRESHOLD
    },
    alert: {
        color: "red",
        threshold: ALERT_THRESHOLD
    }
};

class CountdownTimer {
    // const TIME_LIMIT = 30;
    // let timeLeft = TIME_LIMIT;
    // let timerInterval = null;
    // let remainingPathColor = COLOR_CODES.info.color;

    constructor(time_limit_seconds, times_up_callback) {
        this.time_limit = time_limit_seconds;
        this.times_up_callback = times_up_callback;

        this.resetTimer();
    }

    stopTimer() {
        clearInterval(this.timerInterval);
    }

    resetTimer() {
        this.stopTimer();

        this.timeLeft = this.time_limit; // reset time left
        this.timerInterval = null;
        this.remainingPathColor = COLOR_CODES.info.color;

        document.getElementById("countdown_timer").innerHTML = `
            <div class="base-timer">
            <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <g class="base-timer__circle">
            <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
            <path
            id="base-timer-path-remaining"
            stroke-dasharray="283"
            class="base-timer__path-remaining ${this.remainingPathColor}"
            d="
            M 50, 50
            m -45, 0
            a 45,45 0 1,0 90,0
            a 45,45 0 1,0 -90,0
            "
            ></path>
            </g>
            </svg>
            <span id="base-timer-label" class="base-timer__label">${this.formatTime(this.time_limit)}</span>
            </div>
            `;
        this.setCircleDasharray();
        this.setRemainingPathColor();
    }

    restartTimer() {
        this.resetTimer();
        this.startTimer();
    }

    startTimer() {
        var timerObj = this;

        this.timerInterval = setInterval(() => {
            timerObj.timeLeft -= 1;

            document.getElementById("base-timer-label").innerHTML = timerObj.formatTime(
                timerObj.timeLeft
            );
            timerObj.setCircleDasharray();
            timerObj.setRemainingPathColor(timerObj.timeLeft);

            if (timerObj.timeLeft === 0) {
                timerObj.onTimesUp();
            }
        }, 1000);
    }

    formatTime(time) {
        const minutes = Math.floor(time / 60);
        let seconds = time % 60;

        if (seconds < 10) {
            seconds = `0${seconds}`;
        }

        return `${minutes}:${seconds}`;
    }

    setRemainingPathColor(timeLeft) {
        const { alert, warning, info } = COLOR_CODES;
        if (this.timeLeft <= alert.threshold) {
            document
                .getElementById("base-timer-path-remaining")
                .classList.remove(warning.color);
            document
                .getElementById("base-timer-path-remaining")
                .classList.add(alert.color);
        } else if (timeLeft <= warning.threshold) {
            document
                .getElementById("base-timer-path-remaining")
                .classList.remove(info.color);
            document
                .getElementById("base-timer-path-remaining")
                .classList.add(warning.color);
        }
    }

    calculateTimeFraction() {
        const rawTimeFraction = this.timeLeft / this.time_limit;
        return rawTimeFraction - (1 / this.time_limit) * (1 - rawTimeFraction);
    }

    setCircleDasharray() {
        const circleDasharray = `${(
            this.calculateTimeFraction() * FULL_DASH_ARRAY
        ).toFixed(0)} 283`;
        document
            .getElementById("base-timer-path-remaining")
            .setAttribute("stroke-dasharray", circleDasharray);
    }
    onTimesUp() {
        this.stopTimer();
        if (this.times_up_callback != null) {
            this.times_up_callback();
        }
    }
}
