// Dropdown lonceng admin (aktivitas baru dan pesan belum dibaca). Saat dibuka pertama kali, hitungan aktivitas
// disembunyikan dan server diberi tahu lewat sendBeacon (hanya mengirim penanda "sudah dilihat", tidak mengambil
// data) agar tetap hilang setelah halaman dimuat ulang. Hitungan pesan tetap sampai pesannya dibuka.
export default (seenUrl, token, activityCount = 0, messageCount = 0) => ({
    open: false,
    seen: false,

    get total() {
        return messageCount + (this.seen ? 0 : activityCount);
    },

    get label() {
        return this.total > 99 ? '99+' : this.total;
    },

    toggle() {
        this.open = !this.open;

        if (this.open && !this.seen) {
            this.seen = true;
            navigator.sendBeacon(seenUrl, new URLSearchParams({ _token: token }));
        }
    },
});
