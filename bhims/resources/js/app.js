import './bootstrap';

import Alpine from 'alpinejs';


// Debug logging
console.log('Alpine.js is loading...');

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    console.log('Alpine.js is initializing...');
});

Alpine.start().then(() => {
    console.log('Alpine.js has started');});
