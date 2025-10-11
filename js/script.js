// ========================
// SLIDER
// ========================
let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}
function currentSlide(n) {
  showSlides(slideIndex = n);
}
function showSlides(n) {
  let slides = document.getElementsByClassName("slides");
  let dots = document.getElementsByClassName("dot");
  if (slides.length === 0) return;

  if (n > slides.length) slideIndex = 1;
  if (n < 1) slideIndex = slides.length;

  for (let i = 0; i < slides.length; i++) slides[i].style.display = "none";
  for (let i = 0; i < dots.length; i++) dots[i].classList.remove("active");

  slides[slideIndex - 1].style.display = "block";
  dots[slideIndex - 1].classList.add("active");
}
setInterval(() => plusSlides(1), 5000);

// ========================
// DATA KONTEN LAYANAN
// ========================
const contentDataLayanan = {
  rawatjalan: `
    <h2>Rawat Inap</h2>
    <p>Perawatan intensif dengan fasilitas kamar nyaman dan pengawasan dokter serta perawat profesional.</p>
    <div class="poli-grid" id="rawat-inap-cards">
      <a href="#" class="poli-card" data-content="ruangmaria">Ruang Maria</a>
      <a href="#" class="poli-card" data-content="ruangelisabet">Ruang Elisabet</a>
      <a href="#" class="poli-card" data-content="vip">VIP</a>
      <a href="#" class="poli-card" data-content="vvip">VVIP</a>
    </div>
    <div id="subContentRawatInap" class="sub-content-box"></div>
  `,
  igd: `
    <h2>IGD 24 Jam</h2>
    <p>Siaga 24 jam untuk menangani kondisi darurat yang membutuhkan pertolongan cepat dan tepat.</p>
    <div class="layanan-grid">
      <img src="asset/lay2.jpeg" alt="IGD" class="img-square">
      <img src="asset/igd.jpeg" alt="IGD" class="img-square">
    </div>
  `,
  rawatinap: `
    <h2>Rawat Jalan</h2>
    <p>Pilih Poliklinik:</p>
    <div class="poli-grid">
      <a href="pages/dokter.php?poli_id=1" class="poli-card">Poli Anak</a>
      <a href="pages/dokter.php?poli_id=2" class="poli-card">Poli Gigi</a>
      <a href="pages/dokter.php?poli_id=3" class="poli-card">Poli Umum</a>
      <a href="pages/dokter.php?poli_id=4" class="poli-card">Poli Bedah</a>
      <a href="pages/dokter.php?poli_id=5" class="poli-card">Penyakit Dalam</a>
      <a href="pages/dokter.php?poli_id=6" class="poli-card">Poli Obgyn</a>
      <a href="pages/dokter.php?poli_id=7" class="poli-card">Poli Mata</a>
      <a href="pages/dokter.php?poli_id=8" class="poli-card">Poli Saraf</a>
      <a href="pages/dokter.php?poli_id=9" class="poli-card">Poli TB</a>
      <a href="pages/dokter.php?poli_id=10" class="poli-card">Poli THT</a>
      <a href="pages/dokter.php?poli_id=11" class="poli-card">Poli BKIA</a>
      <a href="pages/dokter.php?poli_id=12" class="poli-card">Poli VCT</a>
    </div>
  `,
  penunjangmedis: `
    <h2>Penunjang Medis</h2>
    <p>Pilih layanan penunjang medis:</p>
    <div class="poli-grid" id="penunjang-medis-cards">
      <a href="#" class="poli-card" data-content="lab">Laboratorium</a>
      <a href="#" class="poli-card" data-content="radiologi">Radiologi</a>
      <a href="#" class="poli-card" data-content="fisioterapi">Fisioterapi</a>
      <a href="#" class="poli-card" data-content="apotik">Apotik</a>
      <a href="#" class="poli-card" data-content="rekammedik">Rekam Medik</a>
    </div>
    <div id="subContentLayanan" class="sub-content-box"></div>
  `
};

const contentDataFasilitas = {
  lab: `
    <h2>Laboratorium</h2>
    <p>Laboratorium modern dengan alat lengkap untuk pemeriksaan darah, urin, dan lainnya.</p>
    <div class="layanan-grid">
      <img src="asset/lay1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
    </div>
  `,
  radiologi: `
    <h2>Radiologi</h2>
    <p>Layanan X-Ray, USG, dan CT-Scan.</p>
    <div class="layanan-grid">
      <img src="asset/lay1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
    </div>
  `,
  fisioterapi: `
    <h2>Fisioterapi</h2>
    <p>Pemulihan cedera dan rehabilitasi pasien dengan terapis berpengalaman.</p>
    <div class="layanan-grid">
      <img src="asset/lay1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
    </div>
  `,
  apotik: `
    <h2>Apotik</h2>
    <p>Tersedia berbagai obat-obatan sesuai resep dokter dengan layanan cepat dan ramah.</p>
    <div class="layanan-grid">
      <img src="asset/lay1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
    </div>
  `,
  rekammedik: `
    <h2>Rekam Medik</h2>
    <p>Pengelolaan data pasien menggunakan sistem rekam medis digital yang aman dan efisien.</p>
    <div class="layanan-grid">
      <img src="asset/lay1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
    </div>
  `
};

const contentDataRawatInap = {
  ruangmaria: `
    <h2>Ruang Maria</h2>
    <p>Fasilitas kamar Ruang Maria dilengkapi dengan AC, kamar mandi dalam, dan televisi.</p>
    <img src="asset/ruang-maria.jpeg" alt="Ruang Maria" class="img-square">
  `,
  ruangelisabet: `
    <h2>Ruang Elisabet</h2>
    <p>Ruang dengan suasana tenang dan fasilitas lengkap untuk pemulihan pasien.</p>
    <img src="asset/ruang-elisabet.jpeg" alt="Ruang Elisabet" class="img-square">
  `,
  vip: `
    <h2>Ruang VIP</h2>
    <p>Ruang lebih luas dengan fasilitas premium untuk kenyamanan maksimal.</p>
    <img src="asset/ruang-vip.jpeg" alt="Ruang VIP" class="img-square">
  `,
  vvip: `
    <h2>Ruang VVIP</h2>
    <p>Fasilitas super mewah dengan pelayanan eksklusif dan privasi penuh.</p>
    <img src="asset/ruang-vvip.jpeg" alt="Ruang VVIP" class="img-square">
  `
};

// ========================
// HANDLER UNTUK LAYANAN
// ========================
const layananSidebar = document.querySelector("#layanan .sidebar");
const contentLayanan = document.getElementById("contentLayanan");

if (layananSidebar) {
  layananSidebar.querySelectorAll("li").forEach(li => {
    li.addEventListener("click", e => {
      e.preventDefault();
      layananSidebar.querySelectorAll("li").forEach(l => l.classList.remove("active"));
      li.classList.add("active");

      const key = li.dataset.content;
      contentLayanan.innerHTML = contentDataLayanan[key] || "<p>Konten tidak ditemukan.</p>";

      // Tambahkan event listener untuk sub layanan setelah konten dimuat
      setTimeout(() => {
        const poliCards = document.querySelectorAll("#penunjang-medis-cards .poli-card");
        poliCards.forEach(card => {
          card.addEventListener("click", ev => {
            ev.preventDefault();
            const subKey = card.dataset.content;
            document.getElementById("subContentLayanan").innerHTML = contentDataFasilitas[subKey] || "";
          });
        });

        const rawatCards = document.querySelectorAll("#rawat-inap-cards .poli-card");
        rawatCards.forEach(card => {
          card.addEventListener("click", ev => {
            ev.preventDefault();
            const subKey = card.dataset.content;
            document.getElementById("subContentRawatInap").innerHTML = contentDataRawatInap[subKey] || "";
          });
        });
      }, 100);
    });
  });
}

// ========================
// SIDEBAR “Tentang Kami” (Dummy loader)
// ========================
document.querySelectorAll("#tentangkami .sidebar ul li").forEach(item => {
  item.addEventListener("click", () => {
    const sidebar = item.closest(".sidebar");
    sidebar.querySelectorAll("li").forEach(li => li.classList.remove("active"));
    item.classList.add("active");
    const contentBox = sidebar.parentElement.querySelector(".content");
    contentBox.innerHTML = `<div class='loading'>Memuat...</div>`;
    setTimeout(() => {
      contentBox.innerHTML = `
        <h3>${item.textContent}</h3>
        <p>
          Konten ${item.textContent} sedang dalam pengembangan. 
          Silakan kunjungi kembali nanti untuk informasi lengkap.
        </p>`;
    }, 400);
  });
});

// ========================
// KONTAK FORM
// ========================
const formKontak = document.getElementById("formKontak");
if (formKontak) {
  formKontak.addEventListener("submit", e => {
    e.preventDefault();
    alert("Pesan Anda telah terkirim! Terima kasih telah menghubungi kami.");
    formKontak.reset();
  });
}

// ========================
// ANIMASI FADE SECTION
// ========================
const fadeSections = document.querySelectorAll(".fade-section");
const fadeObserver = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = 1;
      entry.target.style.transform = "translateY(0)";
    }
  });
}, { threshold: 0.2 });

fadeSections.forEach(sec => {
  sec.style.opacity = 0;
  sec.style.transform = "translateY(40px)";
  sec.style.transition = "opacity 0.8s ease, transform 0.8s ease";
  fadeObserver.observe(sec);
});

// ========================
// ANIMASI MUNCUL SAAT SCROLL (Fade-up)
// ========================
const fadeElements = document.querySelectorAll('.fade-up');

const fadeObserver2 = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('show');
      // Jika tidak ingin animasi diulang saat di-scroll balik ke atas:
      fadeObserver2.unobserve(entry.target);
    }
  });
}, { threshold: 0.2 });

fadeElements.forEach(el => fadeObserver2.observe(el));

document.getElementById("formDaftarOnline").addEventListener("submit", function(e) {
  e.preventDefault();
  alert("Pendaftaran berhasil dikirim! Kami akan segera menghubungi Anda.");
  this.reset();
});


