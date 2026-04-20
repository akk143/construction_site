console.log("JS Loaded");
const loginPopup = document.getElementById("login-popup");
const registerPopup = document.getElementById("register-popup");

const showLoginBtn = document.getElementById("show-login");
const showRegisterBtn = document.getElementById("show-register");

// Show Login popup
showLoginBtn.addEventListener("click", () => {
    registerPopup.classList.remove("active");
    loginPopup.classList.add("active");
});

// Show Register popup
showRegisterBtn.addEventListener("click", () => {
    loginPopup.classList.remove("active");
    registerPopup.classList.add("active");
});

// Close buttons (X)
document.querySelectorAll(".close-btn").forEach(btn => {
    btn.addEventListener("click", () => {
        loginPopup.classList.remove("active");
        registerPopup.classList.remove("active");
    });
});

// Switch from Login → Register
document.getElementById("go-register").addEventListener("click", (e) => {
    e.preventDefault();
    loginPopup.classList.remove("active");
    registerPopup.classList.add("active");
});

// Switch from Register → Login
document.getElementById("go-login").addEventListener("click", (e) => {
    e.preventDefault();
    registerPopup.classList.remove("active");
    loginPopup.classList.add("active");
});




// PROGRESSBAR
const allProgress = document.querySelectorAll('main .card .progress');

allProgress.forEach(item=> {
	item.style.setProperty('--value', item.dataset.value)
})

// APEXCHART
var options = {
  series: [{
  name: 'series1',
  data: [31, 40, 28, 51, 42, 109, 100]
}, {
  name: 'series2',
  data: [11, 32, 45, 32, 34, 52, 41]
}],
  chart: {
  height: 350,
  type: 'area'
},
dataLabels: {
  enabled: false
},
stroke: {
  curve: 'smooth'
},
xaxis: {
  type: 'datetime',
  categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
},
tooltip: {
  x: {
    format: 'dd/MM/yy HH:mm'
  },
},
};

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();

// line graph
const ctx = document.getElementById('lineChart');

const myChart= new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      datasets: [{
        label: 'Yearly Earnings in MMK',
        data: [2050,1900,2100,1800,2800,2000,2500,2600,2450,1950,2300,2900],
		backgroundColor: [
			'rgba(85,85,85,1'
		],
		borderColor: [
			'rgb(41,155,99)'
		],
        borderWidth: 1
      }]
    },
    options: {
      responsive:true
    }
  });