<script>
import Chart from "chart.js/auto";
import axios from "axios"; // Import Axios untuk melakukan permintaan HTTP

export default {
  name: "gradient-line-chart",

  props: {
    title: {
      type: String,
      default: "Grafik Penyewaan",
    },
    detail1: {
      type: String,
      default: " Penyewaan Mobil",
    },
    detail2: {
      type: String,
      default: "",
    },
  },

  mounted() {
    var ctx1 = document.getElementById("chart-line").getContext("2d");

    axios.get("http://127.0.0.1:8000/api/get-transaction")
      .then(response => {
        const responseData = response.data; // Data dari respons API

        if (responseData.success && Array.isArray(responseData.data)) {
          const data = responseData.data; // Ambil array data transaksi peminjaman

          const labels = data.map(item => item.bulan);
          const values = data.map(item => item.jumlah_transaksi);

          var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
          gradientStroke1.addColorStop(1, "rgba(94, 114, 228, 0.2)");
          gradientStroke1.addColorStop(0.2, "rgba(94, 114, 228, 0.0)");
          gradientStroke1.addColorStop(0, "rgba(94, 114, 228, 0)");

          new Chart(ctx1, {
            type: "line",
            data: {
              labels: labels,
              datasets: [
                {
                  label: "Transaksi",
                  tension: 0.4,
                  // borderWidth: 0,
                  pointRadius: 0,
                  borderColor: "#4BB543 ",
                  backgroundColor: gradientStroke1,
                  borderWidth: 3,
                  fill: true,
                  data: values,
                  maxBarThickness: 6,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: false,
                },
              },
              interaction: {
                intersect: false,
                mode: "index",
              },
              scales: {
                y: {
                  grid: {
                    drawBorder: false,
                    display: true,
                    drawOnChartArea: true,
                    drawTicks: false,
                    borderDash: [5, 5],
                  },
                  ticks: {
                    display: true,
                    padding: 10,
                    color: "#fbfbfb",
                    font: {
                      size: 11,
                      family: "Open Sans",
                      style: "normal",
                      lineHeight: 2,
                    },
                  },
                },
                x: {
                  grid: {
                    drawBorder: false,
                    display: false,
                    drawOnChartArea: false,
                    drawTicks: false,
                    borderDash: [5, 5],
                  },
                  ticks: {
                    display: true,
                    color: "#ccc",
                    padding: 20,
                    font: {
                      size: 11,
                      family: "Open Sans",
                      style: "normal",
                      lineHeight: 2,
                    },
                  },
                },
              },
            },
          });
        } else {
          console.error("Data from API is not in the expected format:", responseData);
        }
      })
      .catch(error => {
        console.error("Error fetching data from API:", error);
      });
  },
};
</script>

<template>
  <div class="card">
    <div class="pb-0 card-header mb-0">
      <h6>{{ title }}</h6>
      <p class="text-sm">
        <i class="fa fa-arrow-up text-success"></i>
        <span class="font-weight-bold">{{ detail1 }}</span>
        {{ detail2 }}
      </p>
    </div>
    <div class="p-3 card-body">
      <div class="chart">
        <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
      </div>
    </div>
  </div>
</template>
