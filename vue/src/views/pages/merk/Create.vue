<script setup>
import Swal from "sweetalert2";
import { ref } from "vue";
import { useRouter } from "vue-router";
import api from "../../api";

const router = useRouter();

const nama = ref("");
const errors = ref([]);

const handleSubmit = async () => {
  await storeMerk();
};

const storeMerk = async () => {
  let formData = new FormData();

  formData.append("nama", nama.value);

  await api
    .post("/api/merks", formData)
    .then(() => {
      Swal.fire({
        icon: "success",
        title: "Berhasil",
        text: "Data berhasil disimpan!",
      }).then(() => {
        router.push({ path: "/merks" });
      });
    })
    .catch((error) => {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Gagal menyimpan data. Ulangi lagi.",
      });
      errors.value = error.response.data;
    });
};
</script>

<template>
  <div class="container mt-5 mb-5">
    <div class="row">
      <div class="col-md-6 offset-3">
        <div class="card border-0 rounded shadow">
          <div
            class="card-header d-flex justify-content-between align-items-center"
          >
            <h5 class="m-0">Tambah Data Merk Mobil</h5>
            <router-link
              :to="{ name: 'merks' }"
              class="btn btn-md btn-warning rounded shadow border-0"
              >Kembali</router-link
            >
          </div>
          <div class="card-body">
            <form @submit.prevent="handleSubmit($next)">
              <div class="mb-3">
                <label class="form-label fw-bold">Nama</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="nama"
                  placeholder="Masukkan Nama"
                />
                <div v-if="errors.nama" class="alert alert-danger mt-2">
                  <span>{{ errors.nama[0] }}</span>
                </div>
              </div>

              <button
                type="submit"
                class="btn btn-md btn-primary rounded-sm shadow border-0"
              >
                Save
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
