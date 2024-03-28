<template>
  <div
    class="collapse navbar-collapse w-auto h-auto h-100"
    id="sidenav-collapse-main"
  >
    <ul class="navbar-nav">
      <li class="nav-item">
        <sidenav-item
          url="/dashboard"
          :class="getRoute() === 'dashboard' ? 'active' : ''"
          navText="Dashboard"
        >
          <template v-slot:icon>
            <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
          </template>
        </sidenav-item>
      </li>

      <li class="nav-item">
        <sidenav-item
          url="/users"
          :class="getRoute() === 'users' ? 'active' : ''"
          navText="User"
        >
          <template v-slot:icon>
            <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
          </template>
        </sidenav-item>
      </li>
      <li class="nav-item">
        <sidenav-item
          url="/customers"
          :class="getRoute() === 'customers' ? 'active' : ''"
          navText="Data Customer"
        >
          <template v-slot:icon>
            <i class="ni ni-credit-card text-success text-sm opacity-10"></i>
          </template>
        </sidenav-item>
      </li>
      <li class="nav-item">
        <sidenav-item
          url="/merks"
          :class="getRoute() === 'merks' ? 'active' : ''"
          navText="Merk Mobil"
        >
          <template v-slot:icon>
            <i class="ni ni-world-2 text-danger text-sm opacity-10"></i>
          </template>
        </sidenav-item>
      </li>
      <li class="nav-item">
        <sidenav-item
          url="/mobils"
          :class="getRoute() === 'mobils' ? 'active' : ''"
          navText="Data Mobil"
        >
          <template v-slot:icon>
            <i class="ni ni-single-copy-04 text-danger text-sm opacity-10"></i>
          </template>
        </sidenav-item>
      </li>
      <li class="nav-item">
        <sidenav-item
          url="/transaksi"
          :class="getRoute() === 'transaksi' ? 'active' : ''"
          navText="Transaksi"
        >
          <template v-slot:icon>
            <i class="ni ni-collection text-info text-sm opacity-10"></i>
          </template>
        </sidenav-item>
      </li>

      <li class="mt-3 nav-item">
        <h6 class="text-xs ps-4 text-uppercase font-weight-bolder opacity-6">
          LAPORAN
        </h6>
      </li>

      <li class="nav-item">
        <sidenav-item
          url="/laporan"
          :class="getRoute() === 'laporan' ? 'active' : ''"
          navText="Laporan"
        >
          <template v-slot:icon>
            <i
              class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"
            ></i>
          </template>
        </sidenav-item>
      </li>

      <!-- <li class="nav-item">
        <div class="nav-link cursor-pointer" @click.prevent="useDropDown()">
          <div
            class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center"
          >
            <i class="fab fa-vuejs text-info text-sm opacity-10"></i>
          </div>
          Examples (API)
          <i v-if="!dropDownStatus" class="ms-3 fas fa-angle-right"></i>
          <i v-else class="ms-3 fas fa-angle-down"></i>
        </div>
        <ul v-if="dropDownStatus">
          <sidenav-item
            url="/examples/user-profile"
            :class="getRoute() === 'examples/user-profile' ? 'active' : ''"
            navText="User Profile"
          >
            <template v-slot:icon>
              <i class="fas fa-user-circle text-dark text-sm opacity-10"></i>
            </template>
          </sidenav-item>
          <sidenav-item
            url="/examples/user-management/list-users"
            :class="getRoute() === 'examples/users-list' ? 'active' : ''"
            navText="User Management"
          >
            <template v-slot:icon>
              <i class="fas fa-user-edit text-dark text-sm opacity-10"></i>
            </template>
          </sidenav-item>
        </ul>
      </li> -->
    </ul>
  </div>
  <div class="pt-3 mx-3 mt-3 sidenav-footer">
    <sidenav-card
      :class="cardBg"
      textPrimary="Need Help?"
      textSecondary="Please check our docs"
    />
  </div>
</template>
<script>
import SidenavItem from "./SidenavItem.vue";
import SidenavCard from "./SidenavCard.vue";

export default {
  name: "SidenavList",
  props: {
    cardBg: String,
  },
  data() {
    return {
      title: "Argon Dashboard 2",
      controls: "dashboardsExamples",
      isActive: "active",
      dropDownStatus: true,
    };
  },
  components: {
    SidenavItem,
    SidenavCard,
  },
  mounted() {},
  methods: {
    getRoute() {
      const routeArr = this.$route.path.split("/");
      return routeArr[1];
    },
    useDropDown() {
      this.dropDownStatus = !this.dropDownStatus;
    },
  },
};
</script>
