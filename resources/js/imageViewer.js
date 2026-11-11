// Pratinjau gambar: zoom 100% sampai 300%, geser dengan drag.
export default () => ({
    open: false,
    src: '',
    title: '',
    scale: 1,
    x: 0,
    y: 0,
    dragging: false,
    moved: false,
    origin: null,

    get percent() {
        return `${Math.round(this.scale * 100)}%`;
    },

    get style() {
        return `transform: translate(${this.x}px, ${this.y}px) scale(${this.scale})`;
    },

    show({ src, title }) {
        this.reset();
        this.src = src;
        this.title = title || '';
        this.open = true;
        document.body.classList.add('overflow-hidden');
        this.$nextTick(() => this.$refs.close.focus());
    },

    close() {
        this.open = false;
        document.body.classList.remove('overflow-hidden');
    },

    reset() {
        this.scale = 1;
        this.x = 0;
        this.y = 0;
    },

    zoom(delta) {
        this.scale = Math.min(3, Math.max(1, this.scale + delta));
        this.clamp();
    },

    toggle() {
        if (this.scale > 1) {
            this.reset();
            return;
        }

        this.zoom(1);
    },

    wheel(event) {
        this.zoom(event.deltaY < 0 ? 0.25 : -0.25);
    },

    // Batasi geseran agar tepi gambar tidak lepas dari bingkai.
    clamp() {
        const image = this.$refs.image;
        const stage = this.$refs.stage;
        const limitX = Math.max(0, (image.offsetWidth * this.scale - stage.clientWidth) / 2);
        const limitY = Math.max(0, (image.offsetHeight * this.scale - stage.clientHeight) / 2);

        this.x = Math.min(limitX, Math.max(-limitX, this.x));
        this.y = Math.min(limitY, Math.max(-limitY, this.y));
    },

    down(event) {
        this.moved = false;

        if (this.scale <= 1) {
            return;
        }

        this.dragging = true;
        this.origin = { px: event.clientX, py: event.clientY, x: this.x, y: this.y };
        event.currentTarget.setPointerCapture(event.pointerId);
    },

    move(event) {
        if (!this.dragging) {
            return;
        }

        const dx = event.clientX - this.origin.px;
        const dy = event.clientY - this.origin.py;

        if (Math.abs(dx) + Math.abs(dy) > 4) {
            this.moved = true;
        }

        this.x = this.origin.x + dx;
        this.y = this.origin.y + dy;
        this.clamp();
    },

    up() {
        this.dragging = false;
    },

    // Klik di area kosong menutup, kecuali baru selesai drag.
    backdrop() {
        if (!this.moved) {
            this.close();
        }
    },
});
