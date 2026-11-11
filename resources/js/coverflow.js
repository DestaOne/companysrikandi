// Perilaku carousel coverflow: satu kartu aktif di tengah, kartu lain miring di kiri dan kanan.
export default (count) => ({
    active: 0,
    timer: null,

    init() {
        this.play();
    },

    // Selisih posisi terhadap kartu aktif, dibungkus agar putaran tak berujung.
    offset(index) {
        const half = count / 2;

        return ((((index - this.active + half) % count) + count) % count) - half;
    },

    style(index) {
        const offset = this.offset(index);
        const distance = Math.abs(offset);

        if (distance > 2) {
            return 'opacity:0;pointer-events:none;transform:translateX(-50%) scale(.6)';
        }

        return [
            `transform:translateX(calc(-50% + ${offset * 62}%)) translateZ(${-distance * 120}px) rotateY(${offset * -38}deg) scale(${1 - distance * 0.12})`,
            `z-index:${10 - distance}`,
            `opacity:${distance === 2 ? 0.45 : 1}`,
            `filter:brightness(${distance === 0 ? 1 : 0.6})`,
        ].join(';');
    },

    go(step) {
        this.active = (((this.active + step) % count) + count) % count;
    },

    // Kartu samping hanya memindahkan fokus ke tengah; kartu tengah boleh dibuka sebagai tautan.
    select(event, index) {
        if (this.offset(index) === 0) {
            return;
        }

        event.preventDefault();
        this.active = index;
    },

    play() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        this.stop();
        this.timer = setInterval(() => this.go(1), 4500);
    },

    stop() {
        clearInterval(this.timer);
        this.timer = null;
    },
});
