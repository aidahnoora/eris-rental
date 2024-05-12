<script setup>
import Swal from "sweetalert2";
import { computed, onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import api from "../../api";
import { watch } from "vue";

const router = useRouter();

const customers = ref([]);
const mobils = ref([]);
const mobil_id = ref(null);

const customer_id = ref("");
const perjanjian = ref("");
const tgl_sewa = ref("");
const tgl_kembali = ref("");
const status = ref("");
const lama_telat = ref("");
const denda = ref("");

const errors = ref([]);

const mobilTerpilih = ref([]);

const getCustomers = async () => {
  try {
    const response = await api.get("/api/get-customers");

    customers.value = response.data.data.data;
  } catch (error) {
    console.error("Error fetching customers:", error);
  }
};

const getMobils = async () => {
  await api
    .get("/api/get-mobils")
    .then((response) => {
      mobils.value = response.data.data.data;
    })
    .catch((error) => {
      console.error("Error fetching mobils:", error);
    });
};

const durasi_sewa = ref("");

watch([tgl_sewa, tgl_kembali], () => {
  if (tgl_sewa.value && tgl_kembali.value) {
    const diffTime = new Date(tgl_kembali.value) - new Date(tgl_sewa.value);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    durasi_sewa.value = diffDays;

    // Hitung kembali subtotal setiap kali tanggal berubah
    mobilTerpilih.value.forEach((mobil) => {
      mobil.subtotal =
        durasi_sewa.value *
        (mobilOptions.value.find((option) => option.value === mobil.mobil_id)
          ?.harga || 0);
    });
  }
});

const tambahMobil = () => {
  if (!mobil_id.value) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Harap lengkapi data mobil.",
    });

    return;
  }

  const mobilSudahDipilih = mobilTerpilih.value.find(
    (mobil) => mobil.mobil_id === mobil_id.value
  );

  if (mobilSudahDipilih) {
    Swal.fire({
      icon: "warning",
      title: "Warning",
      text: "Mobil sudah ditambahkan.",
    });

    return;
  }

  const mobil = {
    mobil_id: mobil_id.value,
    subtotal:
      durasi_sewa.value *
      (mobilOptions.value.find((option) => option.value === mobil_id.value)
        ?.harga || 0),
  };

  mobilTerpilih.value.push(mobil);

  // console.log(mobil);

  mobil_id.value = null;
};

const hapusMobil = (index) => {
  mobilTerpilih.value.splice(index, 1);
};

const totalTransaksi = computed(() => {
  return mobilTerpilih.value.reduce(
    (total_bayar, mobil) => total_bayar + mobil.subtotal,
    0
  );
});

const storeTransaksi = async () => {
  let formData = new FormData();

  formData.append("customer_id", customer_id.value);
  formData.append("perjanjian", perjanjian.value);
  formData.append("tgl_sewa", tgl_sewa.value);
  formData.append("tgl_kembali", tgl_kembali.value);
  formData.append("durasi_sewa", durasi_sewa.value);
  formData.append("status", status.value);
  formData.append("lama_telat", lama_telat.value);
  formData.append("denda", denda.value);
  formData.append("total_bayar", totalTransaksi.value);

  mobilTerpilih.value.forEach((mobil, index) => {
    formData.append(`items[${index}][mobil_id]`, mobil.mobil_id);
    formData.append(`items[${index}][subtotal]`, mobil.subtotal);
  });

  await api
    .post("/api/transaksis", formData)
    .then(() => {
      Swal.fire({
        icon: "success",
        title: "Berhasil",
        text: "Data berhasil disimpan!",
      }).then(() => {
        router.push({ path: "/transaksis" });
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

onMounted(() => {
  getCustomers(), getMobils();
});

const mobilOptions = computed(() => {
  return mobils.value.map((mobil) => ({
    title: mobil.nama,
    harga: mobil.harga,
    value: mobil.id,
  }));
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
            <h5 class="m-0">Tambah Transaksi Penyewaan</h5>
            <router-link
              :to="{ name: 'transaksis' }"
              class="btn btn-md btn-warning rounded shadow border-0"
              >Kembali</router-link
            >
          </div>
          <div class="card-body">
            <form @submit.prevent="storeTransaksi()">
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
                  <div class="mb-3">
                    <label class="form-label fw-bold">Total Sewa (Rp)</label>
                    <input
                      type="number"
                      class="form-control"
                      v-model="totalTransaksi"
                      readonly
                    />
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
                  </div> -->
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
                  <!-- <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal Pengembalian</label>
                    <input
                      type="date"
                      class="form-control"
                      disabled
                    />
                  </div> -->
                </div>
              </div>
              <div class="row">
                <div class="mb-3">
                  <label class="form-label fw-bold">Form Perjanjian</label>
                  <textarea
                    class="form-control"
                    v-model="perjanjian"
                    cols="30"
                    rows="5"
                  ></textarea>
                  <div v-if="errors.perjanjian" class="alert alert-danger mt-2">
                    <span>{{ errors.perjanjian[0] }}</span>
                  </div>
                </div>
              </div>
              <div class="row">
                <h6>Data Mobil</h6>
                <label class="form-label fw-bold">Mobil</label>
                <div class="row">
                  <div class="col-md-8">
                    <div class="mb-3">
                      <select v-model="mobil_id" class="form-select">
                        <option
                          v-for="mobil in mobils"
                          :key="mobil.id"
                          :value="mobil.id"
                        >
                          {{ mobil.nama }}
                        </option>
                      </select>
                      <div
                        v-if="errors.mobil_id"
                        class="alert alert-danger mt-2"
                      >
                        <span>{{ errors.mobil_id[0] }}</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mb-3">
                      <button
                        type="button"
                        class="btn btn-primary"
                        @click="tambahMobil"
                      >
                        Tambah
                      </button>
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered" style="font-size: 13px">
                    <thead class="text-center">
                      <tr>
                        <th>No.</th>
                        <th>Mobil</th>
                        <th>Harga Sewa</th>
                        <th>Subtotal</th>
                        <th>Hapus</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(mobil, index) in mobilTerpilih" :key="index">
                        <td class="text-center">{{ index + 1 }}</td>
                        <td>
                          {{
                            mobilOptions.find(
                              (option) => option.value === mobil.mobil_id
                            )?.title
                          }}
                        </td>
                        <td class="text-center">
                          Rp
                          {{
                            mobilOptions.find(
                              (option) => option.value === mobil.mobil_id
                            )?.harga
                          }}
                        </td>
                        <td class="text-center">
                          Rp
                          {{ mobil.subtotal }}
                        </td>
                        <td class="text-center">
                          <button
                            type="button"
                            class="btn btn-warning btn-sm"
                            @click="hapusMobil(index)"
                          >
                            <i class="fas fa-trash"></i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3" class="text-center">
                          <strong>Total (Rp)</strong>
                        </td>
                        <td class="text-center">Rp {{ totalTransaksi }}</td>
                      </tr>
                    </tbody>
                  </table>
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
