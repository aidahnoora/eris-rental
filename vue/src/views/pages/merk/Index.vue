<script setup>
import Swal from 'sweetalert2'
import { onMounted, ref } from "vue";
import api from "../../api";

const merks = ref([]);

const fetchDataMerks = async () => {
  await api.get("/api/merks").then((response) => {
    merks.value = response.data.data.data;
  });
};

onMounted(() => {
  fetchDataMerks();
});

const deleteMerk = async id => {
  Swal.fire({
    title: 'Apakah Anda yakin?',
    text: 'Data yang telah dihapus tidak dapat dikembalikan!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal',
  }).then(async result => {
    if (result.isConfirmed) {
      await api.delete(`/api/merks/${id}`)
        .then(() => {
          fetchDataMerks()
          Swal.fire('Berhasil!', 'Data berhasil dihapus.', 'success')
        })
        .catch(error => {
          Swal.fire('Error!', 'Gagal menghapus data.', 'error')
        })
    }
  })
}
</script>

<template>
  <div class="container mt-5 mb-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card border-0 rounded shadow">
          <div
            class="card-header d-flex justify-content-between align-items-center"
          >
            <h5 class="m-0">Data Merk Mobil</h5>
            <router-link
              :to="{ name: 'merks.create' }"
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
                    <th scope="col">Nama Merk Mobil</th>
                    <th scope="col" style="width:15%">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="merks.length == 0">
                    <td colspan="7" class="text-center">
                      <div class="alert alert-danger mb-0 text-white">
                        Data Belum Tersedia!
                      </div>
                    </td>
                  </tr>
                  <tr
                    v-else
                    v-for="(merk, index) in merks"
                    :key="index"
                  >
                    <td class="text-center">{{ index + 1 }}.</td>
                    <td>
                      {{ merk.nama }}
                    </td>
                    <td class="text-center">
                      <router-link
                        :to="{
                          name: 'merks.edit',
                          params: { id: merk.id },
                        }"
                        class="btn btn-sm btn-primary rounded-sm shadow border-0 me-2"
                        >EDIT</router-link
                      >
                      <button @click.prevent="deleteMerk(merk.id)"
                        class="btn btn-sm btn-danger rounded-sm shadow border-0"
                      >
                        DELETE
                      </button>
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
