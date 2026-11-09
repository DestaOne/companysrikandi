// Navbar transparan di paling atas halaman, solid setelah di-scroll atau saat menu mobile terbuka.
export default () => ({
    open: false,
    scrolled: false,

    init() {
        this.sync();
    },

    get solid() {
        return this.open || this.scrolled;
    },

    sync() {
        this.scrolled = window.scrollY > 24;
    },

    toggle() {
        this.open = !this.open;
    },

    close() {
        this.open = false;
    },

    // Panel mobile ikut tertutup saat layar melebar ke tampilan desktop.
    closeOnDesktop() {
        if (window.innerWidth >= 768) {
            this.close();
        }
    },
});
