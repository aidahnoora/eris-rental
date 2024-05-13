<script setup>
import { onMounted, ref } from "vue";
import api from "../../api";

const mobils = ref([]);

const fetchDataMobils = async () => {
  await api.get("/api/mobils").then((response) => {
    mobils.value = response.data.data.data;
  });
};

const filterByStatus = async (status) => {
  await api.get(`/api/mobils/filter/${status}`).then((response) => {
    mobils.value = response.data.data;
  });
};

onMounted(() => {
  fetchDataMobils();
});
</script>

<template>
  <div class="container mt-5 mb-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card border-0 rounded shadow">
          <div
            class="card-header d-flex justify-content-between align-items-center"
          >
            <h5 class="m-0">Ketersediaan Data Mobil</h5>
            <div class="col-md-auto">
              <router-link
                :to="{ name: 'mobils' }"
                class="btn btn-md btn-danger rounded shadow border-0"
                >Kembali</router-link
              >
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="btn-group">
                <button
                  type="button"
                  class="btn btn-md btn-info dropdown-toggle"
                  data-bs-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  Filter Status Mobil
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" @click="filterByStatus('ada')"
                    >Mobil Ada</a
                  >
                  <a class="dropdown-item" @click="filterByStatus('kosong')"
                    >Mobil Kosong</a
                  >
                  <a class="dropdown-item" @click="fetchDataMobils()"
                    >Reset Filter</a
                  >
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead class="text-center">
                  <tr>
                    <th width="10">No.</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="mobils.length == 0">
                    <td colspan="7" class="text-center">
                      <div class="alert alert-danger mb-0 text-white">
                        Data Belum Tersedia!
                      </div>
                    </td>
                  </tr>
                  <tr v-else v-for="(mobil, index) in mobils" :key="index">
                    <td class="text-center">{{ index + 1 }}.</td>
                    <td>
                      {{ mobil.nama }}
                    </td>
                    <td class="text-center" v-if="mobil.stok > 0">
                      <span
                        class="badge filter bg-gradient-success"
                        data-color="success"
                        >Mobil Ada</span
                      >
                      <br />
                      Stok : {{ mobil.stok }}
                    </td>
                    <td class="text-center" v-else>
                      <span
                        class="badge filter bg-gradient-danger"
                        data-color="danger"
                        >Mobil Kosong</span
                      >
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
