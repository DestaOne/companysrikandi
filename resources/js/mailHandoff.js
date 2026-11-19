// Setelah pesan terkirim, pengunjung dialihkan otomatis ke email yang sudah terisi: Gmail web di desktop,
// aplikasi email (mailto) di ponsel. Tautan manual tetap tersedia di teks pada komponennya.
export default (urls, seconds = 3) => ({
    init() {
        const mobile = /Android|iPhone|iPad|iPod|Mobi/i.test(navigator.userAgent);

        setTimeout(() => {
            window.location.href = mobile ? urls.mailto : urls.gmail;
        }, seconds * 1000);
    },
});
