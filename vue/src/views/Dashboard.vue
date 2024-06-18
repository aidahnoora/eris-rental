<template>
  <div class="py-4 container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="row">
          <div class="col-lg-3 col-md-6 col-12">
            <card
              :title="stats.money.title"
              :value="stats.money.value"
              :percentage="stats.money.percentage"
              :iconClass="stats.money.iconClass"
              :iconBackground="stats.money.iconBackground"
              :detail="stats.money.detail"
              directionReverse
            ></card>
          </div>
          <div class="col-lg-3 col-md-6 col-12">
            <card
              :title="stats.users.title"
              :value="stats.users.value"
              :percentage="stats.users.percentage"
              :iconClass="stats.users.iconClass"
              :iconBackground="stats.users.iconBackground"
              :detail="stats.users.detail"
              directionReverse
            ></card>
          </div>
          <div class="col-lg-3 col-md-6 col-12">
            <card
              :title="stats.clients.title"
              :value="stats.clients.value"
              :percentage="stats.clients.percentage"
              :iconClass="stats.clients.iconClass"
              :iconBackground="stats.clients.iconBackground"
              :percentageColor="stats.clients.percentageColor"
              :detail="stats.clients.detail"
              directionReverse
            ></card>
          </div>
          <div class="col-lg-3 col-md-6 col-12">
            <card
              :title="stats.sales.title"
              :value="stats.sales.value"
              :percentage="stats.sales.percentage"
              :iconClass="stats.sales.iconClass"
              :iconBackground="stats.sales.iconBackground"
              :detail="stats.sales.detail"
              directionReverse
            ></card>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <!-- line chart -->
            <div class="card z-index-2">
              <gradient-line-chart />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Card from "../examples/Cards/Card.vue";
import GradientLineChart from "../examples/Charts/GradientLineChart.vue";
import api from "../views/api/index.js";

export default {
  name: "dashboard",
  data() {
    return {
      stats: {
        money: {
          title: "Merk Mobil",
          value: "",
          iconClass: "ni ni-money-coins",
          iconBackground: "bg-gradient-primary",
        },
        users: {
          title: "Data Mobil",
          value: "",
          iconClass: "ni ni-world",
          iconBackground: "bg-gradient-danger",
        },
        clients: {
          title: "Customer",
          value: "",
          iconClass: "ni ni-paper-diploma",
          iconBackground: "bg-gradient-success",
        },
        sales: {
          title: "User",
          value: "",
          iconClass: "ni ni-cart",
          iconBackground: "bg-gradient-warning",
        },
      },
    };
  },
  mounted() {
    // Ambil data dari controller saat komponen dimuat
    this.fetchData();
  },
  methods: {
    async fetchData() {
      try {
        const response = await api.get(
          "/api/get-categories"
        );
        this.stats.money.value = response.data.data.categories; // Menggunakan data categories
        this.stats.users.value = response.data.data.books;
        this.stats.clients.value = response.data.data.members;
        this.stats.sales.value = response.data.data.raks;
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },
  },
  components: {
    Card,
    GradientLineChart,
  },
};
</script>
