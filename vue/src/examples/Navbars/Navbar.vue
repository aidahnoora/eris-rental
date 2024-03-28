<template>
  <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" :class="this.$store.state.isRTL ? 'top-0 position-sticky z-index-sticky' : ''
    " v-bind="$attrs" id="navbarBlur" data-scroll="true">
    <div class="px-3 py-1 container-fluid">
      <breadcrumbs :currentPage="currentRouteName" textWhite="text-white" />

      <div class="mt-2 collapse navbar-collapse mt-sm-0 me-md-0 me-sm-4"
        :class="this.$store.state.isRTL ? 'px-0' : 'me-sm-4'" id="navbar">
        <div class="pe-md-3 d-flex align-items-center" :class="this.$store.state.isRTL ? 'me-md-auto' : 'ms-md-auto'">
          <div class="input-group">
            <span class="input-group-text text-body">
              <i class="fas fa-search" aria-hidden="true"></i>
            </span>
            <input type="text" class="form-control" :placeholder="this.$store.state.isRTL ? 'أكتب هنا...' : 'Type here...'
              " />
          </div>
        </div>
        <ul class="navbar-nav justify-content-end">
          <li v-if="loggedIn" class="nav-item d-flex align-items-center">
            <a id="dropdownProfileMenu" href="#" style="display: contents;"
              :class="`nav-link ${showProfileDropdown ? 'show' : ''}`" data-bs-toggle="dropdown" aria-expanded="false"
              @click="showProfileDropdown = !showProfileDropdown">
              <span class="avatar avatar-sm rounded-circle">
                <img :src="profileImage()" style="object-fit: cover !important;">
              </span>
              <span v-if="this.$store.state.isRTL" class="d-sm-inline d-none text-white p-2">{{ getNameNavbar }}</span>
              <span v-else class="d-sm-inline d-none text-white p-2">{{ getNameNavbar }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow position-absolute mt-0"
              :class="showProfileDropdown ? 'show' : ''" aria-labelledby="dropdownProfileMenu">
              <div class="border-radius-md" style="padding: 0.3rem 1rem;">
                <small>
                  Welcome
                </small>
              </div>
              <li>
                <a class="dropdown-item border-radius-md" href="javascript:;">
                  <div>
                    <i class="ni ni-circle-08"></i>
                    <router-link :to="{ name: 'User Profile' }" class="ms-2">My Profile</router-link>
                  </div>
                </a>
              </li>
              <hr class="m-2">
              <li>
                <a class="dropdown-item border-radius-md cursor-pointer" @click.prevent="handleLogout">
                  <div>
                    <i class="ni ni-user-run"></i>
                    <span class="ms-2">Logout</span>
                  </div>
                </a>
              </li>
            </ul>
          </li>

          <li v-else class="nav-item d-flex align-items-center">
            <router-link :to="{ name: 'Signin' }" class="px-0 nav-link font-weight-bold text-white" target="_blank">
              <i class="fa fa-user" :class="this.$store.state.isRTL ? 'ms-sm-2' : 'me-sm-2'"></i>
              <span v-if="this.$store.state.isRTL" class="d-sm-inline d-none">يسجل دخول</span>
              <span v-else class="d-sm-inline d-none">Log Out</span>
            </router-link>
          </li>
          <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
            <a href="#" @click="toggleSidebar" class="p-0 nav-link text-white" id="iconNavbarSidenav">
              <div class="sidenav-toggler-inner">
                <i class="sidenav-toggler-line bg-white"></i>
                <i class="sidenav-toggler-line bg-white"></i>
                <i class="sidenav-toggler-line bg-white"></i>
              </div>
            </a>
          </li>
          <li class="px-3 nav-item d-flex align-items-center">
            <a class="p-0 nav-link text-white" @click="toggleConfigurator">
              <i class="cursor-pointer fa fa-cog fixed-plugin-button-nav"></i>
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

export default {
  name: "navbar",
  components: {
    Breadcrumbs
  },
  data() {
    return {
      showMenu: false,
      showProfileDropdown: false
    };
  },
  props: ["minNav", "textWhite"],
  computed: {
    currentRouteName() {
      return this.$route.name;
    },
    loggedIn() {
      return this.$store.getters["auth/isAuthenticated"];
    },
    getProfileNavbar() {
      return this.$store.getters["profile/me"];
    },
    getNameNavbar() {
      return this.getProfileNavbar?.name
    },
    getImageNavbar() {
      return this.getProfileNavbar?.profile_image;
    },
  },
  mixins: [showSwal],
  created() {
    this.minNav;
  },
  methods: {
    ...mapMutations(["navbarMinimize", "toggleConfigurator"]),
    ...mapActions(["toggleSidebarColor"]),

    profileImage() {
      return this.getImageNavbar ? this.getImageNavbar : placeholder;
    },
    async handleLogout() {
      try {
        await this.$store.dispatch("auth/logout");
      } finally {
        await this.$router.push({ name: "Login" });
      }
    },

    toggleSidebar() {
      this.toggleSidebarColor("bg-white");
      this.navbarMinimize();
    }
  },
};
</script>
