// js/updateChecker.js

let lastUpdateTime = sessionStorage.getItem("lastSeenUpdate") || null;

function showToast(message) {
    const toast = document.getElementById("updateToast");
    toast.innerHTML = message; // <-- esta es la línea clave
    toast.classList.add("show");

    // Oculta después de 3 segundos (si quieres volver a activarlo)
    // setTimeout(() => {
    //     toast.classList.remove("show");
    // }, 3000);
}


function checkForUpdates() {
    fetch('includes/checkPostsUpdates.php')
        .then(response => response.json())
        .then(data => {
            if (data.lastUpdate) {
                if (!lastUpdateTime) {
                    // Primera vez entrando: guarda la última actualización pero NO alerta
                    lastUpdateTime = data.lastUpdate;
                    sessionStorage.setItem("lastSeenUpdate", lastUpdateTime);
                } else if (data.lastUpdate > lastUpdateTime) {
                    lastUpdateTime = data.lastUpdate;
                    sessionStorage.setItem("lastSeenUpdate", lastUpdateTime);

                    if (data.uid != CURRENT_USER_ID) {
                        showToast('¡Hay una nueva publicación disponible! <a href="posts.php?pag=1&postperpage=10&tag=1">Ver</a>');
                    }
                }

            }
        })
        .catch(error => console.error("Error al verificar actualizaciones:", error));
}

// Solo ejecuta el checker si estás en posts.php
if (window.location.pathname.includes("posts.php")) {
    setInterval(checkForUpdates, 5000); // cada 5 segundos
}