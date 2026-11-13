// Hitung mundur untuk tombol terkunci (mis. login setelah salah 3x)
export default (seconds) => ({
    left: seconds,

    init() {
        if (this.left <= 0) {
            return;
        }

        const timer = setInterval(() => {
            if (--this.left <= 0) {
                clearInterval(timer);
            }
        }, 1000);
    },
});
