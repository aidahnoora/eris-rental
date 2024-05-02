<script setup>
import Swal from "sweetalert2";
import { ref } from "vue";
import { useRouter } from "vue-router";
import api from "../../api";

const router = useRouter();

const nama = ref("");
const umur = ref("");
const identitas = ref("");
const jaminan = ref("");
const no_hp = ref("");
const alamat = ref("");
const errors = ref([]);

const handleSubmit = async () => {
  await storeCustomer();
};

const storeCustomer = async () => {
  let formData = new FormData();

  formData.append("nama", nama.value);
  formData.append("umur", umur.value);
  formData.append("identitas", identitas.value);
  formData.append("jaminan", jaminan.value);
  formData.append("no_hp", no_hp.value);
  formData.append("alamat", alamat.value);

  await api
    .post("/api/customers", formData)
    .then(() => {
      Swal.fire({
        icon: "success",
        title: "Berhasil",
        text: "Data berhasil disimpan!",
      }).then(() => {
        router.push({ path: "/customers" });
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
      <div class="col-md-12">
        <div class="card border-0 rounded shadow">
          <div
            class="card-header d-flex justify-content-between align-items-center"
          >
            <h5 class="m-0">Tambah Data Customer</h5>
            <router-link
              :to="{ name: 'customers' }"
              class="btn btn-md btn-warning rounded shadow border-0"
              >Kembali</router-link
            >
          </div>
          <div class="card-body">
            <form @submit.prevent="handleSubmit($next)">
              <div class="row">
                <div class="col-md-6">
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
                  <div class="mb-3">
                    <label class="form-label fw-bold">Umur</label>
                    <input
                      type="number"
                      class="form-control"
                      v-model="umur"
                      placeholder="Masukkan Umur"
                    />
                    <div v-if="errors.umur" class="alert alert-danger mt-2">
                      <span>{{ errors.umur[0] }}</span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Identitas</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="identitas"
                      placeholder="Masukkan Identitas"
                    />
                    <div
                      v-if="errors.identitas"
                      class="alert alert-danger mt-2"
                    >
                      <span>{{ errors.identitas[0] }}</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label fw-bold">Jaminan</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="jaminan"
                      placeholder="Masukkan Jaminan"
                    />
                    <div v-if="errors.jaminan" class="alert alert-danger mt-2">
                      <span>{{ errors.jaminan[0] }}</span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Nomor Hp</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="no_hp"
                      placeholder="Masukkan Nomor Hp"
                    />
                    <div v-if="errors.no_hp" class="alert alert-danger mt-2">
                      <span>{{ errors.no_hp[0] }}</span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Alamat</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="alamat"
                      placeholder="Masukkan Alamat"
                    />
                    <div v-if="errors.alamat" class="alert alert-danger mt-2">
                      <span>{{ errors.alamat[0] }}</span>
                    </div>
                  </div>
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
