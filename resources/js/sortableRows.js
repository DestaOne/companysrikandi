// Item ber-atribut data-id (baris tabel atau petak grid) yang bisa diseret untuk mengubah urutan.
// Setelah dilepas, urutan id dikirim lewat form biasa (tanpa fetch); komponen pembungkus menyediakan
// x-ref "rows", "form", dan "ids". axis 'y' untuk daftar vertikal (tabel), 'x' untuk petak grid.
export default (axis = 'y') => ({
    dragging: null,
    before: '',

    init() {
        this.restoreScroll();
        this.$refs.rows.querySelectorAll('[data-id]').forEach((row) => (row.draggable = true));
    },

    // Form dikirim biasa (halaman dimuat ulang), jadi posisi gulir disimpan lalu dipulihkan agar tidak kembali ke atas.
    restoreScroll() {
        try {
            const y = sessionStorage.getItem('sortableScroll');

            if (y !== null) {
                sessionStorage.removeItem('sortableScroll');
                window.scrollTo(0, Number(y));
            }
        } catch {
            // penyimpanan peramban dinonaktifkan: biarkan di atas
        }
    },

    order() {
        return [...this.$refs.rows.querySelectorAll('[data-id]')].map((row) => row.dataset.id);
    },

    start(event) {
        this.dragging = event.target.closest?.('[data-id]');

        if (!this.dragging) {
            return;
        }

        this.before = this.order().join();
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/plain', this.dragging.dataset.id); // Firefox butuh data agar drag jalan
        this.dragging.classList.add('opacity-40');
    },

    // Baris yang diseret langsung berpindah mengikuti kursor, jadi hasilnya terlihat sebelum dilepas.
    over(event) {
        const row = event.target.closest?.('[data-id]');

        if (!this.dragging || !row || row === this.dragging) {
            return;
        }

        const rect = row.getBoundingClientRect();
        const after = axis === 'x' ? event.clientX > rect.left + rect.width / 2 : event.clientY > rect.top + rect.height / 2;

        row.parentNode.insertBefore(this.dragging, after ? row.nextSibling : row);
    },

    end() {
        this.dragging?.classList.remove('opacity-40');
        this.dragging = null;

        // Tidak berubah (mis. dilepas di tempat semula atau dibatalkan): tidak perlu menyimpan
        if (this.order().join() === this.before) {
            return;
        }

        this.$refs.ids.replaceChildren(
            ...this.order().map((id) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;

                return input;
            }),
        );

        try {
            sessionStorage.setItem('sortableScroll', String(window.scrollY));
        } catch {
            // abaikan bila penyimpanan tidak tersedia
        }

        this.$refs.form.requestSubmit();
    },
});
