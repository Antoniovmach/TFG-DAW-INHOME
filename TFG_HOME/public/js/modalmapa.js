document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("openModalBtn").addEventListener("click", function() {
        document.getElementById("modal").classList.add("show");
    });

    window.onclick = function(event) {
        if (event.target == document.getElementById("modal")) {
            document.getElementById("modal").classList.remove("show");
        }
    };

    document.getElementById("comprarBtn").addEventListener("click", function(event) {
        event.preventDefault();

        var userId = document.getElementById("idUsuario").innerText;

        fetch('/api/Usuario/comprar-premium', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ userId: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
            } else if (data.error) {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al procesar la solicitud.');
        });
    });
});
