
import axios from 'axios';

const axiosInstance = axios.create({
  //  baseURL: 'https://freecvbd.com', // Set the base URL
   baseURL: 'http://127.0.0.1:8000', // Set the base URL
  
  timeout: 5000, // Set a timeout for requests
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer YOUR_TOKEN`, // Set the Authorization header if needed
  },
});

export default axiosInstance;
