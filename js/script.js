
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
    dots[i].className = dots[i].className.replace(" active", "");
  }

  slides[slideIndex - 1].style.display = "block";
  dots[slideIndex - 1].className += " active";

  setTimeout(showSlides, 4000);
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

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.2 });

  fadeSections.forEach(section => observer.observe(section));

  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      document.querySelector(this.getAttribute('href')).scrollIntoView({
        behavior: 'smooth'
      });
    });
  });
});

/* ================= Dynamic Content Handlers ================= */

// Data untuk konten "Tentang Kami"
const contentDataTentangKami = {
    profil: `
        <h2>Profil</h2>
        <p>
            Rumah Sakit Umum Karitas di Weetabula merupakan salah satu unit pelayanan kesehatan
            yang dikelola oleh Kongregasi Suster Amalkasih Darah Mulia (ADM) melalui Yayasan Karitas Katolik Sumba
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
    `,
};

// Data untuk konten "Layanan"
const contentDataLayanan = {
    rawatjalan: `
        <h2>Rawat Jalan</h2>
        <p>Layanan pemeriksaan dan konsultasi kesehatan tanpa perlu menginap. Praktis untuk kontrol rutin atau tindakan medis ringan.</p>
        <div class="layanan-grid">
            <img src="asset/lay1.jpeg" alt="rawat jalan" class="img-square">
            <img src="asset/rj1.jpeg" alt="rawat jalan" class="img-square">
            <img src="asset/rj1.jpeg" alt="rawat jalan" class="img-square">
        </div>
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
        <h2>Rawat Inap</h2>
        <p>Perawatan intensif dengan fasilitas kamar nyaman dan pengawasan dokter serta perawat profesional.</p>
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
};

// Data untuk konten "Fasilitas"
const contentDataFasilitas = {
    lab: `
        <h2>Laboratorium</h2>
        <p>Fasilitas laboratorium modern dengan peralatan lengkap untuk pemeriksaan darah, urin, dan lainnya.</p>
        <div class="layanan-grid">
            <img src="asset/lay1.jpeg" alt="rawat jalan" class="img-square">
            <img src="asset/rj1.jpeg" alt="rawat jalan" class="img-square">
            <img src="asset/rj1.jpeg" alt="rawat jalan" class="img-square">
        </div>
    `,
    radiologi: `
        <h2>Radiologi</h2>
        <p>Radiologi dengan teknologi CT-Scan, USG, dan X-Ray untuk diagnosis akurat.</p>
    `,
    fisioterapi: `
        <h2>Fisioterapi</h2>
        <p>Layanan fisioterapi untuk rehabilitasi pasien dengan tenaga profesional dan berpengalaman.</p>
    `,
    apotik: `
        <h2>Apotik</h2>
        <p>Apotek rumah sakit menyediakan obat-obatan lengkap sesuai resep dokter, buka 24 jam.</p>
    `,
};

// Fungsi umum untuk menangani pergantian konten dan menetapkan konten default
function setupDynamicContent(sectionId, contentMap, defaultContentType) {
    const section = document.getElementById(sectionId);
    if (!section) return;

    const contentDiv = section.querySelector('.content');
    const sidebarItems = section.querySelectorAll('.sidebar li');

    // Tampilkan konten default saat halaman dimuat
    if (defaultContentType && contentMap[defaultContentType]) {
        contentDiv.innerHTML = contentMap[defaultContentType];
        // Tandai item sidebar default sebagai aktif
        const defaultItem = Array.from(sidebarItems).find(item => item.dataset.content === defaultContentType);
        if (defaultItem) {
            defaultItem.classList.add('active');
        }
    }

    // Tambahkan event listener untuk perubahan konten
    sidebarItems.forEach(item => {
        item.addEventListener('click', (event) => {
            // Hapus kelas 'active' dari semua item sidebar
            sidebarItems.forEach(i => i.classList.remove('active'));

            // Tambahkan kelas 'active' pada item yang diklik
            event.target.classList.add('active');

            // Ganti konten sesuai data-content
            const contentType = event.target.dataset.content;
            if (contentMap[contentType]) {
                contentDiv.innerHTML = contentMap[contentType];
            }
        });
    });
}

// Inisialisasi konten dinamis untuk setiap bagian
document.addEventListener("DOMContentLoaded", () => {
    // Tetapkan konten default untuk setiap bagian saat halaman dimuat
    setupDynamicContent('tentangkami', contentDataTentangKami, 'profil');
    setupDynamicContent('layanan', contentDataLayanan, 'rawatjalan');
    setupDynamicContent('fasilitas', contentDataFasilitas, 'lab');

    // Handler untuk form kontak
    document.getElementById("formKontak").addEventListener("submit", function (e) {
        e.preventDefault();
        alert("Terima kasih, pesan Anda sudah terkirim!");
        this.reset();
    });
});