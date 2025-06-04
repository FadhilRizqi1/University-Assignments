document.querySelectorAll("nav a").forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    const href = this.getAttribute("href");

    // Cek apakah href adalah ID elemen di halaman yang sama
    if (href.startsWith("#")) {
      e.preventDefault(); // Mencegah perilaku default hanya untuk ID
      document.querySelector(href).scrollIntoView({
        behavior: "smooth",
      });
    }
    // Jika bukan ID, biarkan tautan berfungsi seperti biasa
  });
});

// const departurePort = document.getElementById('departure-port');
// const destinationPort = document.getElementById('destination-port');

// function updateDepartureOptions() {
//     const selectedDestination = destinationPort.value;

//     // Enable all options
//     for (let option of departurePort.options) {
//         option.disabled = false;
//     }

//     // Disable the selected destination port in departure port options
//     if (selectedDestination) {
//         for (let option of departurePort.options) {
//             if (option.value === selectedDestination) {
//                 option.disabled = true;
//             }
//         }
//     }
// }

// function updateDestinationOptions() {
//     const selectedDeparture = departurePort.value;

//     // Enable all options
//     for (let option of destinationPort.options) {
//         option.disabled = false;
//     }

//     // Disable the selected departure port in destination port options
//     if (selectedDeparture) {
//         for (let option of destinationPort.options) {
//             if (option.value === selectedDeparture) {
//                 option.disabled = true;
//             }
//         }
//     }
// }

// // Listen to changes on both selects
// departurePort.addEventListener('change', updateDestinationOptions);
// destinationPort.addEventListener('change', updateDepartureOptions);

// Ambil elemen <select> untuk asal dan tujuan
const departurePort = document.getElementById("departure-port");
const arrivalPort = document.getElementById("destination-port");
// Tambahkan event listener untuk mendeteksi perubahan pada departurePort
departurePort.addEventListener("change", function () {
  // Periksa nilai yang dipilih
  if (departurePort.value === "Bakauheni, Lampung") {
    arrivalPort.value = "Merak, Banten"; // Pilih Merak di arrivalPort
  } else if (departurePort.value === "Merak, Banten") {
    arrivalPort.value = "Bakauheni, Lampung"; // Pilih Bakauheni di arrivalPort
  }
});

arrivalPort.addEventListener("change", function () {
  if (arrivalPort.value === "Merak, Banten") {
    departurePort.value = "Bakauheni, Lampung"; // Pilih Bakauheni di arrivalPort
  } else if (arrivalPort.value === "Bakauheni, Lampung") {
    departurePort.value = "Merak, Banten"; // Pilih Bakauheni di arrivalPort
  }
});
