import Alpine from 'alpinejs';
import countdown from './countdown';
import coverflow from './coverflow';
import idleLogout from './idleLogout';
import imagePreview from './imagePreview';
import imageViewer from './imageViewer';
import liveSearch from './liveSearch';
import navbar from './navbar';
import mailHandoff from './mailHandoff';
import notificationBell from './notificationBell';
import sortableRows from './sortableRows';

Alpine.data('navbar', navbar);
Alpine.data('countdown', countdown);
Alpine.data('idleLogout', idleLogout);
Alpine.data('coverflow', coverflow);
Alpine.data('imagePreview', imagePreview);
Alpine.data('imageViewer', imageViewer);
Alpine.data('liveSearch', liveSearch);
Alpine.data('mailHandoff', mailHandoff);
Alpine.data('notificationBell', notificationBell);
Alpine.data('sortableRows', sortableRows);

window.Alpine = Alpine;
Alpine.start();
