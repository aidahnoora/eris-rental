<script setup>
import Swal from "sweetalert2";
import { computed, onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "../../api";

const router = useRouter();
const route = useRoute();

const image = ref("");
const nama = ref("");
const stok = ref("");
const model = ref("");
const tahun_buat = ref("");
const nomor_plat = ref("");
const kapasitas = ref("");
const bbm = ref("");
const harga = ref("");
const transmisi = ref("");

const errors = ref([]);

const merks = ref([]);
const merk_id = ref(null);

const getMerks = async () => {
  await api
    .get("/api/get-merks")
    .then((response) => {
      merks.value = response.data.data.data;
    })
    .catch((error) => {
      console.error("Error fetching merks:", error);
    });
};

onMounted(async () => {
  await api.get(`/api/mobils/${route.params.id}`).then((response) => {
    merk_id.value = response.data.data.merk_id;
    nama.value = response.data.data.nama;
    stok.value = response.data.data.stok;
    model.value = response.data.data.model;
    tahun_buat.value = response.data.data.tahun_buat;
    nomor_plat.value = response.data.data.nomor_plat;
    kapasitas.value = response.data.data.kapasitas;
    bbm.value = response.data.data.bbm;
    harga.value = response.data.data.harga;
    transmisi.value = response.data.data.transmisi;
  }),
    getMerks();
});

const handleFileChange = (e) => {
  image.value = e.target.files[0];
};

const updateMobil = async () => {
  let formData = new FormData();

  formData.append("image", image.value);
  formData.append("merk_id", merk_id.value);
  formData.append("nama", nama.value);
  formData.append("stok", stok.value);
  formData.append("model", model.value);
  formData.append("tahun_buat", tahun_buat.value);
  formData.append("nomor_plat", nomor_plat.value);
  formData.append("kapasitas", kapasitas.value);
  formData.append("bbm", bbm.value);
  formData.append("harga", harga.value);
  formData.append("transmisi", transmisi.value);
  formData.append("_method", "PATCH");

  await api
    .post(`/api/mobils/${route.params.id}`, formData)
    .then(() => {
      Swal.fire({
        icon: "success",
        title: "Berhasil",
        text: "Data berhasil diperbarui!",
      }).then(() => {
        router.push({ path: "/mobils" });
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
            <h5 class="m-0">Edit Data Mobil</h5>
            <router-link
              :to="{ name: 'mobils' }"
              class="btn btn-md btn-warning rounded shadow border-0"
              >Kembali</router-link
            >
          </div>
          <div class="card-body">
            <form @submit.prevent="updateMobil()">
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label fw-bold">Image</label>
                    <input
                      type="file"
                      class="form-control"
                      @change="handleFileChange($event)"
                    />
                    <div v-if="errors.image" class="alert alert-danger mt-2">
                      <span>{{ errors.image[0] }}</span>
                    </div>
                  </div>
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
                    <label class="form-label fw-bold">Merk</label>
                    <select v-model="merk_id" class="form-control">
                      <option
                        v-for="merk in merks"
                        :key="merk.id"
                        :value="merk.id"
                      >
                        {{ merk.nama }}
                      </option>
                    </select>
                    <div v-if="errors.merk_id" class="alert alert-danger mt-2">
                      <span>{{ errors.merk_id[0] }}</span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Stok</label>
                    <input
                      type="number"
                      class="form-control"
                      v-model="stok"
                      placeholder="Masukkan stok"
                    />
                    <div v-if="errors.stok" class="alert alert-danger mt-2">
                      <span>{{ errors.stok[0] }}</span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Model</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="model"
                      placeholder="Masukkan model"
                    />
                    <div v-if="errors.model" class="alert alert-danger mt-2">
                      <span>{{ errors.model[0] }}</span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Tahun Buat</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="tahun_buat"
                      placeholder="Masukkan tahun buat"
                    />
                    <div
                      v-if="errors.tahun_buat"
                      class="alert alert-danger mt-2"
                    >
                      <span>{{ errors.tahun_buat[0] }}</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label fw-bold">Nomor Plat</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="nomor_plat"
                      placeholder="Masukkan nomor plat"
                    />
                    <div
                      v-if="errors.nomor_plat"
                      class="alert alert-danger mt-2"
                    >
                      <span>{{ errors.nomor_plat[0] }}</span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Kapasitas</label>
                    <input
                      type="number"
                      class="form-control"
                      v-model="kapasitas"
                      placeholder="Masukkan kapasitas"
                    />
                    <div
                      v-if="errors.kapasitas"
                      class="alert alert-danger mt-2"
                    >
                      <span>{{ errors.kapasitas[0] }}</span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">BBM</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="bbm"
                      placeholder="Masukkan bbm"
                    />
                    <div v-if="errors.bbm" class="alert alert-danger mt-2">
                      <span>{{ errors.bbm[0] }}</span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Harga Sewa (Rp)</label>
                    <input
                      type="number"
                      class="form-control"
                      v-model="harga"
                      placeholder="0"
                    />
                    <div
                      v-if="errors.harga"
                      class="alert alert-danger mt-2"
                    >
                      <span>{{ errors.harga[0] }}</span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Transmisi</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="transmisi"
                      placeholder="Masukkan transmisi"
                    />
                    <div
                      v-if="errors.transmisi"
                      class="alert alert-danger mt-2"
                    >
                      <span>{{ errors.transmisi[0] }}</span>
                    </div>
                  </div>
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
