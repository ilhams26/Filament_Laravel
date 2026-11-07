@php
    $record = method_exists($this, 'getRecord') ? $this->getRecord() : null;
    $currentLat = $record?->latitude;
    $currentLng = $record?->longitude;
@endphp

{{-- Leaflet CSS dan styling khusus komponen peta --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .leaflet-default-icon-path { background-image: url(https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png); }
    .branch-map-container svg {
        display: inline-block;
        flex-shrink: 0;
        vertical-align: middle;
    }
    .branch-map-container .leaflet-marker-icon { width: 25px !important; height: 41px !important; }
    .branch-map-container .leaflet-marker-shadow { width: 41px !important; height: 41px !important; }
</style>

<div
    x-data="leafletMapPicker({
        initialLat: @js($currentLat),
        initialLng: @js($currentLng),
    })"
    x-init="initMap()"
    wire:ignore
    class="branch-map-container w-full space-y-2 mt-2"
>
    {{-- Label Instruksi --}}
    <div class="flex items-center justify-between text-sm font-medium text-gray-700 dark:text-gray-200" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
        <span class="flex items-center gap-1.5" style="display: flex; align-items: center; gap: 6px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; min-width: 16px; min-height: 16px; display: inline-block; color: #ea580c; flex-shrink: 0;">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
            </svg>
            Klik peta untuk menentukan titik
        </span>
        <button
            type="button"
            @click="detectLocation()"
            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg bg-orange-50 text-orange-600 hover:bg-orange-100 dark:bg-orange-950 dark:text-orange-400 dark:hover:bg-orange-900 border border-orange-200 dark:border-orange-800 transition"
            style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; font-size: 12px; font-weight: 600; border-radius: 6px; border: 1px solid #fed7aa; background-color: #fff7ed; color: #ea580c; cursor: pointer;"
        >
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; display: inline-block; flex-shrink: 0;">
                <polygon points="3 11 22 2 13 21 11 13 3 11"></polygon>
            </svg>
            Gunakan Lokasi Saya
        </button>
    </div>

    {{-- Kontainer Peta Leaflet --}}
    <div
        x-ref="mapContainer"
        class="w-full h-80 sm:h-96 rounded-xl border border-gray-300 dark:border-gray-700 shadow-sm overflow-hidden relative z-0"
        style="width: 100%; height: 360px; min-height: 360px; border-radius: 12px; border: 1px solid #d1d5db; position: relative; z-index: 0;"
    ></div>

    {{-- Keterangan di bawah peta --}}
    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400" style="display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #6b7280; margin-top: 6px;">
        <span class="flex items-center gap-1" style="display: flex; align-items: center; gap: 4px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; display: inline-block; color: #9ca3af; flex-shrink: 0;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            Marker dapat digeser, koordinat terisi otomatis
        </span>
        <span x-show="currentPositionText" x-text="currentPositionText" class="font-mono text-[11px] text-gray-600 dark:text-gray-300 font-medium" style="font-family: monospace; font-size: 11px;"></span>
    </div>
</div>

<script>
    if (!window.leafletMapPicker) {
        window.leafletMapPicker = function(config) {
            return {
                map: null,
                marker: null,
                markerIcon: null,
                currentPositionText: '',

                initMap() {
                    const ensureLeafletLoaded = (callback) => {
                        if (window.L) {
                            callback();
                            return;
                        }
                        if (!document.getElementById('leaflet-js')) {
                            const script = document.createElement('script');
                            script.id = 'leaflet-js';
                            script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                            script.onload = () => callback();
                            document.head.appendChild(script);
                        } else {
                            const el = document.getElementById('leaflet-js');
                            if (el.dataset.loaded) { callback(); }
                            else { el.addEventListener('load', () => callback()); }
                        }
                    };

                    ensureLeafletLoaded(() => {
                        this.$nextTick(() => {
                            this.setupLeaflet();
                        });
                    });
                },

                setupLeaflet() {
                    if (this.map) return;

                    // Buat custom icon eksplisit - menghindari bug broken image default
                    this.markerIcon = L.icon({
                        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41],
                    });

                    const latInput = document.getElementById('branch-latitude');
                    const lngInput = document.getElementById('branch-longitude');

                    let lat = parseFloat(config.initialLat);
                    let lng = parseFloat(config.initialLng);

                    if (isNaN(lat) && latInput && latInput.value) {
                        lat = parseFloat(latInput.value);
                    }
                    if (isNaN(lng) && lngInput && lngInput.value) {
                        lng = parseFloat(lngInput.value);
                    }

                    const hasInitialCoords = !isNaN(lat) && !isNaN(lng);
                    const defaultCenter = hasInitialCoords ? [lat, lng] : [-6.2000000, 106.8166667];
                    const defaultZoom = hasInitialCoords ? 15 : 12;

                    this.map = L.map(this.$refs.mapContainer, {
                        center: defaultCenter,
                        zoom: defaultZoom,
                        zoomControl: true,
                    });

                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(this.map);

                    if (hasInitialCoords) {
                        this.placeMarker(lat, lng, false);
                    }

                    this.map.on('click', (e) => {
                        this.placeMarker(e.latlng.lat, e.latlng.lng, true);
                    });

                    const syncFromInputs = () => {
                        const typedLat = parseFloat(latInput?.value);
                        const typedLng = parseFloat(lngInput?.value);
                        if (!isNaN(typedLat) && !isNaN(typedLng) &&
                            typedLat >= -90 && typedLat <= 90 &&
                            typedLng >= -180 && typedLng <= 180) {
                            this.placeMarker(typedLat, typedLng, false);
                            this.map.panTo([typedLat, typedLng]);
                        }
                    };

                    latInput?.addEventListener('input', syncFromInputs);
                    lngInput?.addEventListener('input', syncFromInputs);

                    setTimeout(() => this.map?.invalidateSize(), 200);
                    setTimeout(() => this.map?.invalidateSize(), 500);
                },

                placeMarker(lat, lng, shouldUpdateInputs = true) {
                    const latFormatted = Number(lat).toFixed(7);
                    const lngFormatted = Number(lng).toFixed(7);

                    if (!this.marker) {
                        this.marker = L.marker([lat, lng], {
                            draggable: true,
                            icon: this.markerIcon,
                        }).addTo(this.map);
                        this.marker.bindPopup('<b>Titik Cabang</b><br>Geser untuk menyesuaikan').openPopup();

                        this.marker.on('dragend', (e) => {
                            const pos = e.target.getLatLng();
                            this.placeMarker(pos.lat, pos.lng, true);
                        });
                    } else {
                        this.marker.setLatLng([lat, lng]);
                    }

                    this.currentPositionText = `Latitude: ${latFormatted}, Longitude: ${lngFormatted}`;

                    if (shouldUpdateInputs) {
                        this.updateFormFields(latFormatted, lngFormatted);
                    }
                },

                updateFormFields(lat, lng) {
                    const latInput = document.getElementById('branch-latitude');
                    const lngInput = document.getElementById('branch-longitude');

                    if (latInput) {
                        latInput.value = lat;
                        latInput.dispatchEvent(new Event('input', { bubbles: true }));
                        latInput.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    if (lngInput) {
                        lngInput.value = lng;
                        lngInput.dispatchEvent(new Event('input', { bubbles: true }));
                        lngInput.dispatchEvent(new Event('change', { bubbles: true }));
                    }

                    if (typeof this.$wire !== 'undefined') {
                        this.$wire.set('data.latitude', lat);
                        this.$wire.set('data.longitude', lng);
                    }
                },

                detectLocation() {
                    if (!navigator.geolocation) {
                        alert('Browser Anda tidak mendukung fitur geolokasi.');
                        return;
                    }
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            if (this.map) {
                                this.map.setView([lat, lng], 16);
                                this.placeMarker(lat, lng, true);
                            }
                        },
                        (error) => {
                            alert('Tidak dapat mendeteksi lokasi. Pastikan izin akses lokasi diizinkan pada browser Anda.');
                        },
                        { enableHighAccuracy: true, timeout: 10000 }
                    );
                }
            };
        };

        if (typeof Alpine !== 'undefined') {
            Alpine.data('leafletMapPicker', window.leafletMapPicker);
        } else {
            document.addEventListener('alpine:init', () => {
                Alpine.data('leafletMapPicker', window.leafletMapPicker);
            });
        }
    }
</script>

