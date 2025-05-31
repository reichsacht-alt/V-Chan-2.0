document.addEventListener('DOMContentLoaded', () => {
    // Obtén todas las imágenes con la clase "gif-toggle"
    const alternate = document.querySelectorAll('.alt');

    // Para cada imagen, agregamos los eventos para cambiar el GIF al pasar el mouse
    alternate.forEach(img => {
        const original = img.src; // URL de la imagen estática
        const alt = img.getAttribute('alternate'); // Obtiene la URL del GIF desde el atributo data-gif

        // Si la imagen tiene un atributo data-gif, se aplican los eventos
        if (alt) {
            // Cuando el mouse pase por encima, cambia la fuente al GIF
            img.addEventListener('mouseover', () => {
                img.src = alt;
            });

            // Cuando el mouse salga de la imagen, vuelve a la imagen estática
            img.addEventListener('mouseout', () => {
                img.src = original;
            });
        }
    });
});