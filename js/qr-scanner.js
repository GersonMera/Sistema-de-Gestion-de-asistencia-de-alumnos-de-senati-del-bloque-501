function iniciarEscaneo() {
    const html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", { fps: 10, qrbox: 250 }
    );

    function onScanSuccess(decodedText, decodedResult) {
        // Enviar el código QR al servidor
        fetch('process_attendance.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                qr_code: decodedText
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if(data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la asistencia');
        });

        html5QrcodeScanner.clear();
    }

    html5QrcodeScanner.render(onScanSuccess);
}