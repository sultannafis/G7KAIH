// School Show/Detail - JavaScript
let isRejectModalOpen = false;
let mapInstance = null;

// Modal functions
function showRejectModal() {
    isRejectModalOpen = true;
    const modal = document.getElementById('rejectModal');
    const modalContent = modal.querySelector('.bg-white, .dark\\:bg-gray-800');
    
    modal.classList.remove('hidden');
    
    setTimeout(() => {
        modal.style.opacity = '1';
        if (modalContent) {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }
        // Focus ke textarea saat modal terbuka
        const reasonTextarea = document.getElementById('reason');
        if (reasonTextarea) {
            reasonTextarea.focus();
        }
    }, 10);
    
    // Sembunyikan peta jika ada
    if (mapInstance) {
        mapInstance.remove();
        mapInstance = null;
    }
}

function hideRejectModal() {
    isRejectModalOpen = false;
    const modal = document.getElementById('rejectModal');
    const modalContent = modal.querySelector('.bg-white, .dark\\:bg-gray-800');
    
    if (modalContent) {
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
    }
    
    modal.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        const reasonTextarea = document.getElementById('reason');
        if (reasonTextarea) {
            reasonTextarea.value = '';
        }
        // Inisialisasi ulang peta jika ada
        if (document.getElementById('map')) {
            initMap();
        }
    }, 200);
}

// Initialize map if coordinates exist
function initMap() {
    const mapElement = document.getElementById('map');
    if (!mapElement) return;
    
    // Destroy existing map instance first
    if (mapInstance) {
        mapInstance.remove();
        mapInstance = null;
    }

    const lat = parseFloat(mapElement.dataset.lat);
    const lng = parseFloat(mapElement.dataset.lng);
    const name = mapElement.dataset.name;

    // Validate coordinates
    if (isNaN(lat) || isNaN(lng)) {
        console.error('Invalid coordinates');
        return;
    }

    mapInstance = L.map('map').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(mapInstance);

    // Custom icon
    const schoolIcon = L.divIcon({
        html: `
            <div class="relative">
                <div class="w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
                <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                    <div class="w-0 h-0 border-l-4 border-r-4 border-t-4 border-l-transparent border-r-transparent border-t-blue-600"></div>
                </div>
            </div>
        `,
        className: 'custom-div-icon',
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -40]
    });

    L.marker([lat, lng], { icon: schoolIcon })
        .addTo(mapInstance)
        .bindPopup(`<b>${name}</b><br>Lokasi Sekolah`)
        .openPopup();
}

// Toggle school status confirmation
function toggleSchoolStatus(isActive) {
    const action = isActive ? 'nonaktifkan' : 'aktifkan';
    
    if (confirm(`Apakah Anda yakin ingin ${action} sistem sekolah ini?`)) {
        const form = document.getElementById('toggleStatusForm');
        if (form) {
            form.submit();
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map if exists
    if (document.getElementById('map')) {
        initMap();
    }
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isRejectModalOpen) {
            hideRejectModal();
        }
    });
    
    // Close modal on backdrop click (only on the backdrop, not the modal content)
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('click', function(e) {
            // Only close if clicking directly on the backdrop (not on child elements)
            if (e.target === rejectModal) {
                hideRejectModal();
            }
        });
    }

    // Setup cancel button
    const cancelBtn = document.getElementById('cancelRejectBtn');
    if (cancelBtn) {
        // Remove any disabled state
        cancelBtn.disabled = false;
        cancelBtn.style.cursor = 'pointer';
        cancelBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        
        // Add click event listener as backup
        cancelBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            hideRejectModal();
        });
    }

    // Form validation untuk reject
    const rejectForm = document.getElementById('rejectForm');
    if (rejectForm) {
        rejectForm.addEventListener('submit', function(e) {
            const reason = document.getElementById('reason');
            if (reason) {
                const reasonValue = reason.value.trim();
                if (!reasonValue) {
                    e.preventDefault();
                    alert('Harap isi alasan penolakan');
                    reason.focus();
                }
            }
        });
    }

    // Animate stats on scroll (optional enhancement)
    const statsCards = document.querySelectorAll('[data-stat]');
    if (statsCards.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, { threshold: 0.1 });

        statsCards.forEach(card => observer.observe(card));
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && document.querySelector(href)) {
                e.preventDefault();
                document.querySelector(href).scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Copy coordinates to clipboard (optional enhancement)
    const coordinateElements = document.querySelectorAll('[data-coordinate]');
    coordinateElements.forEach(element => {
        element.style.cursor = 'pointer';
        element.title = 'Klik untuk copy koordinat';
        
        element.addEventListener('click', function() {
            const text = this.textContent.trim();
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    // Show temporary success message
                    const originalText = this.textContent;
                    this.textContent = '✓ Tersalin!';
                    setTimeout(() => {
                        this.textContent = originalText;
                    }, 1500);
                });
            }
        });
    });
});

// Handle window resize for map
window.addEventListener('resize', function() {
    if (mapInstance) {
        mapInstance.invalidateSize();
    }
});

// Prevent memory leaks
window.addEventListener('beforeunload', function() {
    if (mapInstance) {
        mapInstance.remove();
        mapInstance = null;
    }
});