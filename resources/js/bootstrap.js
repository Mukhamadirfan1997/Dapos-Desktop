import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import $ from 'jquery';
window.$ = window.jQuery = $;

import 'bootstrap';

import 'datatables.net';
import 'datatables.net-bs5';

import 'select2';

import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);
window.Chart = Chart;
