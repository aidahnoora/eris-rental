<script setup>
import Swal from "sweetalert2";
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "../../api";
import { watch } from "vue";

const router = useRouter();
const route = useRoute();

const customers = ref([]);
const customer_id = ref("");

// const nota = ref("");
const tgl_sewa = ref("");
const tgl_kembali = ref("");
const tgl_pengembalian_mobil = ref("");
const status = ref("");
const lama_telat = ref("");
const denda_per_hari = ref("");
const total_bayar = ref("");
const durasi_sewa = ref("");
const total_denda = ref(0);
const total_sewa = ref("");
const finalBayar = ref("");

const errors = ref([]);

const itemTransaksis = ref([]);

const getCustomers = async () => {
  try {
    const response = await api.get("/api/get-customers");

    customers.value = response.data.data.data;
  } catch (error) {
    console.error("Error fetching customers:", error);
  }
};

const getItemTransaksis = async () => {
  try {
    const response = await api.get(`api/transaksis/${route.params.id}/items`);

    itemTransaksis.value = response.data.data.data;
  } catch (error) {
    console.error("Error fetching itemTransaksis:", error);
  }
};

onMounted(async () => {
  await api.get(`/api/transaksis/${route.params.id}`).then((response) => {
    customer_id.value = response.data.data.customer_id;
    // nota.value = response.data.data.nota;
    tgl_sewa.value = response.data.data.tgl_sewa;
    tgl_kembali.value = response.data.data.tgl_kembali;
    tgl_pengembalian_mobil.value = response.data.data.tgl_pengembalian_mobil;
    status.value = response.data.data.status;
    durasi_sewa.value = response.data.data.durasi_sewa;
    lama_telat.value = response.data.data.lama_telat;
    denda_per_hari.value = response.data.data.denda_per_hari;
    total_denda.value = response.data.data.total_denda;
    total_bayar.value = response.data.data.total_bayar;
  }),
    getCustomers(),
    getItemTransaksis();
});

const calculateTotal = () => {
  total_sewa.value = total_bayar.value - denda_per_hari.value;
  total_denda.value = denda_per_hari.value * lama_telat.value;
  if (status.value == "0") {
    finalBayar.value = total_bayar.value + total_denda.value;
  } else {
    finalBayar.value = total_bayar.value;
  }
};

watch([tgl_sewa, tgl_kembali, tgl_pengembalian_mobil], () => {
  if (tgl_sewa.value && tgl_kembali.value && tgl_pengembalian_mobil.value) {
    const diffTime = new Date(tgl_kembali.value) - new Date(tgl_sewa.value);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    durasi_sewa.value = diffDays;

    const diffTimeLate =
      new Date(tgl_pengembalian_mobil.value) - new Date(tgl_kembali.value);
    const diffDaysLate = Math.ceil(diffTimeLate / (1000 * 60 * 60 * 24));
    lama_telat.value = diffDaysLate > 0 ? diffDaysLate : 0;

    calculateTotal();
  }
});

const updateTransaksi = async () => {
  let formData = new FormData();

  formData.append("customer_id", customer_id.value);
  // formData.append("nota", nota.value);
  formData.append("tgl_sewa", tgl_sewa.value);
  formData.append("tgl_kembali", tgl_kembali.value);
  formData.append("tgl_pengembalian_mobil", tgl_pengembalian_mobil.value);
  formData.append("status", status.value);
  formData.append("durasi_sewa", durasi_sewa.value);
  formData.append("lama_telat", lama_telat.value);
  formData.append("denda_per_hari", denda_per_hari.value);
  formData.append("total_denda", total_denda.value);
  formData.append("total_bayar", finalBayar.value);
  formData.append("_method", "PATCH");

  await api
    .post(`/api/transaksis/${route.params.id}`, formData)
    .then(() => {
      Swal.fire({
        icon: "success",
        title: "Berhasil",
        text: "Data berhasil diperbarui!",
      }).then(() => {
        router.push({ path: "/transaksis" });
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
            <h5 class="m-0">Edit Transaksi Penyewaan</h5>
            <router-link
              :to="{ name: 'transaksis' }"
              class="btn btn-md btn-warning rounded shadow border-0"
              >Kembali</router-link
            >
          </div>
          <div class="card-body">
            <form @submit.prevent="updateTransaksi()">
              <div class="row">
                <h6>Data Penyewaan</h6>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label fw-bold">Customer</label>
                    <select v-model="customer_id" class="form-select">
                      <option
                        v-for="customer in customers"
                        :key="customer.id"
                        :value="customer.id"
                      >
                        {{ customer.nama }}
                      </option>
                    </select>
                    <div
                      v-if="errors.customer_id"
                      class="alert alert-danger mt-2"
                    >
                      <span>{{ errors.customer_id[0] }}</span>
                    </div>
                  </div>
                  <!-- <div class="mb-3">
                    <label class="form-label fw-bold">Nota</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="nota"
                      placeholder="Masukkan nota"
                    />
                    <div v-if="errors.nota" class="alert alert-danger mt-2">
                      <span>{{ errors.nota[0] }}</span>
                    </div>
                  </div> -->
                  <div class="mb-3">
                    <label class="form-label fw-bold"
                      >Tanggal Pengembalian</label
                    >
                    <input
                      type="date"
                      class="form-control"
                      v-model="tgl_pengembalian_mobil"
                      :disabled="status === '1'"
                    />
                    <div
                      v-if="errors.tgl_pengembalian_mobil"
                      class="alert alert-danger mt-2"
                    >
                      <span>{{ errors.tgl_pengembalian_mobil[0] }}</span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Durasi Terlambat</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="lama_telat"
                      disabled
                    />
                    <div
                      v-if="errors.lama_telat"
                      class="alert alert-danger mt-2"
                    >
                      <span>{{ errors.lama_telat[0] }}</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Sewa</label>
                        <input
                          type="date"
                          class="form-control"
                          v-model="tgl_sewa"
                          disabled
                        />
                        <div
                          v-if="errors.tgl_sewa"
                          class="alert alert-danger mt-2"
                        >
                          <span>{{ errors.tgl_sewa[0] }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label fw-bold"
                          >Tanggal Kembali</label
                        >
                        <input
                          type="date"
                          class="form-control"
                          v-model="tgl_kembali"
                          disabled
                        />
                        <div
                          v-if="errors.tgl_kembali"
                          class="alert alert-danger mt-2"
                        >
                          <span>{{ errors.tgl_kembali[0] }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Durasi Sewa (Hari)</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="durasi_sewa"
                      disabled
                    />
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Status Pembayaran</label>
                    <select v-model="status" class="form-select">
                      <option value="1">Sudah Dibayar</option>
                      <option value="0">Belum Dibayar</option>
                    </select>
                    <div v-if="errors.status" class="alert alert-danger mt-2">
                      <span>{{ errors.status[0] }}</span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label fw-bold"
                          >Denda (per Hari)</label
                        >
                        <input
                          type="text"
                          class="form-control"
                          v-model="denda_per_hari"
                          :readonly="lama_telat === 0"
                          :disabled="status === '1'"
                          @input="calculateTotal()"
                        />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label fw-bold">Total Denda</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="total_denda"
                          :disabled="total_denda === 0"
                          readonly
                        />
                      </div>
                    </div>
                  </div>
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
                      <tr>
                        <td colspan="3" class="text-center">
                          <strong>Total Sewa (Rp)</strong>
                        </td>
                        <td class="text-center">Rp {{ total_sewa }}</td>
                      </tr>
                      <tr>
                        <td colspan="3" class="text-center">
                          <strong>Total Denda (Rp)</strong>
                        </td>
                        <td class="text-center">Rp {{ total_denda }}</td>
                      </tr>
                      <tr>
                        <td colspan="3" class="text-center">
                          <strong>Total Denda + Sewa (Rp)</strong>
                        </td>
                        <td class="text-center">Rp {{ finalBayar }}</td>
                      </tr>
                    </tbody>
                  </table>
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
