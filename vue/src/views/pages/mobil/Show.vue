<script setup>
import { onMounted, ref } from "vue";
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
const merk = ref("");

const baseURL = "http://localhost:8000/storage/posts/";

onMounted(async () => {
  await api.get(`/api/mobils/${route.params.id}`).then((response) => {
    nama.value = response.data.data.nama;
    stok.value = response.data.data.stok;
    model.value = response.data.data.model;
    tahun_buat.value = response.data.data.tahun_buat;
    nomor_plat.value = response.data.data.nomor_plat;
    kapasitas.value = response.data.data.kapasitas;
    bbm.value = response.data.data.bbm;
    harga.value = response.data.data.harga;
    transmisi.value = response.data.data.transmisi;

    image.value = response.data.data.image;
    merk.value = response.data.data.merk.nama;
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
                <tr>
                  <td scope="col">Merk</td>
                  <td>:</td>
                  <td>
                    {{ merk }}
                  </td>
                </tr>
                <tr>
                  <td scope="col">Nama</td>
                  <td>:</td>
                  <td>
                    {{ nama }}
                  </td>
                </tr>
                <tr>
                  <td scope="col">Stok</td>
                  <td>:</td>
                  <td>
                    {{ stok }}
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
                    Rp {{ harga }}
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
