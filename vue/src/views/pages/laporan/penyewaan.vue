<template>
  <div class="container mt-5 mb-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card border-0 rounded shadow">
          <div
            class="card-header d-flex justify-content-between align-items-center"
          >
            <h5 class="m-0">Laporan Penyewaan</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-2">
                <label for="bulan" style="font-size: medium">Bulan:</label>
              </div>
              <div class="col-md-3">
                <select
                  v-model="bulan"
                  @change="filterData"
                  class="form-select"
                  style="margin-bottom: 10px"
                >
                  <option value="">Pilih Bulan</option>
                  <option
                    v-for="bulan in bulanOptions"
                    :key="bulan.value"
                    :value="bulan.value"
                  >
                    {{ bulan.label }}
                  </option>
                </select>
              </div>
              <div class="col-md-2">
                <label for="bulan" style="font-size: medium">Tahun:</label>
              </div>
              <div class="col-md-2">
                <select
                  v-model="tahun"
                  @change="filterData"
                  class="form-select"
                  style="margin-bottom: 10px"
                >
                  <option value="">Pilih Tahun</option>
                  <option
                    v-for="tahun in tahunOptions"
                    :key="tahun"
                    :value="tahun"
                  >
                    {{ tahun }}
                  </option>
                </select>
              </div>
              <div class="col-md-3">
                <button
                  class="btn btn-block btn-success rounded shadow border-0"
                  @click="exportPDF"
                >
                  Export PDF
                </button>
              </div>
            </div>
            <br /><br />
            <div class="row">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead class="text-center">
                    <th scope="col">No.</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Tanggal Sewa</th>
                    <th scope="col">Status</th>
                    <th scope="col">Tanggal Pengembalian</th>
                    <th scope="col">Total</th>
                  </thead>
                  <tbody>
                    <tr v-if="transaksis.length == 0">
                      <td colspan="6" class="text-center">
                        Data Belum Tersedia!
                      </td>
                    </tr>
                    <tr
                      v-for="(transaksi, index) in transaksis"
                      :key="transaksi.id"
                    >
                      <td class="text-center">{{ index + 1 }}.</td>
                      <td class="text-center">
                        {{ transaksi.customer.nama }}
                      </td>
                      <td class="text-center">
                        {{ transaksi.tgl_sewa }}
                      </td>
                      <td
                        style="text-align: center"
                        v-if="transaksi.status == '0'"
                      >
                        Belum Dikembalikan
                      </td>
                      <td
                        style="text-align: center"
                        v-if="transaksi.status == '1'"
                      >
                        Sudah Dikembalikan
                      </td>
                      <td
                        class="text-center"
                        v-if="transaksi.tgl_pengembalian_mobil == NULL"
                      >
                        -
                      </td>
                      <td class="text-center" v-else>
                        {{ transaksi.tgl_pengembalian_mobil }}
                      </td>
                      <td class="text-center">
                        Rp. {{ transaksi.total_bayar }}
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
  </div>
</template>

<script>
import axios from "axios";
import Api from "../../api";

export default {
  data() {
    return {
      bulan: "",
      tahun: "",
      bulanOptions: [
        { value: 1, label: "Januari" },
        { value: 2, label: "Februari" },
        { value: 3, label: "Maret" },
        { value: 4, label: "April" },
        { value: 5, label: "Mei" },
        { value: 6, label: "Juni" },
        { value: 7, label: "Juli" },
        { value: 8, label: "Agustus" },
        { value: 9, label: "September" },
        { value: 10, label: "Oktober" },
        { value: 11, label: "November" },
        { value: 12, label: "Desember" },
      ],
      tahunOptions: [2022, 2023, 2024], // Ganti dengan tahun-tahun yang sesuai
      transaksis: [],
    };
  },
  methods: {
    async filterData() {
      if (this.bulan && this.tahun) {
        try {
          const response = await Api.get(
            `/api/transaksi-penyewaan/filter?bulan=${this.bulan}&tahun=${this.tahun}`
          );

          this.transaksis = response.data.transaksis;

          console.log(this.transaksis);
        } catch (error) {
          console.error("Error filtering data:", error);
        }
      }
    },
    async exportPDF() {
      if (this.bulan && this.tahun) {
        try {
          window.open(
            // ubah ip address local disini (sesuaikan dengan ip address saat di ipconfig)
            `http://192.168.0.105:8000/api/transaksi-penyewaan/export-pdf?bulan=${this.bulan}&tahun=${this.tahun}`,
            "_blank"
          );
        } catch (error) {
          console.error("Error exporting PDF:", error);
        }
      }
    },
  },
};
</script>
