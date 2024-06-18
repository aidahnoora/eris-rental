import axios from "axios";

const Api = axios.create({
  // ubah ip address local disini (sesuaikan dengan ip address saat di ipconfig)
  baseURL: "http://192.168.0.105:8000",
});

export default Api;
