// Kirim form logout otomatis bila tidak ada aktivitas selama `seconds` detik
export default (seconds) => ({
    timer: null,

    reset() {
        clearTimeout(this.timer);
        this.timer = setTimeout(() => this.$el.submit(), seconds * 1000);
    },
});
