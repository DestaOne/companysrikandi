import Alpine from 'alpinejs';
import coverflow from './coverflow';
import imageViewer from './imageViewer';
import navbar from './navbar';

Alpine.data('navbar', navbar);
Alpine.data('coverflow', coverflow);
Alpine.data('imageViewer', imageViewer);

window.Alpine = Alpine;
Alpine.start();
