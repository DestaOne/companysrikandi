import Alpine from 'alpinejs';
import carousel from './carousel';
import navbar from './navbar';

Alpine.data('navbar', navbar);
Alpine.data('carousel', carousel);

window.Alpine = Alpine;
Alpine.start();
