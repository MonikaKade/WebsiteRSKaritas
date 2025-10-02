/* ================= Hero Slider ================= */
let slideIndex = 0;
showSlides();

function showSlides() {
  let slides = document.getElementsByClassName("slides");
  let dots = document.getElementsByClassName("dot");

  for (let i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }

  slideIndex++;
  if (slideIndex > slides.length) slideIndex = 1;

  for (let i = 0; i < dots.length; i++) {
    dots[i].classList.remove("active");
  }

  slides[slideIndex - 1].style.display = "block";
  dots[slideIndex - 1].classList.add("active");

  setTimeout(showSlides, 10000);
}

function plusSlides(n) {
  slideIndex += n - 1;
  showSlides();
}

function currentSlide(n) {
  slideIndex = n - 1;
  showSlides();
}

/* ================= ScrollSpy & Fade-in on Scroll ================= */
document.addEventListener("DOMContentLoaded", () => {
  const sections = document.querySelectorAll("section");
  const navLinks = document.querySelectorAll(".nav-menu a");
  const fadeSections = document.querySelectorAll(".fade-section");

  // ScrollSpy
  window.addEventListener("scroll", () => {
    let current = "";
    sections.forEach((section) => {
      const sectionTop = section.offsetTop - 80;
      const sectionHeight = section.clientHeight;
      if (pageYOffset >= sectionTop && pageYOffset < sectionTop + sectionHeight) {
        current = section.getAttribute("id");
      }
    });

    navLinks.forEach((link) => {
      link.classList.remove("active");
      if (link.getAttribute("href") === "#" + current) {
        link.classList.add("active");
      }
    });
  });

  // Fade-in effect
  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.2 });

  fadeSections.forEach(section => observer.observe(section));

  // Smooth scroll
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      document.querySelector(this.getAttribute('href')).scrollIntoView({
        behavior: 'smooth'
      });
    });
  });
});

/* ================= Dynamic Content ================= */

// Tentang Kami: SESUAIKAN/UBAH ISINYA YG DI DALAM TAG <P></P>
const contentDataTentangKami = {
  profil: `
    <h2>Profil</h2> 
    <p>
     Setelah kemerdekaan, pemerintah Indonesia terus meningkatkan kualitas dan cakupan pelayanan kesehatan, serta mendorong pengembangan tenaga kesehatan dan pembangunan rumah sakit baik di perkotaan maupun pedesaan. 
    Perkembangan Modern dan Etimologi
    Dari Latin:
    Kata "rumah sakit" (hospital) berasal dari bahasa Latin hospes, yang berarti tamu atau orang asing, dan hospitium yang berarti keramahtamahan. 
    Perkembangan Global:
    Konsep rumah sakit modern mulai berkembang di Eropa dan dibawa ke koloni-koloni di Amerika Utara, Afrika, dan Asia melalui eksplorasi. 
    Institusi Pendidikan:
    Di beberapa negara, termasuk Indonesia, rumah sakit mulai dirancang untuk berfungsi sebagai rumah sakit pendidikan yang mengintegrasikan pelayanan medis dan pendidikan kedokteran. 
    </p>
  `,
  visi: `
    <h2>Visi & Misi</h2>
    <p>
      <b>Visi:</b> Menjadi rumah sakit pelayanan terbaik di Sumba. <br><br>
      <b>Misi:</b> Terakreditasi paripurna, profesional, bermutu, pusat rujukan,
      penguatan SDM dan sarana, serta pengembangan jejaring.
    </p>
  `,
  sejarah: `
    <h2>Sejarah</h2>
    <ul>
      <li>Pelayanan kesehatan dimulai sejak 19 November 1958 ketika Sr. Regina merawat masyarakat di ruang sederhana di sekitar biara</li>
      <li>Klinik resmi berdiri pada 16 Februari 1959, dan kemudian pembangunan Rumah Sakit Karitas secara formal diresmikan pada 1 Juni 1961 oleh Kementerian Kesehatan</li>
      <li>Pada 17 Maret 1966, rumah sakit ini diberkati secara resmi dan mampu menampung sekitar 30–60 pasien rawat inap</li>
    </ul>
  `,
  salam: `
    <h2>Salam Direktur</h2>
    <p>
      Salam hangat dari Direktur Rumah Sakit Karitas Weetabula.
      Kami berkomitmen memberikan pelayanan terbaik kepada seluruh pasien
      dengan penuh empati dan profesionalisme.
    </p>
  `,
  penghargaan: `
    <h2>Penghargaan & Akreditasi</h2>
    <p>
      Rumah Sakit Karitas Weetabula telah menerima berbagai penghargaan
      nasional maupun internasional serta terakreditasi paripurna KARS.
    </p>
    <div style="text-align: center;">
     <img src="asset/download.jpeg" alt="IGD">
    </div>
  `,
};

// Layanan
const contentDataLayanan = {
  rawatjalan: `
    <h2>Rawat Inap</h2>
    <p>Perawatan intensif dengan fasilitas kamar nyaman dan pengawasan dokter serta perawat profesional.</p>
    <div class="poli-grid" id="rawat-inap-cards">
      <a href="" class="poli-card" data-content="ruangmaria">Ruang Maria</a>
      <a href="" class="poli-card" data-content="ruangelisabet">Ruang Elisabet</a>
      <a href="" class="poli-card" data-content="vip">VIP</a>
      <a href="" class="poli-card" data-content="vvip">VVIP</a>
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
      <a href="" class="poli-card" data-content="lab">Laboratorium</a>
      <a href="" class="poli-card" data-content="radiologi">Radiologi</a>
      <a href="" class="poli-card" data-content="fisioterapi">Fisioterapi</a>
      <a href="" class="poli-card" data-content="apotik">Apotik</a>
      <a href="" class="poli-card" data-content="rekammedik">Rekam Medik</a>
    </div>
    <div id="subContentLayanan" class="sub-content-box"></div>
  `,
};

// Penunjang Medis Sub Content
const contentDataFasilitas = {
  lab: `
    <h2>Laboratorium</h2>
    <p>Fasilitas laboratorium modern dengan peralatan lengkap untuk pemeriksaan darah, urin, dan lainnya.</p>
    <div class="layanan-grid">
      <img src="asset/lay1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
    </div>
  `,
  radiologi: `
    <h2>Radiologi</h2>
    <p>Layanan radiologi untuk kebutuhan X-Ray, USG, dan CT-Scan.</p>
        <div class="layanan-grid">
      <img src="asset/lay1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
    </div>
  `,
  fisioterapi: `
    <h2>Fisioterapi</h2>
    <p>Layanan fisioterapi untuk pemulihan cedera dan rehabilitasi pasien.</p>
        <div class="layanan-grid">
      <img src="asset/lay1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
    </div>
  `,
  apotik: `
    <h2>Apotik</h2>
    <p>Apotik dengan obat-obatan lengkap sesuai resep dokter.</p>
        <div class="layanan-grid">
      <img src="asset/lay1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
    </div>
  `,
  rekammedik: `
    <h2>Rekam Medik</h2>
    <p>Sistem rekam medis digital untuk pengelolaan data pasien.</p>
        <div class="layanan-grid">
      <img src="asset/lay1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
      <img src="asset/rj1.jpeg" class="img-square">
    </div>
  `,
};

const contentDataRawatInap = {
  ruangmaria: `
    <h2>Ruang Maria</h2>
    <p>Fasilitas kamar Ruang Maria dilengkapi dengan AC, kamar mandi dalam, dan televisi.</p>
    <img src="asset/ruang-maria.jpeg" alt="Ruang Maria">
  `,
  ruangelisabet: `
    <h2>Ruang Elisabet</h2>
    <p>Ruang Elisabet menawarkan suasana yang tenang dan fasilitas lengkap untuk pemulihan pasien.</p>
    <img src="asset/ruang-elisabet.jpeg" alt="Ruang Elisabet">
  `,
  vip: `
    <h2>Ruang VIP</h2>
    <p>Kamar VIP didesain untuk kenyamanan maksimal dengan ruang yang lebih luas dan fasilitas premium.</p>
    <img src="asset/ruang-vip.jpeg" alt="Ruang VIP">
  `,
  vvip: `
    <h2>Ruang VVIP</h2>
    <p>Ruang VVIP adalah pilihan terbaik dengan fasilitas super mewah dan pelayanan eksklusif.</p>
    <img src="asset/ruang-vvip.jpeg" alt="Ruang VVIP">
  `,
};

/* ================= Function Dynamic Content ================= */
function setupDynamicContent(sectionId, dataContent, defaultKey) {
  const section = document.getElementById(`content${capitalize(sectionId)}`);
  if (!section) return;

  const sidebar = document.querySelector(`#${sectionId} .sidebar ul`);
  if (sidebar) {
    sidebar.querySelectorAll("li").forEach(li => {
      li.addEventListener("click", () => {
        sidebar.querySelectorAll("li").forEach(item => item.classList.remove("active"));
        li.classList.add("active");
        section.innerHTML = dataContent[li.getAttribute("data-content")];
      });
    });
  }

  section.innerHTML = dataContent[defaultKey];
}

function capitalize(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}

/* ================= INIT ================= */
document.addEventListener("DOMContentLoaded", () => {
  // Tentang Kami
  setupDynamicContent("tentangkami", contentDataTentangKami, "profil");

  // Layanan
  setupDynamicContent("layanan", contentDataLayanan, "rawatjalan");

  // Sub penunjang medis
  const layananSection = document.getElementById("contentLayanan");
  layananSection.addEventListener("click", (e) => {
    if (e.target && e.target.getAttribute("data-content")) {
      const key = e.target.getAttribute("data-content");
      const subContent = document.getElementById("subContentLayanan");
      if (contentDataFasilitas[key]) {
        document.querySelectorAll("#contentLayanan .sidebar ul li").forEach(li => li.classList.remove("active"));
        e.target.classList.add("active");
        subContent.innerHTML = contentDataFasilitas[key];
      }
    }
  });

  // Form kontak
  const formKontak = document.getElementById("formKontak");
  if (formKontak) {
    formKontak.addEventListener("submit", function (e) {
      e.preventDefault();
      alert("Terima kasih, pesan Anda sudah terkirim!");
      this.reset();
    });
  }
});

// Event klik tombol untuk Penunjang Medis dan Rawat Inap
document.addEventListener("click", function (e) {
  // Logika untuk Penunjang Medis
  if (e.target.classList.contains("poli-card") && e.target.closest("#penunjang-medis-cards")) {
    e.preventDefault();
    const cardsContainer = document.getElementById("penunjang-medis-cards");
    const subContent = document.getElementById("subContentLayanan");
    const key = e.target.dataset.content;

    if (cardsContainer && subContent && contentDataFasilitas[key]) {
      cardsContainer.style.display = "none";
      subContent.innerHTML = contentDataFasilitas[key];
      subContent.style.display = "block";
    }
  }

  // Logika untuk Rawat Inap
  if (e.target.classList.contains("poli-card") && e.target.closest("#rawat-inap-cards")) {
    e.preventDefault();
    const cardsContainer = document.getElementById("rawat-inap-cards");
    const subContent = document.getElementById("subContentRawatInap");
    const key = e.target.dataset.content;

    if (cardsContainer && subContent && contentDataRawatInap[key]) {
      cardsContainer.style.display = "none";
      subContent.innerHTML = contentDataRawatInap[key];
      subContent.style.display = "block";
    }
  }
});

// Default tampil Laboratorium saat pertama kali masuk
const subContentLayanan = document.getElementById("subContentLayanan");
if (subContentLayanan) {
  subContentLayanan.innerHTML = contentDataFasilitas.lab;
}

// Default tampil Ruang Maria saat pertama kali masuk
const subContentRawatInap = document.getElementById("subContentRawatInap");
if (subContentRawatInap) {
  subContentRawatInap.innerHTML = contentDataRawatInap.ruangmaria;
}

// Event Listener Tentang Kami
document.querySelectorAll('#tentangkami .sidebar li').forEach((item) => {
  item.addEventListener('click', () => {
    document.querySelectorAll('#tentangkami .sidebar li').forEach((li) => {
      li.classList.remove('active');
    });
    item.classList.add('active');
    const key = item.getAttribute('data-content');
    document.getElementById('contentTentangKami').innerHTML = contentDataTentangKami[key];
  });
});

// tampilkan default (misalnya profil)
document.getElementById('contentTentangKami').innerHTML = contentDataTentangKami['profil'];

