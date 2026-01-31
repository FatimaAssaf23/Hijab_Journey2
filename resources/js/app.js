import './bootstrap';

import Alpine from 'alpinejs';
import { Chart, registerables } from 'chart.js';

// Register Chart.js components
Chart.register(...registerables);

// Make Chart available globally for inline scripts
window.Chart = Chart;

window.Alpine = Alpine;

Alpine.start();