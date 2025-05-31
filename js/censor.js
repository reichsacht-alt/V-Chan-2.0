document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".pixelated-image").forEach(canvas => {
        const src = canvas.getAttribute("data-src");
        const ctx = canvas.getContext("2d");
        const img = new Image();
        img.crossOrigin = "anonymous"; // por si cargás desde otro dominio
        img.onload = () => {
            const pixelSize = 0.1; // cuanto menor, más borroso (por ejemplo: 0.1 = 10% del tamaño original)
            const w = canvas.width;
            const h = canvas.height;

            // Escalado hacia abajo
            const scaledW = Math.max(1, Math.floor(w * pixelSize));
            const scaledH = Math.max(1, Math.floor(h * pixelSize));
            const offscreenCanvas = document.createElement("canvas");
            offscreenCanvas.width = scaledW;
            offscreenCanvas.height = scaledH;
            const offCtx = offscreenCanvas.getContext("2d");

            offCtx.drawImage(img, 0, 0, scaledW, scaledH);

            // Escalado hacia arriba con "pixelated"
            ctx.imageSmoothingEnabled = false;
            ctx.clearRect(0, 0, w, h);
            ctx.drawImage(offscreenCanvas, 0, 0, scaledW, scaledH, 0, 0, w, h);
        };
        img.src = src;
    });
});