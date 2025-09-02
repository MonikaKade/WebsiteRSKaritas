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

/* ================= ScrollSpy ================= */
document.addEventListener("DOMContentLoaded", () => {
  const sections = document.querySelectorAll("section");
  const navLinks = document.querySelectorAll(".nav-menu a");

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
});

/* ================= Tentang Kami ================= */
const contentTentangKami = {
  profil: `
    <h2>Profil</h2>
    <p>
      Rumah Sakit Royal Surabaya terletak di Jl. Rungkut Industri I/1 
      dan berada di bawah naungan PT. Prima Karya Husada.  
      Rumah Sakit Royal Surabaya senantiasa bekerja keras untuk menjadi pilihan 
      pertama bagi individu yang membutuhkan pelayanan kesehatan, 
      sumber daya manusia yang tulus memberikan sumbangsih di bidang kesehatan, 
      didukung shareholders yang peduli terhadap sesama lingkungan.
    </p>
  `,
  visi: `
    <h2>Visi & Misi</h2>
    <p>
      <b>Visi:</b> Menjadi rumah sakit pilihan utama dengan pelayanan profesional dan ramah lingkungan. <br><br>
      <b>Misi:</b> Memberikan pelayanan kesehatan berkualitas, berorientasi pada pasien, 
      dan berlandaskan etika profesi.
    </p>
  `,
  sejarah: `
    <h2>Sejarah</h2>
    <p>
      Rumah Sakit Royal Surabaya berdiri pada tahun XXXX sebagai bagian dari 
      komitmen meningkatkan pelayanan kesehatan di Indonesia.
    </p>
  `,
  salam: `
    <h2>Salam Direktur</h2>
    <p>
      Salam hangat dari Direktur Rumah Sakit Royal Surabaya.  
      Kami berkomitmen memberikan pelayanan terbaik kepada seluruh pasien 
      dengan penuh empati dan profesionalisme.
    </p>
  `,
  penghargaan: `
    <h2>Penghargaan & Akreditasi</h2>
    <p>
      Rumah Sakit Royal Surabaya telah menerima berbagai penghargaan 
      nasional maupun internasional serta terakreditasi paripurna KARS.
    </p>
  `,
};

function showContentTentangKami(type, event) {
  document.getElementById("contentTentangKami").innerHTML = contentTentangKami[type];
  let lis = event.target.parentNode.querySelectorAll("li");
  lis.forEach((li) => li.classList.remove("active"));
  event.target.classList.add("active");
}

/* ================= Layanan ================= */
function showContentLayanan(type, event) {
  let submenu = document.getElementById("submenu");
  let dokterDetail = document.getElementById("dokterDetail");
  submenu.innerHTML = "";
  dokterDetail.innerHTML = "";

  if (type === "rawatjalan") {
    submenu.innerHTML = `
      <h2>Rawat Jalan</h2>
      <p>Layanan rawat jalan tersedia untuk pasien umum maupun rujukan.</p>
    `;
  } else if (type === "igd") {
    submenu.innerHTML = `
      <h2>IGD 24 Jam</h2>
      <p>Layanan gawat darurat 24 jam dengan tenaga medis profesional.</p>
    `;
  } else if (type === "rawatinap") {
    submenu.innerHTML = `
      <h2>Rawat Inap</h2>
      <p>Pilih Poliklinik:</p>
      <button class="submenu-btn" onclick="showDokter('poli-anak')">Poli Anak</button>
      <button class="submenu-btn" onclick="showDokter('poli-bedah')">Poli Bedah</button>
      <button class="submenu-btn" onclick="showDokter('poli-umum')">Poli Umum</button>
    `;
  }

  let lis = event.target.parentNode.querySelectorAll("li");
  lis.forEach((li) => li.classList.remove("active"));
  event.target.classList.add("active");
}

function showDokter(poli) {
  let dokterDetail = document.getElementById("dokterDetail");
  const data = {
    "poli-anak": `
      <div class="dokter-card">
        <h3>dr. Andi</h3>
        <p>Spesialis Anak</p>
        <p>STR: 123456</p>
        <p>Jadwal: Senin - Rabu 08:00 - 12:00</p>
      </div>
    `,
    "poli-bedah": `
      <div class="dokter-card">
        <h3>dr. Budi</h3>
        <p>Spesialis Bedah</p>
        <p>STR: 789101</p>
        <p>Jadwal: Selasa - Kamis 09:00 - 13:00</p>
      </div>
    `,
    "poli-umum": `
      <div class="dokter-card">
        <h3>dr. Citra</h3>
        <p>Dokter Umum</p>
        <p>STR: 112233</p>
        <p>Jadwal: Setiap Hari 08:00 - 16:00</p>
      </div>
    `,
  };
  dokterDetail.innerHTML = data[poli];
}
/* ============ Fade-in on Scroll ============ */
document.addEventListener("DOMContentLoaded", () => {
  const fadeSections = document.querySelectorAll(".fade-section");

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
        observer.unobserve(entry.target); // biar animasi sekali saja
      }
    });
  }, { threshold: 0.2 });

  fadeSections.forEach(section => observer.observe(section));
});


/* ================= Fasilitas ================= */
const contentFasilitas = {
  lab: `
    <h2>Laboratorium</h2>
    <p>
      Fasilitas laboratorium modern dengan peralatan lengkap untuk pemeriksaan darah, urin, dan lainnya.
    </p>
  `,
  radiologi: `
    <h2>Radiologi</h2>
    <p>
      Radiologi dengan teknologi CT-Scan, USG, dan X-Ray untuk diagnosis akurat.
    </p>
  `,
  fisioterapi: `
    <h2>Fisioterapi</h2>
    <p>
      Layanan fisioterapi untuk rehabilitasi pasien dengan tenaga profesional dan berpengalaman.
    </p>
  `,
  apotik: `
    <h2>Apotik</h2>
    <p>
      Apotek rumah sakit menyediakan obat-obatan lengkap sesuai resep dokter, buka 24 jam.
    </p>
  `,
};

function showContentFasilitas(type, event) {
  document.getElementById("contentFasilitas").innerHTML = contentFasilitas[type];

  // aktifkan tab di sidebar fasilitas
  let lis = event.target.parentNode.querySelectorAll("li");
  lis.forEach((li) => li.classList.remove("active"));
  event.target.classList.add("active");
}

/* ================= Kontak Form Handler ================= */
document.getElementById("formKontak").addEventListener("submit", function (e) {
  e.preventDefault();
  alert("Terima kasih, pesan Anda sudah terkirim!");
  this.reset();
});
