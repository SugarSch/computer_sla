import axios from 'axios';
import React from "react";
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import 'bootstrap/dist/css/bootstrap.min.css';
