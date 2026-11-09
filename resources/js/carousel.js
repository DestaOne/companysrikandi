/**
 * Carousel layanan: berjalan otomatis dari kanan ke kiri, bisa digeser dengan tombol atau drag.
 * Isi di markup digandakan, jadi posisi cukup dijaga di antara 0,5x dan 1,5x lebar setengah track agar putaran menyambung.
 * State animasi disimpan di closure (bukan properti Alpine) supaya tidak memicu reaktivitas di setiap frame.
 */
export default () => {
    const SPEED = 68; // px per detik
    const DRAG_THRESHOLD = 5;

    let viewport;
    let track;
    let reduceMotion;
    let pos = 0;
    let written = 0;
    let last = 0;
    let tween = null;
    let hovering = false;
    let focused = false;
    let resumeAt = 0;
    let pending = false;
    let dragging = false;
    let suppressClick = false;
    let startX = 0;
    let startPos = 0;

    const half = () => track.scrollWidth / 2;

    const shift = (delta) => {
        pos += delta;
        startPos += delta;

        if (tween) {
            tween.from += delta;
            tween.to += delta;
        }
    };

    const normalize = () => {
        const h = half();

        if (h <= 0) {
            return;
        }

        while (pos >= h * 1.5) {
            shift(-h);
        }

        while (pos < h * 0.5) {
            shift(h);
        }
    };

    const step = () => {
        const first = track.querySelector('a');
        const second = first?.nextElementSibling;

        return first && second ? second.offsetLeft - first.offsetLeft : 312;
    };

    const frame = (now) => {
        const dt = Math.min(now - last, 50);
        last = now;

        // Posisi berubah dari luar (misalnya browser menggulir saat fokus keyboard).
        if (Math.abs(viewport.scrollLeft - written) > 2) {
            pos = viewport.scrollLeft;
        }

        if (tween) {
            const progress = Math.min((now - tween.start) / tween.duration, 1);

            pos = tween.from + (tween.to - tween.from) * (1 - Math.pow(1 - progress, 3));

            if (progress >= 1) {
                tween = null;
            }
        } else if (!reduceMotion.matches && !hovering && !focused && !dragging && now >= resumeAt) {
            pos += (SPEED * dt) / 1000;
        }

        normalize();
        viewport.scrollLeft = pos;
        written = viewport.scrollLeft;

        requestAnimationFrame(frame);
    };

    return {
        init() {
            viewport = this.$refs.viewport;
            track = this.$refs.track;
            reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
            last = performance.now();

            viewport.addEventListener('dragstart', (event) => event.preventDefault());

            // Setelah drag, klik yang menyusul tidak boleh membuka link kartu.
            viewport.addEventListener(
                'click',
                (event) => {
                    if (suppressClick) {
                        event.preventDefault();
                        event.stopPropagation();
                        suppressClick = false;
                    }
                },
                true,
            );

            pos = half();
            viewport.scrollLeft = pos;
            written = viewport.scrollLeft;
            requestAnimationFrame(frame);
        },

        go(direction) {
            const base = tween ? tween.to : pos;

            tween = { from: pos, to: base + direction * step(), start: performance.now(), duration: 450 };
        },

        enter(event) {
            hovering = event.pointerType === 'mouse';
        },

        leave() {
            hovering = false;
        },

        focusIn(event) {
            focused = event.target.matches(':focus-visible');
        },

        focusOut() {
            focused = false;
        },

        down(event) {
            if (event.pointerType === 'mouse' && event.button !== 0) {
                return;
            }

            pending = true;
            suppressClick = false;
            startX = event.clientX;
            startPos = pos;
        },

        move(event) {
            if (!pending) {
                return;
            }

            const dx = event.clientX - startX;

            if (!dragging && Math.abs(dx) > DRAG_THRESHOLD) {
                dragging = true;
                tween = null;
                viewport.dataset.dragging = 'true';

                try {
                    viewport.setPointerCapture(event.pointerId);
                } catch {
                    // Tanpa capture, drag tetap berjalan selama kursor berada di dalam carousel.
                }
            }

            if (dragging) {
                pos = startPos - dx;
            }
        },

        up(event) {
            if (!pending) {
                return;
            }

            pending = false;

            if (!dragging) {
                return;
            }

            dragging = false;
            suppressClick = true;
            delete viewport.dataset.dragging;

            try {
                viewport.releasePointerCapture(event.pointerId);
            } catch {
                // Pointer sudah dilepas.
            }

            if (event.pointerType !== 'mouse') {
                resumeAt = performance.now() + 2500;
            }
        },
    };
};
