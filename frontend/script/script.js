document.addEventListener("DOMContentLoaded", () => {
    // Toggle Tema
    function toggleTheme() {
        const root = document.documentElement;
        const currentTheme = root.getAttribute("data-theme") || "light";
        const newTheme = currentTheme === "dark" ? "light" : "dark";

        root.setAttribute("data-theme", newTheme);
        localStorage.setItem("theme", newTheme);
        updateButtonText(newTheme);
    }

    function updateButtonText(theme) {
        const toggleThemeBtn = document.getElementById("toggle-theme");
        if (toggleThemeBtn) {
            toggleThemeBtn.innerHTML = theme === "dark" ? "☀️ Light Mode" : "🌙 Dark Mode";
        }
    }

    // Mengecek tema saat halaman dimuat
    const savedTheme = localStorage.getItem("theme") || "light";
    document.documentElement.setAttribute("data-theme", savedTheme);
    updateButtonText(savedTheme);

    // Event listener untuk tombol toggle tema
    document.getElementById("toggle-theme")?.addEventListener("click", toggleTheme);

   
        const navbarList = document.getElementById("navbarList");
        const menuToggle = document.getElementById("menuToggle");
    
        // Fungsi untuk toggle navbar
        function toggleNavbar() {
            navbarList.classList.toggle("open");
        }
    
        // Event listener untuk tombol toggle
        menuToggle.addEventListener("click", (e) => {
            e.stopPropagation(); // Mencegah event bubbling
            toggleNavbar();
        });
    
        // Tutup navbar jika klik di luar menu
        document.addEventListener("click", (event) => {
            if (!navbarList.contains(event.target) && !menuToggle.contains(event.target)) {
                navbarList.classList.remove("open");
            }
        });
    

    // Scroll halus untuk semua link
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            target?.scrollIntoView({ behavior: 'smooth' });

            // Tutup menu setelah klik
            navbarList.classList.remove("open");
        });
    });
});