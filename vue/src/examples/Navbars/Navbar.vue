<template>
  <nav
    class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl"
    :class="
      this.$store.state.isRTL ? 'top-0 position-sticky z-index-sticky' : ''
    "
    v-bind="$attrs"
    id="navbarBlur"
    data-scroll="true"
  >
    <div class="px-3 py-1 container-fluid">
      <breadcrumbs :currentPage="currentRouteName" textWhite="text-white" />

      <div
        class="mt-2 collapse navbar-collapse mt-sm-0 me-md-0 me-sm-4"
        :class="this.$store.state.isRTL ? 'px-0' : 'me-sm-4'"
        id="navbar"
      >
        <div
          class="pe-md-3 d-flex align-items-center"
          :class="this.$store.state.isRTL ? 'me-md-auto' : 'ms-md-auto'"
        >
          <div class="input-group"></div>
        </div>
        <ul class="navbar-nav justify-content-end">
          <li v-if="loggedIn" class="nav-item d-flex align-items-center">
            <a
              href="#"
              @click.prevent="handleLogout"
              class="px-0 nav-link font-weight-bold text-white"
              target="_blank"
            >
              <i
                class="fa fa-user"
                :class="this.$store.state.isRTL ? 'ms-sm-2' : 'me-sm-2'"
              ></i>
              <span v-if="this.$store.state.isRTL" class="d-sm-inline d-none"
                >يسجل دخول</span
              >
              <span v-else class="d-sm-inline d-none">Log Out</span>
            </a>
          </li>
          <li v-else class="nav-item d-flex align-items-center">
            <a
              href="#"
              class="px-0 nav-link font-weight-bold text-white"
              target="_blank"
            >
              <i
                class="fa fa-user"
                :class="this.$store.state.isRTL ? 'ms-sm-2' : 'me-sm-2'"
              ></i>
              <span v-if="this.$store.state.isRTL" class="d-sm-inline d-none"
                >يسجل دخول</span
              >
              <span v-else class="d-sm-inline d-none">Belum Log In</span>
            </a>
          </li>
          <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
            <a
              href="#"
              @click="toggleSidebar"
              class="p-0 nav-link text-white"
              id="iconNavbarSidenav"
            >
              <div class="sidenav-toggler-inner">
                <i class="sidenav-toggler-line bg-white"></i>
                <i class="sidenav-toggler-line bg-white"></i>
                <i class="sidenav-toggler-line bg-white"></i>
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
<script>
import Breadcrumbs from "../Breadcrumbs.vue";
import { mapMutations, mapActions } from "vuex";
import placeholder from "../../assets/img/placeholder.jpg";
import showSwal from "../../mixins/showSwal.js";
import axios from "axios";
import Swal from "sweetalert2";

export default {
  name: "navbar",
  components: {
    Breadcrumbs,
  },
  data() {
    return {
      showMenu: false,
      showProfileDropdown: false,
      loggedIn: localStorage.getItem("loggedIn"),
      token: localStorage.getItem("token"),
      user: [],
    };
  },
  props: ["minNav", "textWhite"],
  computed: {
    currentRouteName() {
      return this.$route.name;
    },
    // loggedIn() {
    //   const token = localStorage.getItem("token");
    //   return !!token; // Mengembalikan true jika token ada, false jika tidak ada
    // },
    getProfileNavbar() {
      return this.$store.getters["profile/me"];
    },
    getNameNavbar() {
      return this.getProfileNavbar?.name;
    },
    getImageNavbar() {
      return this.getProfileNavbar?.profile_image;
    },
  },
  mixins: [showSwal],
  created() {
    this.minNav;
    axios
      .get("http://localhost:8000/api/user", {
        headers: { Authorization: "Bearer " + this.token },
      })
      .then((response) => {
        console.log(response);
        this.user = response.data;
      });
  },

  methods: {
    ...mapMutations(["navbarMinimize", "toggleConfigurator"]),
    ...mapActions(["toggleSidebarColor"]),

    profileImage() {
      return this.getImageNavbar ? this.getImageNavbar : placeholder;
    },
    async handleLogout() {
      try {
        await axios.get("http://localhost:8000/api/logout");
        localStorage.removeItem("loggedIn");
        localStorage.removeItem("token");

        Swal.fire({
          icon: "success",
          title: "Berhasil",
          text: "Logout berhasil!",
        }).then(() => {
          return this.$router.push({ name: "Login" });
        });
      } catch (error) {
        console.error("Error during logout:", error);
        // Handle error, show Swal error message, etc.
      }
    },

    toggleSidebar() {
      this.toggleSidebarColor("bg-white");
      this.navbarMinimize();
    },

    mounted() {
      if (!this.loggedIn) {
        return this.$router.push({ name: "Login" });
      }
    },
  },
};
</script>
