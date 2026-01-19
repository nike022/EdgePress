// Navigation & UI Toggles
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
    } else {
        menu.classList.add('hidden');
    }
}

function toggleSubmenu(id) {
    const submenu = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    if (submenu.classList.contains('hidden')) {
        submenu.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        submenu.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

// Dark Mode Logic
// Check for saved user preference handled in head, this handles toggle
function toggleDarkMode() {
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.theme = 'light';
    } else {
        document.documentElement.classList.add('dark');
        localStorage.theme = 'dark';
    }
}

// Search Modal
function toggleSearch() {
    const modal = document.getElementById('search-modal');
    if (!modal) return; // Guard clause

    if (modal.classList.contains('hidden')) {
        // Open
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.paddingRight = `${scrollbarWidth}px`;
        document.body.style.overflow = 'hidden'; // Prevent scrolling
        document.documentElement.style.overflow = 'hidden'; // Prevent scrolling on html
        
        modal.classList.remove('hidden');
        // Small delay to allow display:block to apply before opacity transition
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('div').classList.remove('scale-95');
        }, 10);
    } else {
        // Close
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            document.documentElement.style.overflow = '';
            document.body.style.paddingRight = '';
        }, 300);
    }
}

// Initialize Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Search Modal Outside Click
    const searchModal = document.getElementById('search-modal');
    if (searchModal) {
        searchModal.addEventListener('click', function(event) {
            if (event.target === this) {
                toggleSearch();
            }
        });
    }

    // Hero Slider Logic
    const sliderContainer = document.getElementById('hero-slider');
    if (sliderContainer) {
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide-item');
        const dots = document.querySelectorAll('#slider-dots button');
        const totalSlides = slides.length;
        let slideInterval;

        function showSlide(index) {
            // Wrap around index
            if (index >= totalSlides) index = 0;
            if (index < 0) index = totalSlides - 1;
            
            currentSlide = index;

            // Update slides
            slides.forEach((slide, i) => {
                if (i === currentSlide) {
                    slide.classList.remove('opacity-0', 'z-0');
                    slide.classList.add('opacity-100', 'z-10');
                } else {
                    slide.classList.remove('opacity-100', 'z-10');
                    slide.classList.add('opacity-0', 'z-0');
                }
            });

            // Update dots
            if (dots.length > 0) {
                dots.forEach((dot, i) => {
                    if (i === currentSlide) {
                        dot.classList.remove('opacity-50');
                        dot.classList.add('opacity-100');
                    } else {
                        dot.classList.remove('opacity-100');
                        dot.classList.add('opacity-50');
                    }
                });
            }
        }

        function nextSlide() {
            showSlide(currentSlide + 1);
        }

        function startSlideShow() {
            // Clear existing interval to avoid duplicates
            if (slideInterval) clearInterval(slideInterval);

            // 从 data 属性读取间隔时间（静态化兼容）
            const interval = sliderContainer.dataset.interval ? parseInt(sliderContainer.dataset.interval) * 1000 : 5000;

            slideInterval = setInterval(nextSlide, interval);
        }

        function stopSlideShow() {
            clearInterval(slideInterval);
        }

        // Global function to be called from HTML onclick if needed
        window.goToSlide = function(index) {
            showSlide(index);
            stopSlideShow();
            startSlideShow(); // Restart timer
        };

        // Initialize
        if (slides.length > 0) {
            startSlideShow();
        }

        // Pause on hover
        sliderContainer.addEventListener('mouseenter', stopSlideShow);
        sliderContainer.addEventListener('mouseleave', startSlideShow);
    }
});

// AJAX 功能已移除，主题现已完全静态化兼容
// - "加载更多" 已改为传统分页
// - "点赞功能" 已移除（可用第三方服务替代，如 LeanCloud）
