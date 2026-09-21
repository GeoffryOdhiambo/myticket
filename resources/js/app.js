import './bootstrap';
import Alpine from 'alpinejs';
import { initScrollReveal, initCounters, countUp } from './animations';
import ticketSelector from './ticket-selector';
import scannerPage from './scanner';

window.Alpine = Alpine;
window.countUp = countUp;

Alpine.data('ticketSelector', ticketSelector);
Alpine.data('scannerPage', scannerPage);

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    initScrollReveal();
    initCounters();
});
