<script setup>
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "../../api";

const router = useRouter();
const route = useRoute();

const image = ref("");
const nama = ref("");
const kode = ref("");
const model = ref("");
const tahun_buat = ref("");
const nomor_plat = ref("");
const kapasitas = ref("");
const bbm = ref("");
const harga_sewa = ref("");
const transmisi = ref("");
const merk_id = ref("");
// const merkNama = ref("");

const baseURL = "http://localhost:8000/storage/posts/";

onMounted(async () => {
  await api.get(`/api/mobils/${route.params.id}`).then((response) => {
    merk_id.value = response.data.data.merk_id;
    nama.value = response.data.data.nama;
    kode.value = response.data.data.kode;
    model.value = response.data.data.model;
    tahun_buat.value = response.data.data.tahun_buat;
    nomor_plat.value = response.data.data.nomor_plat;
    kapasitas.value = response.data.data.kapasitas;
    bbm.value = response.data.data.bbm;
    harga_sewa.value = response.data.data.harga_sewa;
    transmisi.value = response.data.data.transmisi;

    image.value = response.data.data.image;
    // merkNama.value = response.data.data.merk.nama;
  });
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
            <h5 class="m-0">Detail Data Mobil</h5>
            <router-link
              :to="{ name: 'mobils' }"
              class="btn btn-md btn-warning rounded shadow border-0"
              >Kembali</router-link
            >
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered">
                <tr>
                  <td scope="col" width="25%">Gambar</td>
                  <td>:</td>
                  <td>
                    <img
                      :src="`${baseURL}${image}`"
                      width="200"
                      class="rounded-3"
                    />
                  </td>
                </tr>
                <!-- <tr>
                  <td scope="col">Merk</td>
                  <td>:</td>
                  <td>
                    {{ merkNama }}
                  </td>
                </tr> -->
                <tr>
                  <td scope="col">Nama</td>
                  <td>:</td>
                  <td>
                    {{ nama }}
                  </td>
                </tr>
                <tr>
                  <td scope="col">Kode</td>
                  <td>:</td>
                  <td>
                    {{ kode }}
                  </td>
                </tr>
                <tr>
                  <td scope="col">Model</td>
                  <td>:</td>
                  <td>
                    {{ model }}
                  </td>
                </tr>
                <tr>
                  <td scope="col">Tahun Buat</td>
                  <td>:</td>
                  <td>
                    {{ tahun_buat }}
                  </td>
                </tr>
                <tr>
                  <td scope="col">Nomor Plat</td>
                  <td>:</td>
                  <td>
                    {{ nomor_plat }}
                  </td>
                </tr>
                <tr>
                  <td scope="col">Kapasitas</td>
                  <td>:</td>
                  <td>
                    {{ kapasitas }} orang
                  </td>
                </tr>
                <tr>
                  <td scope="col">BBM</td>
                  <td>:</td>
                  <td>
                    {{ bbm }}
                  </td>
                </tr>
                <tr>
                  <td scope="col">Harga Sewa</td>
                  <td>:</td>
                  <td>
                    Rp {{ harga_sewa }}
                  </td>
                </tr>
                <tr>
                  <td scope="col">Transmisi</td>
                  <td>:</td>
                  <td>
                    {{ transmisi }}
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
