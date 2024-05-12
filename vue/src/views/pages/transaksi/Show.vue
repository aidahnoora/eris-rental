<script setup>
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "../../api";

const route = useRoute();

const customer_id = ref("");
const perjanjian = ref("");
const tgl_sewa = ref("");
const tgl_kembali = ref("");
const tgl_pengembalian_mobil = ref("");
const status = ref("");
const lama_telat = ref("");
const denda_per_hari = ref("");
const total_bayar = ref("");
const durasi_sewa = ref("");
const total_denda = ref(0);
const customer = ref("");

const itemTransaksis = ref([]);

const getItemTransaksis = async () => {
  try {
    const response = await api.get(`api/transaksis/${route.params.id}/items`);

    itemTransaksis.value = response.data.data.data;

    console.log(itemTransaksis);
  } catch (error) {
    console.error("Error fetching itemTransaksis:", error);
  }
};

onMounted(async () => {
  await api.get(`/api/transaksis/${route.params.id}`).then((response) => {
    customer_id.value = response.data.data.customer_id;
    perjanjian.value = response.data.data.perjanjian;
    tgl_sewa.value = response.data.data.tgl_sewa;
    tgl_kembali.value = response.data.data.tgl_kembali;
    tgl_pengembalian_mobil.value = response.data.data.tgl_pengembalian_mobil;
    status.value = response.data.data.status;
    durasi_sewa.value = response.data.data.durasi_sewa;
    lama_telat.value = response.data.data.lama_telat;
    denda_per_hari.value = response.data.data.denda_per_hari;
    total_denda.value = response.data.data.total_denda;
    total_bayar.value = response.data.data.total_bayar;

    customer.value = response.data.data.customer.nama;
  }),
    getItemTransaksis();
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
            <h5 class="m-0">Detail Transaksi Penyewaan</h5>
            <router-link
              :to="{ name: 'transaksis' }"
              class="btn btn-md btn-warning rounded shadow border-0"
              >Kembali</router-link
            >
          </div>
          <div class="card-body">
            <form>
              <div class="row">
                <h6>Data Penyewaan</h6>
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <tr>
                      <td scope="col" width="20%">Customer</td>
                      <td>:</td>
                      <td>
                        {{ customer }}
                      </td>
                    </tr>
                    <tr>
                      <td scope="col">Tanggal Sewa</td>
                      <td>:</td>
                      <td>
                        {{ tgl_sewa }}
                      </td>
                    </tr>
                    <tr>
                      <td scope="col">Tanggal Kembali</td>
                      <td>:</td>
                      <td>
                        {{ tgl_kembali }}
                      </td>
                    </tr>
                    <tr>
                      <td scope="col">Pengembalian</td>
                      <td>:</td>
                      <td>
                        {{ tgl_pengembalian_mobil }}
                      </td>
                    </tr>
                    <tr>
                      <td scope="col">Status</td>
                      <td>:</td>
                      <td v-if="status === '0'">Belum Dibayar</td>
                      <td v-if="status === '1'">Sudah Dibayar</td>
                    </tr>
                    <tr>
                      <td scope="col">Durasi Sewa</td>
                      <td>:</td>
                      <td>{{ durasi_sewa }} Hari</td>
                    </tr>
                    <tr>
                      <td scope="col">Durasi Keterlambatan</td>
                      <td>:</td>
                      <td>{{ lama_telat }} Hari</td>
                    </tr>
                    <tr>
                      <td scope="col">Denda (per Hari)</td>
                      <td>:</td>
                      <td>Rp {{ denda_per_hari }}</td>
                    </tr>
                    <tr>
                      <td scope="col">Total Denda</td>
                      <td>:</td>
                      <td>Rp {{ total_denda }}</td>
                    </tr>
                    <tr>
                      <td scope="col">Total Bayar</td>
                      <td>:</td>
                      <td>Rp {{ total_bayar }}</td>
                    </tr>
                  </table>
                  <h5>Perjanjian :</h5>
                  <p>{{ perjanjian }}</p>
                </div>
              </div>
              <div class="row">
                <h6>Data Mobil</h6>
                <div class="table-responsive">
                  <table class="table table-bordered" style="font-size: 13px">
                    <thead class="text-center">
                      <tr>
                        <th>No.</th>
                        <th>Mobil</th>
                        <th>Harga Sewa</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in itemTransaksis" :key="index">
                        <td class="text-center">{{ index + 1 }}</td>
                        <td>
                          {{ item.mobil.nama }}
                        </td>
                        <td class="text-center">Rp {{ item.mobil.harga }}</td>
                        <td class="text-center">
                          Rp
                          {{ item.subtotal }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
