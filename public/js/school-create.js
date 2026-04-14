// School Create Form - JavaScript
// Inisialisasi variabel global
let map;
let marker;
let currentLatitude = -6.2088; // Default: Jakarta
let currentLongitude = 106.8456;
let regionNames = {
    province: '',
    city: '',
    district: '',
    village: ''
};

// Initialize Map
function initMap() {
    map = L.map('map').setView([currentLatitude, currentLongitude], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
        className: 'map-tiles'
    }).addTo(map);

    // Add marker
    marker = L.marker([currentLatitude, currentLongitude], {
        draggable: true
    }).addTo(map);

    // Update coordinates on marker drag
    marker.on('dragend', function(e) {
        const latlng = marker.getLatLng();
        updateCoordinates(latlng.lat, latlng.lng);
    });

    // Update coordinates on map click
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateCoordinates(e.latlng.lat, e.latlng.lng);
    });

    // Load existing coordinates from form if any
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    
    if (latInput.value && lngInput.value) {
        currentLatitude = parseFloat(latInput.value);
        currentLongitude = parseFloat(lngInput.value);
        map.setView([currentLatitude, currentLongitude], 13);
        marker.setLatLng([currentLatitude, currentLongitude]);
    }

    // Load region names from selects
    updateRegionNames();
}

// Update coordinate inputs
function updateCoordinates(lat, lng) {
    document.getElementById('latitude').value = lat.toFixed(6);
    document.getElementById('longitude').value = lng.toFixed(6);
}

// Update region names from form selects
function updateRegionNames() {
    const provinceSelect = document.getElementById('province_id');
    const citySelect = document.getElementById('city_id');
    const districtSelect = document.getElementById('district_id');
    const villageSelect = document.getElementById('village_id');
    
    regionNames.province = provinceSelect.options[provinceSelect.selectedIndex]?.text || '';
    regionNames.city = citySelect.options[citySelect.selectedIndex]?.text || '';
    regionNames.district = districtSelect.options[districtSelect.selectedIndex]?.text || '';
    regionNames.village = villageSelect.options[villageSelect.selectedIndex]?.text || '';
}

// Get current location using browser geolocation
function getCurrentLocation() {
    showMapLoading(true);
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                currentLatitude = position.coords.latitude;
                currentLongitude = position.coords.longitude;
                
                map.setView([currentLatitude, currentLongitude], 15);
                marker.setLatLng([currentLatitude, currentLongitude]);
                updateCoordinates(currentLatitude, currentLongitude);
                
                // Get address from coordinates (reverse geocoding)
                reverseGeocode(currentLatitude, currentLongitude);
            },
            (error) => {
                showMapLoading(false);
                alert('Tidak dapat mendapatkan lokasi saat ini. Pastikan GPS diaktifkan dan izin lokasi diberikan.');
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    } else {
        showMapLoading(false);
        alert('Browser tidak mendukung geolocation.');
    }
}

// Search location by address (geocoding)
function searchLocationByAddress() {
    showMapLoading(true);
    
    // Get address from form
    const addressDetail = document.getElementById('address_detail').value;
    
    // Get selected region names
    updateRegionNames();
    
    if (!addressDetail.trim()) {
        showMapLoading(false);
        alert('Silakan isi detail alamat terlebih dahulu.');
        return;
    }

    // Build search query
    let searchQuery = addressDetail;
    
    // Add region names if available
    const regionParts = [];
    if (regionNames.village) regionParts.push(regionNames.village);
    if (regionNames.district) regionParts.push(regionNames.district);
    if (regionNames.city) regionParts.push(regionNames.city);
    if (regionNames.province) regionParts.push(regionNames.province);
    
    if (regionParts.length > 0) {
        searchQuery += ', ' + regionParts.join(', ');
    }
    
    // Also add postal code if available
    const postalCode = document.getElementById('postal_code').value;
    if (postalCode) {
        searchQuery += ' ' + postalCode;
    }

    // Search using Nominatim (OpenStreetMap)
    geocodeAddress(searchQuery);
}

// Geocode address to coordinates
function geocodeAddress(query) {
    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=1`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            showMapLoading(false);
            
            if (data && data.length > 0) {
                const result = data[0];
                currentLatitude = parseFloat(result.lat);
                currentLongitude = parseFloat(result.lon);
                
                map.setView([currentLatitude, currentLongitude], 15);
                marker.setLatLng([currentLatitude, currentLongitude]);
                updateCoordinates(currentLatitude, currentLongitude);
                
                // Show success message
                showNotification('success', 'Lokasi ditemukan!');
            } else {
                alert('Alamat tidak ditemukan. Silakan periksa kembali detail alamat.');
            }
        })
        .catch(error => {
            showMapLoading(false);
            console.error('Geocoding error:', error);
            alert('Terjadi kesalahan saat mencari lokasi. Silakan coba lagi.');
        });
}

// Reverse geocode coordinates to address
function reverseGeocode(lat, lng) {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            showMapLoading(false);
            
            if (data && data.address) {
                const address = data.address;
                
                // Update address fields if empty
                updateAddressFields(address);
                showNotification('success', 'Lokasi ditemukan! Alamat telah diisi otomatis.');
            }
        })
        .catch(error => {
            showMapLoading(false);
            console.error('Reverse geocoding error:', error);
        });
}

// Update address fields from reverse geocoding
function updateAddressFields(addressData) {
    // Only update if field is empty
    const addressDetail = document.getElementById('address_detail');
    const postalCode = document.getElementById('postal_code');
    
    if (!addressDetail.value.trim() && addressData.road) {
        let detail = addressData.road;
        if (addressData.house_number) detail += ' No. ' + addressData.house_number;
        if (addressData.neighbourhood) detail += ', ' + addressData.neighbourhood;
        addressDetail.value = detail;
    }
    
    if (!postalCode.value && addressData.postcode) {
        postalCode.value = addressData.postcode;
    }
}

// Show/hide map loading indicator
function showMapLoading(show) {
    const loadingElement = document.getElementById('mapLoading');
    if (show) {
        loadingElement.classList.remove('hidden');
    } else {
        loadingElement.classList.add('hidden');
    }
}

// Show notification
function showNotification(type, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Logo preview function
function previewLogo(input) {
    const preview = document.getElementById('logoPreview');
    const placeholder = document.getElementById('logoPlaceholder');
    const image = document.getElementById('previewImage');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            image.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Initialize region cascade select
function initRegionCascade() {
    const provinceSelect = document.getElementById('province_id');
    const citySelect = document.getElementById('city_id');
    const districtSelect = document.getElementById('district_id');
    const villageSelect = document.getElementById('village_id');

    // Province change event
    provinceSelect.addEventListener('change', function() {
        const provinceId = this.value;
        
        // Reset downstream selects
        citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
        districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
        citySelect.disabled = true;
        districtSelect.disabled = true;
        villageSelect.disabled = true;

        if (provinceId) {
            citySelect.disabled = false;
            fetch(`/api/cities/${provinceId}`)
                .then(response => response.json())
                .then(data => {
                    citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    data.forEach(city => {
                        citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                    });
                    updateRegionNames();
                });
        }
    });

    // City change event
    citySelect.addEventListener('change', function() {
        const cityId = this.value;
        
        districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
        districtSelect.disabled = true;
        villageSelect.disabled = true;

        if (cityId) {
            districtSelect.disabled = false;
            fetch(`/api/districts/${cityId}`)
                .then(response => response.json())
                .then(data => {
                    districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    data.forEach(district => {
                        districtSelect.innerHTML += `<option value="${district.id}">${district.name}</option>`;
                    });
                    updateRegionNames();
                });
        }
    });

    // District change event
    districtSelect.addEventListener('change', function() {
        const districtId = this.value;
        
        villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
        villageSelect.disabled = true;

        if (districtId) {
            villageSelect.disabled = false;
            fetch(`/api/villages/${districtId}`)
                .then(response => response.json())
                .then(data => {
                    villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
                    data.forEach(village => {
                        villageSelect.innerHTML += `<option value="${village.id}">${village.name}</option>`;
                    });
                    updateRegionNames();
                });
        }
    });

    // Village change event
    villageSelect.addEventListener('change', function() {
        updateRegionNames();
    });
}

// Load old values if any
function loadOldValues(oldValues) {
    if (!oldValues || !oldValues.province_id) return;

    const provinceSelect = document.getElementById('province_id');
    const citySelect = document.getElementById('city_id');
    const districtSelect = document.getElementById('district_id');
    const villageSelect = document.getElementById('village_id');

    provinceSelect.value = oldValues.province_id;
    provinceSelect.dispatchEvent(new Event('change'));
    
    // Set timeout to allow API call to complete
    setTimeout(() => {
        if (oldValues.city_id) {
            citySelect.value = oldValues.city_id;
            citySelect.dispatchEvent(new Event('change'));
            
            setTimeout(() => {
                if (oldValues.district_id) {
                    districtSelect.value = oldValues.district_id;
                    districtSelect.dispatchEvent(new Event('change'));
                    
                    setTimeout(() => {
                        if (oldValues.village_id) {
                            villageSelect.value = oldValues.village_id;
                            villageSelect.dispatchEvent(new Event('change'));
                        }
                    }, 500);
                }
            }, 500);
        }
    }, 500);
}

// Dark mode support for map
function updateMapForDarkMode() {
    const isDarkMode = document.documentElement.classList.contains('dark');
    
    if (isDarkMode) {
        // Use dark map tiles
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            maxZoom: 19
        }).addTo(map);
    } else {
        // Use light map tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
    }
}

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    initMap();
    
    // Initialize region cascade
    initRegionCascade();

    // Form submission
    const form = document.getElementById('schoolForm');
    form.addEventListener('submit', function() {
        document.getElementById('loadingModal').classList.remove('hidden');
    });
});