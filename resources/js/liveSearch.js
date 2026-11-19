// Pencarian langsung: baris daftar yang tampil di halaman disaring saat mengetik, tanpa memuat ulang halaman.
// Baris yang bisa disaring ber-atribut data-search (teks huruf kecil); baris data-search-empty tampil bila tidak ada yang cocok.
// Menekan Enter mengirim form biasa untuk mencari di semua halaman. Komponen pembungkus menyediakan x-ref "input".
export default () => ({
    query: '',

    init() {
        this.query = this.$refs.input.value;
        this.filter();
    },

    clear() {
        this.query = '';
        this.$refs.input.value = '';
        this.$refs.input.focus();
        this.filter();
    },

    filter() {
        const keyword = this.query.trim().toLowerCase();
        let shown = 0;

        document.querySelectorAll('[data-search]').forEach((row) => {
            const match = keyword === '' || row.dataset.search.includes(keyword);
            row.hidden = !match;
            shown += match ? 1 : 0;
        });

        document.querySelectorAll('[data-search-empty]').forEach((row) => (row.hidden = keyword === '' || shown > 0));
    },
});
