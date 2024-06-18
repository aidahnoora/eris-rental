<script setup>
import Swal from "sweetalert2";
import { onMounted, ref } from "vue";
import api from "../../api";

const mobils = ref([]);
// ubah ip address local disini (sesuaikan dengan ip address saat di ipconfig)
const baseURL = "http:///192.168.0.105:8000/storage/posts/";

const fetchDataMobils = async () => {
  await api.get("/api/mobils").then((response) => {
    mobils.value = response.data.data.data;
  });
};

onMounted(() => {
  fetchDataMobils();
});

const deleteMobil = async (id) => {
  Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Data yang telah dihapus tidak dapat dikembalikan!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Ya, hapus!",
    cancelButtonText: "Batal",
  }).then(async (result) => {
    if (result.isConfirmed) {
      await api
        .delete(`/api/mobils/${id}`)
        .then(() => {
          fetchDataMobils();
          Swal.fire("Berhasil!", "Data berhasil dihapus.", "success");
        })
        .catch((error) => {
          Swal.fire("Error!", "Gagal menghapus data.", "error");
        });
    }
  });
};
</script>

<template>
  <div class="container mt-5 mb-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card border-0 rounded shadow">
          <div
            class="card-header d-flex justify-content-between align-items-center"
          >
            <h5 class="m-0">Data Mobil</h5>
            <div class="col-md-auto">
              <router-link
                :to="{ name: 'mobils.look' }"
                class="btn btn-md btn-primary rounded shadow border-0"
                style="margin-right: 10px"
                >Lihat Ketersediaan Mobil</router-link
              >
              <router-link
                :to="{ name: 'mobils.create' }"
                class="btn btn-md btn-success rounded shadow border-0"
                >Tambah</router-link
              >
            </div>
          </div>

          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead class="text-center">
                  <tr>
                    <th>No.</th>
                    <th scope="col">Image</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Stok</th>
                    <th scope="col">Harga (per Hari)</th>
                    <th scope="col">Actions</th>
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
                    <td class="text-center">
                      <img
                        :src="`${baseURL}${mobil.image}`"
                        width="200"
                        class="rounded-3"
                      />
                    </td>
                    <td class="text-center">
                      {{ mobil.nama }}
                    </td>
                    <td class="text-center">
                      {{ mobil.stok }}
                    </td>
                    <td class="text-center">Rp {{ mobil.harga }}</td>
                    <td class="text-center">
                      <router-link
                        :to="{
                          name: 'mobils.show',
                          params: { id: mobil.id },
                        }"
                        class="btn btn-sm btn-info rounded-sm shadow border-0 me-2"
                        >DETAIL</router-link
                      >
                      <router-link
                        :to="{
                          name: 'mobils.edit',
                          params: { id: mobil.id },
                        }"
                        class="btn btn-sm btn-primary rounded-sm shadow border-0 me-2"
                        >EDIT</router-link
                      >
                      <button
                        @click.prevent="deleteMobil(mobil.id)"
                        class="btn btn-sm btn-danger rounded-sm shadow border-0"
                      >
                        DELETE
                      </button>
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
