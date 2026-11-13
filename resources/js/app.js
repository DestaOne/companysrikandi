import Alpine from 'alpinejs';
import countdown from './countdown';
import coverflow from './coverflow';
import idleLogout from './idleLogout';
import imageViewer from './imageViewer';
import navbar from './navbar';

Alpine.data('navbar', navbar);
Alpine.data('countdown', countdown);
Alpine.data('idleLogout', idleLogout);
Alpine.data('coverflow', coverflow);
Alpine.data('imageViewer', imageViewer);

window.Alpine = Alpine;
Alpine.start();
