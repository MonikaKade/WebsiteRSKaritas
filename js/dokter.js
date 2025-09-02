const dataDokter = {
  anak: [
    { 
      nama: "dr. Siti Aminah, Sp.A", 
      spesialis: "Spesialis Anak", 
      izin: "No. SIP: 12345", 
      foto: "asset/siti.jpeg", 
      jadwal: [
        { hari: "Senin", jam: "08:00 - 12:00" },
        { hari: "Rabu", jam: "10:00 - 14:00" }
      ] 
    },
    {
        nama: "dr. Rina Kusuma, Sp.A", 
        spesialis: "Spesialis Anak", 
        izin: "No. SIP: 54321", 
        foto: "asset/rina.jpeg", 
        jadwal: [
        { hari: "Selasa", jam: "09:00 - 13:00" },
        { hari: "Kamis", jam: "10:00 - 14:00" }
        ] 
    }
  ],
  gigi: [
    { 
      nama: "drg. Budi Santoso", 
      spesialis: "Spesialis Gigi", 
      izin: "No. SIP: 67890", 
      foto: "img/dokter-gigi1.jpg", 
      jadwal: [
        { hari: "Selasa", jam: "09:00 - 13:00" },
        { hari: "Jumat", jam: "13:00 - 16:00" }
      ] 
    }  
],
    
  umum: [
    { 
      nama: "dr. Andi Wijaya", 
      spesialis: "Dokter Umum", 
      izin: "No. SIP: 11223", 
      foto: "img/dokter-umum1.jpg", 
      jadwal: [
        { hari: "Setiap Hari", jam: "08:00 - 16:00" }
      ] 
    }
  ]
};

// ambil query param poli
const params = new URLSearchParams(window.location.search);
const poliDipilih = params.get("poli");

// ambil elemen
const judulPoli = document.getElementById("judul-poli");
const container = document.getElementById("dokter-container");

if (poliDipilih && dataDokter[poliDipilih]) {
  judulPoli.textContent = "Dokter Poli " + poliDipilih.charAt(0).toUpperCase() + poliDipilih.slice(1);

  dataDokter[poliDipilih].forEach(dokter => {
    const card = document.createElement("div");
    card.className = "dokter-card";
    card.innerHTML = `
      <img src="${dokter.foto}" alt="${dokter.nama}" class="dokter-foto">
      <div class="dokter-info">
        <h3>${dokter.nama}</h3>
        <p><strong>${dokter.spesialis}</strong></p>
        <p>${dokter.izin}</p>
        <h4>Jadwal Praktek:</h4>
        <ul>
          ${dokter.jadwal.map(j => `<li>${j.hari} - ${j.jam}</li>`).join("")}
        </ul>
      </div>
    `;
    container.appendChild(card);
  });
} else {
  judulPoli.textContent = "Poli tidak ditemukan";
}
