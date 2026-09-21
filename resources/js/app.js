import './bootstrap';
import Alpine from 'alpinejs';
import { initScrollReveal, countUp } from './animations';

window.Alpine = Alpine;
window.countUp = countUp;

Alpine.start();

document.addEventListener('DOMContentLoaded', initScrollReveal);
