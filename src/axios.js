import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api', // Ubah URL ini sesuai dengan backend Anda
  headers: {
    'Content-Type': 'application/json',
  },
  timeout: 5000,
});

export default api;
