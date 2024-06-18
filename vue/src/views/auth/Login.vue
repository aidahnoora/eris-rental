<template>
  <main class="mt-0 main-content">
    <section>
      <div class="page-header min-vh-100">
        <div v-if="loginFailed" class="alert alert-danger">
          Email atau Password Anda salah.
        </div>
        <div class="container">
          <div class="row">
            <div class="col-md-6 offset-md-3">
              <div class="card">
                <div class="pb-0 card-header text-start">
                  <br /><br />
                  <h4 class="font-weight-bolder text-center">
                    <img
                      :src="logo"
                      class="navbar-brand-img h-100"
                      alt="main_logo"
                      width="100"
                      style="border-radius: 100%"
                    />
                    <br /><br />
                    Sistem Informasi Eris Rental Mobil <br />
                    Login
                  </h4>
                </div>
                <div class="card-body">
                  <form @submit.prevent="login">
                    <div class="mb-3">
                      <input
                        type="email"
                        placeholder="Email"
                        class="form-control"
                        name="email"
                        v-model="user.email"
                      />
                      <div
                        v-if="validation.email"
                        class="mt-2 alert alert-danger"
                      >
                        Masukkan Email
                      </div>
                    </div>

                    <div class="mb-3">
                      <input
                        type="password"
                        placeholder="Password"
                        class="form-control"
                        name="password"
                        v-model="user.password"
                      />
                      <div
                        v-if="validation.password"
                        class="mt-2 alert alert-danger"
                      >
                        Masukkan Password
                      </div>
                    </div>
                    <argon-switch id="rememberMe">Remember me</argon-switch>

                    <div class="text-center">
                      <button
                        class="btn bg-gradient-success mt-4 w-100 btn-lg"
                        type="submit"
                      >
                        Sign in
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>

<script>
import ArgonSwitch from "@/components/ArgonSwitch.vue";
import Swal from "sweetalert2";
import logo from "@/assets/img/logo.png";
import api from "../../views/api/index.js";

const body = document.getElementsByTagName("body")[0];

export default {
  name: "login",
  components: {
    ArgonSwitch,
  },
  created() {
    this.$store.state.hideConfigButton = true;
    this.$store.state.showNavbar = false;
    this.$store.state.showSidenav = false;
    this.$store.state.showFooter = false;
    body.classList.remove("bg-gray-100");
  },
  beforeUnmount() {
    this.$store.state.hideConfigButton = false;
    this.$store.state.showNavbar = true;
    this.$store.state.showSidenav = true;
    this.$store.state.showFooter = true;
    body.classList.add("bg-gray-100");
  },
  data() {
    return {
      logo,
      loggedIn: localStorage.getItem("loggedIn"),
      token: localStorage.getItem("token"),
      user: [],
      validation: [],
      loginFailed: null,
    };
  },
  methods: {
    login() {
      if (this.user.email && this.user.password) {
        api
          .get("/sanctum/csrf-cookie")
          .then((response) => {
            console.log(response);

            api
              .post("/api/login", {
                email: this.user.email,
                password: this.user.password,
              })
              .then((res) => {
                console.log(res);

                if (res.data.success) {
                  localStorage.setItem("loggedIn", "true");
                  localStorage.setItem("token", res.data.token);

                  this.loggedIn = true;

                  // return this.$router.push({ path: "dashboard" });
                  Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: "Login berhasil!",
                  }).then(() => {
                    this.$router.push({ name: "Dashboard" });
                  });
                } else {
                  // this.loginFailed = true;

                  Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Gagal login!",
                  });
                  this.validation.value = this.error.response.data;
                  this.loginFailed = true;
                }
              })
              .catch((error) => {
                console.log(error);
              });
          });
      }

      this.validation = [];

      if (!this.user.email) {
        this.validation.email = true;
      }

      if (!this.user.password) {
        this.validation.password = true;
      }
    },
  },

  //check user already logged in
  mounted() {
    if (this.loggedIn) {
      return this.$router.push({ name: "Dashboard" });
    }
  },
};
</script>
