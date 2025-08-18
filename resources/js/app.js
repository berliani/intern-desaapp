import "./bootstrap";
import Alpine from "alpinejs";

// Lazy load komponen yang tidak segera dibutuhkan
const loadOptionalDependencies = () => {
    // Import Font Awesome hanya jika belum dimuat
    if (!document.querySelector('link[href*="font-awesome"]')) {
        import("@fortawesome/fontawesome-free/css/all.css");
    }
};

// Fungsi untuk memuat AOS dengan lazy loading
const initAOS = async () => {
    // Load AOS asynchronously
    const AOS = (await import("aos")).default;
    await import("aos/dist/aos.css");

    AOS.init({
        duration: 800,
        once: true,
        offset: 100,
        // Mengurangi animasi pada perangkat mobile untuk performa lebih baik
        disable: window.innerWidth < 768 && "phone",
        // Mempercepat render dengan membatasi jumlah item yang dianimasikan
        startEvent: "DOMContentLoaded",
    });

    // Untuk memperbaiki AOS pada halaman-halaman Livewire
    document.addEventListener("livewire:navigated", () => {
        AOS.refresh();
    });

    // Optimalkan animasi saat scroll dengan throttling
    let scrolling = false;
    window.addEventListener("scroll", () => {
        if (!scrolling) {
            window.requestAnimationFrame(() => {
                if (window.scrollY > 100) {
                    loadOptionalDependencies();
                }
                scrolling = false;
            });
            scrolling = true;
        }
    });
};

// Deteksi jika pengguna memilih preferensi reduced motion
const prefersReducedMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)"
).matches;

// Saat dokumen sudah siap
document.addEventListener("DOMContentLoaded", () => {
    // Inisialisasi fitur utama

    // Jika pengguna tidak memilih reduced motion, inisialisasi AOS
    if (!prefersReducedMotion) {
        // Delay AOS initialization slightly to prioritize critical rendering
        setTimeout(() => {
            initAOS();
        }, 100);
    }

    // Tambahkan smooth scrolling dengan performa yang lebih baik
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();

            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                // Gunakan scrollIntoView dengan opsi behavior: 'smooth' hanya jika pengguna tidak memilih reduced motion
                target.scrollIntoView({
                    behavior: prefersReducedMotion ? "auto" : "smooth",
                    block: "start",
                });
            }
        });
    });

    // Mulai memuat komponen non-kritis setelah komponen utama dimuat
    if ("requestIdleCallback" in window) {
        requestIdleCallback(() => {
            loadOptionalDependencies();
        });
    } else {
        // Fallback untuk browser yang tidak mendukung requestIdleCallback
        setTimeout(loadOptionalDependencies, 1000);
    }
});

// Aktifkan resource hints untuk preconnect dan preload
const addResourceHints = () => {
    // Preconnect untuk domain font dan CDN
    [
        "https://fonts.googleapis.com",
        "https://fonts.gstatic.com",
        "https://cdnjs.cloudflare.com",
    ].forEach((domain) => {
        if (
            !document.querySelector(`link[rel="preconnect"][href="${domain}"]`)
        ) {
            const link = document.createElement("link");
            link.rel = "preconnect";
            link.href = domain;
            link.crossOrigin = "anonymous";
            document.head.appendChild(link);
        }
    });
};

// Tambahkan resource hints saat ada waktu idle
if ("requestIdleCallback" in window) {
    requestIdleCallback(addResourceHints);
} else {
    setTimeout(addResourceHints, 500);
}
