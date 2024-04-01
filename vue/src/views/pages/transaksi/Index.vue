<script setup>
import Swal from "sweetalert2";
import { onMounted, ref } from "vue";
import api from "../../api";

const transaksis = ref([]);

const fetchDataTransaksis = async () => {
  await api.get("/api/transaksis").then((response) => {
    transaksis.value = response.data.data.data;
  });
};

onMounted(() => {
  fetchDataTransaksis();
});

const deleteTransaksi = async (id) => {
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
        .delete(`/api/transaksis/${id}`)
        .then(() => {
          fetchDataTransaksis();
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
            <h5 class="m-0">Data Transaksi Penyewaan</h5>
            <router-link
              :to="{ name: 'transaksis.create' }"
              class="btn btn-md btn-success rounded shadow border-0"
              >Tambah</router-link
            >
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead class="text-center">
                  <tr>
                    <th>No.</th>
                    <th scope="col">Nota</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Tanggal Sewa</th>
                    <th scope="col">Total Bayar</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="transaksis.length == 0">
                    <td colspan="7" class="text-center">
                      <div class="alert alert-danger mb-0 text-white">
                        Data Belum Tersedia!
                      </div>
                    </td>
                  </tr>
                  <tr
                    v-else
                    v-for="(transaksi, index) in transaksis"
                    :key="index"
                  >
                    <td class="text-center">{{ index + 1 }}.</td>
                    <td class="text-center">
                      {{ transaksi.nota }}
                    </td>
                    <td class="text-center">
                      {{ transaksi.customer.nama }}
                    </td>
                    <td class="text-center">
                      {{ transaksi.tgl_sewa }}
                    </td>
                    <td class="text-center">
                      Rp {{ transaksi.total_bayar }}
                    </td>
                    <td class="text-center">
                      <label
                        v-if="transaksi.status === '0'"
                        style="color: red"
                      >
                        Belum Dibayar
                      </label>
                      <label v-if="transaksi.status === '1'" style="color: green">
                        Sudah Dibayar
                      </label>
                    </td>
                    <td class="text-center">
                      <div class="dropdown">
                        <button
                          class="btn btn-info dropdown-toggle"
                          type="button"
                          id="dropdownMenuButton"
                          data-bs-toggle="dropdown"
                          aria-expanded="false"
                        >
                          ACTIONS
                        </button>
                        <ul
                          class="dropdown-menu"
                          aria-labelledby="dropdownMenuButton"
                        >
                          <li>
                            <router-link
                              :to="{
                                name: 'transaksis.show',
                                params: { id: transaksi.id },
                              }"
                              class="dropdown-item"
                              >DETAIL</router-link
                            >
                          </li>
                          <li>
                            <router-link
                              :to="{
                                name: 'transaksis.edit',
                                params: { id: transaksi.id },
                              }"
                              class="dropdown-item"
                              >EDIT</router-link
                            >
                          </li>
                          <li>
                            <button
                              @click.prevent="deleteTransaksi(transaksi.id)"
                              class="dropdown-item"
                            >
                              DELETE
                            </button>
                          </li>
                        </ul>
                      </div>
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
