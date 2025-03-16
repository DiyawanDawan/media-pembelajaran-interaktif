document.addEventListener("DOMContentLoaded", () => {
    // Mengecek tema yang tersimpan di localStorage
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "dark") {
        document.documentElement.setAttribute("data-theme", "dark");
    }

    // Tambahkan event listener untuk klik di luar sidebar
    document.addEventListener("click", closeSidebarOnClickOutside);
});

function toggleTheme() {
    const root = document.documentElement;
    const currentTheme = root.getAttribute("data-theme");

    if (currentTheme === "dark") {
        root.removeAttribute("data-theme");
        localStorage.setItem("theme", "light"); // Simpan preferensi
    } else {
        root.setAttribute("data-theme", "dark");
        localStorage.setItem("theme", "dark"); // Simpan preferensi
    }
}

// Fungsi untuk toggle sidebar
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");

    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
}

// Fungsi untuk menutup sidebar saat klik di luar sidebar
function closeSidebarOnClickOutside(event) {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");
    const sidebarToggle = document.querySelector(".sidebar-toggle");

    if (sidebar.classList.contains("active")) {
        // Cek apakah klik terjadi di luar sidebar DAN bukan pada tombol toggle
        if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
            sidebar.classList.remove("active"); // Tutup sidebar
            overlay.classList.remove("active"); // Sembunyikan overlay
        }
    }
}
