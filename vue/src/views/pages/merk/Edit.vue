<script setup>
import Swal from "sweetalert2";
import { ref, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import api from "../../api";

const router = useRouter();
const route = useRoute();

const nama = ref("");
const errors = ref([]);

onMounted(async () => {
  await api.get(`/api/merks/${route.params.id}`).then((response) => {
    nama.value = response.data.data.nama;
  });
});

const handleSubmit = async () => {
  await updateMerk();
};

const updateMerk = async () => {
  let formData = new FormData();

  formData.append("nama", nama.value);
  formData.append("_method", "PATCH");

  await api
    .post(`/api/merks/${route.params.id}`, formData)
    .then(() => {
      Swal.fire({
        icon: "success",
        title: "Berhasil",
        text: "Data berhasil diperbarui!",
      }).then(() => {
        router.push({ path: "/merks" });
      });
    })
    .catch((error) => {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Gagal memperbarui data. Ulangi lagi.",
      });
      errors.value = error.response.data;
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
            <h5 class="m-0">Edit Data Merk Mobil</h5>
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
                />
                <div v-if="errors.nama" class="alert alert-danger mt-2">
                  <span>{{ errors.nama[0] }}</span>
                </div>
              </div>

              <button
                type="submit"
                class="btn btn-md btn-primary rounded-sm shadow border-0"
              >
                Update
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
