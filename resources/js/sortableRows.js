// Item ber-atribut data-id (baris tabel atau petak grid) yang bisa diseret untuk mengubah urutan.
// Setelah dilepas, urutan id dikirim lewat form ke iframe tersembunyi (tanpa fetch dan tanpa memuat ulang halaman); komponen pembungkus menyediakan
// x-ref "rows", "form", dan "ids". axis 'y' untuk daftar vertikal (tabel), 'x' untuk petak grid.
export default (axis = 'y') => ({
    dragging: null,
    before: '',

    init() {
        this.$refs.rows.querySelectorAll('[data-id]').forEach((row) => (row.draggable = true));
    },

    // Sukses: iframe menerima 204 (kosong), tidak ada yang perlu dilakukan. Gagal: iframe berisi halaman dengan
    // popup error; popup itu disalin ke halaman utama agar admin tahu urutan tidak tersimpan.
    saved(frame) {
        const popup = frame.contentDocument?.querySelector('[role="alertdialog"]')?.parentElement;

        if (popup) {
            document.body.append(document.importNode(popup, true));
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

        this.$refs.form.requestSubmit();
    },
});
