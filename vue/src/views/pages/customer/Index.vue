<script setup>
import Swal from 'sweetalert2'
import { onMounted, ref } from "vue";
import api from "../../api";

const customers = ref([]);

const fetchDataCustomers = async () => {
  await api.get("/api/customers").then((response) => {
    customers.value = response.data.data.data;
  });
};

onMounted(() => {
  fetchDataCustomers();
});

const deleteCustomer = async id => {
  Swal.fire({
    title: 'Apakah Anda yakin?',
    text: 'Data yang telah dihapus tidak dapat dikembalikan!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal',
  }).then(async result => {
    if (result.isConfirmed) {
      await api.delete(`/api/customers/${id}`)
        .then(() => {
          fetchDataCustomers()
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
            <h5 class="m-0">Data Customer</h5>
            <router-link
              :to="{ name: 'customers.create' }"
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
                    <th scope="col">Nama</th>
                    <th scope="col">Umur</th>
                    <th scope="col">Identitas</th>
                    <th scope="col">Jaminan</th>
                    <th scope="col">No. Hp</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="customers.length == 0">
                    <td colspan="8" class="text-center">
                      <div class="alert alert-danger mb-0 text-white">
                        Data Belum Tersedia!
                      </div>
                    </td>
                  </tr>
                  <tr
                    v-else
                    v-for="(customer, index) in customers"
                    :key="index"
                  >
                    <td class="text-center">{{ index + 1 }}.</td>
                    <td>
                      {{ customer.nama }}
                    </td>
                    <td class="text-center">
                      {{ customer.umur }}
                    </td>
                    <td class="text-center">
                      {{ customer.identitas }}
                    </td>
                    <td class="text-center">
                      {{ customer.jaminan }}
                    </td>
                    <td class="text-center">
                      {{ customer.no_hp }}
                    </td>
                    <td class="text-center">
                      {{ customer.alamat }}
                    </td>
                    <td class="text-center">
                      <router-link
                        :to="{
                          name: 'customers.edit',
                          params: { id: customer.id },
                        }"
                        class="btn btn-sm btn-primary rounded-sm shadow border-0 me-2"
                        >EDIT</router-link
                      >
                      <button @click.prevent="deleteCustomer(customer.id)"
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
