// Intersection Observer for counting animation when scrolled into view
document.addEventListener("DOMContentLoaded", () => {
    // Setup the intersection observer
    const counterObserver = new IntersectionObserver(
        (entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    // Check if we've already triggered the animation
                    if (!entry.target._x_isShown) {
                        entry.target._x_isShown = true;

                        // Get the Alpine component instance
                        const component = Alpine.$data(entry.target);

                        // Set up the animation
                        const duration = 1000;
                        let startTime = null;

                        function step(timestamp) {
                            if (!startTime) startTime = timestamp;
                            const progress = Math.min(
                                (timestamp - startTime) / duration,
                                1
                            );
                            component.value = Math.floor(
                                progress * component.target
                            );

                            if (progress < 1) {
                                window.requestAnimationFrame(step);
                            } else {
                                component.value = component.target; // Ensure we end at exactly the target
                            }
                        }

                        // Start the animation
                        window.requestAnimationFrame(step);

                        // Unobserve after animation starts
                        observer.unobserve(entry.target);
                    }
                }
            });
        },
        {
            threshold: 0.1, // Trigger when at least 10% of the element is visible
            rootMargin: "0px 0px -50px 0px", // Adjust the trigger point
        }
    );

    // Observe all counter elements
    document.querySelectorAll(".counter-item").forEach((counter) => {
        counterObserver.observe(counter);
    });
});

// Initialize Swiper for berita and umkm sliders
document.addEventListener("DOMContentLoaded", function () {
    // Berita Slider Initialization
    if (document.querySelector(".berita-slider")) {
        var swiper = new Swiper(".berita-slider", {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".berita-pagination",
                clickable: true,
                dynamicBullets: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 24,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
        });
    }

    // UMKM Slider Initialization
    if (document.querySelector(".umkm-slider")) {
        var umkmSwiper = new Swiper(".umkm-slider", {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".umkm-pagination",
                clickable: true,
                dynamicBullets: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 24,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
        });
    }

    // Map loader handling
    setTimeout(function () {
        const loader = document.querySelector(".map-loader");
        if (loader) {
            loader.style.display = "none";
        }
    }, 5000);
});

// profil
document.addEventListener("alpine:init", () => {
    Alpine.data("tabNavigation", () => ({
        activeTab: "about",
        init() {
            // Set active tab from URL hash if available
            const hash = window.location.hash.substring(1);
            if (
                [
                    "about",
                    "vision",
                    "history",
                    "structure",
                    "geography",
                ].includes(hash)
            ) {
                this.activeTab = hash;
                this.$nextTick(() => this.scrollToActiveTab());
            }

            // Update URL when tab changes
            this.$watch("activeTab", (value) => {
                history.replaceState(
                    null,
                    null,
                    value ? `#${value}` : window.location.pathname
                );
                this.scrollToActiveTab();
            });
        },
        scrollToActiveTab() {
            setTimeout(() => {
                const activeTabEl = document.getElementById(
                    "tab-" + this.activeTab
                );
                if (activeTabEl) {
                    // Scroll the tab into center view
                    const container = document.getElementById("tabPills");
                    const containerWidth = container.offsetWidth;
                    const tabWidth = activeTabEl.offsetWidth;
                    const tabLeft = activeTabEl.offsetLeft;

                    container.scrollTo({
                        left: tabLeft - containerWidth / 2 + tabWidth / 2,
                        behavior: "smooth",
                    });
                }
            }, 50);
        },
    }));

    Alpine.data("imageSlider", () => ({
        currentIndex: 0,
        totalImages: 1, // Default value
        touchStartX: 0,
        touchEndX: 0,

        init() {
            // Get totalImages from data attribute
            this.totalImages = parseInt(this.$el.dataset.totalImages || 1);

            // Auto-advance slides every 5 seconds if there are multiple images
            if (this.totalImages > 1) {
                this.startAutoSlide();
            }
        },

        startAutoSlide() {
            this.autoSlideInterval = setInterval(() => {
                this.next();
            }, 5000);
        },

        stopAutoSlide() {
            if (this.autoSlideInterval) {
                clearInterval(this.autoSlideInterval);
            }
        },

        next() {
            this.stopAutoSlide();
            this.currentIndex = (this.currentIndex + 1) % this.totalImages;
            this.startAutoSlide();
        },

        prev() {
            this.stopAutoSlide();
            this.currentIndex =
                (this.currentIndex - 1 + this.totalImages) % this.totalImages;
            this.startAutoSlide();
        },

        touchStart(e) {
            this.touchStartX = e.changedTouches[0].screenX;
        },

        touchEnd(e) {
            this.touchEndX = e.changedTouches[0].screenX;
            this.handleSwipe();
        },

        handleSwipe() {
            const threshold = 50;
            const swipeDistance = this.touchEndX - this.touchStartX;

            if (swipeDistance > threshold) {
                // Swiped right, go to previous
                this.prev();
            } else if (swipeDistance < -threshold) {
                // Swiped left, go to next
                this.next();
            }
        },
    }));
});

// berita detail
// Make copyToClipboard globally accessible
window.copyToClipboard = function (text) {
    navigator.clipboard
        .writeText(text)
        .then(() => {
            // Show success toast
            const toast = document.createElement("div");
            toast.className =
                "fixed bottom-4 right-4 bg-gray-900 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 animate-fade-in z-50";
            toast.innerHTML = `
                <i class="fas fa-check text-emerald-400"></i>
                <span>Link berhasil disalin!</span>
            `;
            document.body.appendChild(toast);

            // Remove toast after 2 seconds
            setTimeout(() => {
                toast.classList.add("animate-fade-out");
                setTimeout(() => toast.remove(), 300);
            }, 2000);
        })
        .catch(console.error);
};
