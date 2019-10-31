<template>
    <div>
        <div class="time">
            <div>{{hours}}:{{minutes}}:{{seconds}}</div>
        </div>
        <div class="date">
            <div>{{ date }}</div>
        </div>
    </div>
</template>
<script>
import moment from 'moment'


function padZero(number) {
  if (number < 10) {
    return "0" + number;
  }
  return number;
}

function zeroPadding(num, digit) {
    var zero = '';
    for(var i = 0; i < digit; i++) {
        zero += '0';
    }
    return (zero + num).slice(-digit);
}

const getDate = () => new Date();

const getSeconds = () => padZero(getDate().getSeconds());
const getMinutes = () => padZero(getDate().getMinutes());
const getHour = () => getDate().getHours();

let week = ['NED', 'PON', 'UTO', 'SRE', 'ČET', 'PET', 'SUB'];
let cd = new Date()
const getDatum = () =>  zeroPadding(cd.getDate(), 2)  + '.' + zeroPadding(cd.getMonth()+1, 2)  + '.' + zeroPadding(cd.getFullYear(), 4) + ' ' + week[cd.getDay()];
export default {
  name: "vue-digital-clock",
  data() {
    return {
      ticker: null,
      date: getDatum(),
      minutes: getMinutes(),
      hours: getHour(),
      seconds: getSeconds(),
    };
  },
  created() {
    this.ticker = setInterval(() => {
      this.minutes = getMinutes();
      this.hours = getHour(this.twelveHour);
      this.seconds = getSeconds();
    }, 1000);
  },
  destroyed() {
    clearInterval(this.ticker);
  }
};
</script>
