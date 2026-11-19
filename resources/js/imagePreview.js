// Pratinjau gambar yang baru dipilih di input file (dipakai x-ui.file-upload).
export default (current = null) => ({
    preview: current,

    pick(event) {
        const file = event.target.files[0];

        if (file) {
            this.preview = URL.createObjectURL(file);
        }
    },
});
