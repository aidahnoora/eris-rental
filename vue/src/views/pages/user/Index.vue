<script setup>
import { onMounted, ref } from "vue";
import api from "../../api";

const users = ref([]);
const baseURL = "http://localhost:8000/storage/photos/";

const fetchDataUsers = async () => {
  await api.get("/api/users").then((response) => {
    users.value = response.data.data.data;
  });
};

onMounted(() => {
  fetchDataUsers();
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
            <h5 class="m-0">Data User</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead class="text-center">
                  <tr>
                    <th>No.</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Email</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="users.length == 0">
                    <td colspan="7" class="text-center">
                      <div class="alert alert-danger mb-0 text-white">
                        Data Belum Tersedia!
                      </div>
                    </td>
                  </tr>
                  <tr
                    v-else
                    v-for="(user, index) in users"
                    :key="index"
                  >
                    <td class="text-center">{{ index + 1 }}.</td>
                    <td>
                      {{ user.name }}
                    </td>
                    <td class="text-center">
                      <img
                        :src="`${baseURL}${user.image}`"
                        width="100"
                        style="border-radius: 100%;"
                      />
                    </td>
                    <td class="text-center">
                      {{ user.email }}
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
