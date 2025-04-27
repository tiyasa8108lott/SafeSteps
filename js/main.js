function sendSOS() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(position => {
      const lat = position.coords.latitude;
      const lon = position.coords.longitude;
      const location = `https://www.google.com/maps?q=${lat},${lon}`;

      const user_id = localStorage.getItem("user_id");

      fetch("php/send_alert.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `user_id=${user_id}&location=${location}`
      })
      .then(res => res.text())
      .then(data => {
        alert("SOS sent!");
      });
    });
  } else {
    alert("Geolocation not supported");
  }
}