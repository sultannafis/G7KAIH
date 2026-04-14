<div id="school-settings" class="scroll-mt-10">
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm shadow-slate-200/50">
        <div class="flex items-center gap-3 mb-8">
            <div class="h-10 w-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800 tracking-tight">Profil Instansi Sekolah</h3>
                <p class="text-sm text-slate-500">Kelola identitas dan lokasi resmi sekolah Anda</p>
            </div>
        </div>

        @if($school)
        <form action="{{ route('school-admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="schoolForm" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                
                {{-- Info Dasar --}}
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Sekolah *</label>
                        <input type="text" name="name" value="{{ old('name', $school->name) }}" required
                               class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 transition-all focus:bg-white focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">NPSN *</label>
                            <input type="text" name="npsn" value="{{ old('npsn', $school->npsn) }}" required
                                   class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 transition-all focus:bg-white focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Zona Waktu *</label>
                            <select name="timezone" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 transition-all focus:bg-white focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 outline-none appearance-none">
                                <option value="Asia/Jakarta"  {{ old('timezone', $school->timezone) == 'Asia/Jakarta'  ? 'selected' : '' }}>WIB (Jakarta)</option>
                                <option value="Asia/Makassar" {{ old('timezone', $school->timezone) == 'Asia/Makassar' ? 'selected' : '' }}>WITA (Makassar)</option>
                                <option value="Asia/Jayapura" {{ old('timezone', $school->timezone) == 'Asia/Jayapura' ? 'selected' : '' }}>WIT (Jayapura)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Logo Sekolah</label>
                        <div class="flex items-center gap-4">
                            <div class="h-24 w-24 rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden">
                                @if($school->logo_path)
                                    <img id="previewLogo" src="{{ asset('storage/' . $school->logo_path) }}" class="w-full h-full object-cover">
                                @else
                                    <svg id="logoPlaceholder" class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <img id="previewLogo" class="hidden w-full h-full object-cover">
                                @endif
                            </div>
                            <div>
                                <label for="logo-school" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 font-bold text-xs uppercase tracking-widest hover:bg-slate-50 cursor-pointer transition-all inline-block">Ganti Logo</label>
                                <input type="file" id="logo-school" name="logo" class="hidden" accept="image/*" onchange="previewLogoSchool(this)">
                                <p class="text-[0.65rem] text-slate-400 mt-2">Maks. 2MB (PNG/JPG)</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Provinsi</label>
                            <select id="province_id" name="province_id" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 outline-none appearance-none transition-all focus:bg-white focus:ring-4 focus:ring-emerald-100">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $p)
                                    <option value="{{ $p->id }}" {{ old('province_id', $school->addresses->first()?->province_id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Kota/Kabupaten</label>
                            <select id="city_id" name="city_id" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 outline-none appearance-none transition-all focus:bg-white focus:ring-4 focus:ring-emerald-100">
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Kecamatan & Desa</label>
                        <div class="grid grid-cols-2 gap-4">
                            <select id="district_id" name="district_id" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 outline-none appearance-none transition-all focus:bg-white focus:ring-4 focus:ring-emerald-100">
                                <option value="">Kecamatan</option>
                            </select>
                            <select id="village_id" name="village_id" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 outline-none appearance-none transition-all focus:bg-white focus:ring-4 focus:ring-emerald-100">
                                <option value="">Desa/Kelurahan</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Alamat Lengkap</label>
                        <textarea name="address_detail" rows="2" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 outline-none focus:bg-white focus:ring-4 focus:ring-emerald-100 transition-all">{{ old('address_detail', $school->addresses->first()?->address_detail) }}</textarea>
                    </div>
                </div>

            </div>

            {{-- Peta --}}
            <div class="mt-10 pt-8 border-t border-slate-50">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <div>
                        <h4 class="text-sm font-bold text-slate-700">Lokasi Koordinat Sekolah</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Tentukan titik lokasi di peta</p>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="getCurrentLocation()" class="px-4 py-2 rounded-xl bg-orange-50 border border-orange-100 text-orange-600 font-bold text-[0.65rem] uppercase tracking-widest hover:bg-orange-100 transition-all">Lokasi Saya</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-8">
                        <div class="h-80 rounded-2xl border-2 border-slate-100 overflow-hidden relative" id="map-container" style="z-index: 1">
                            <div id="map" class="h-full w-full"></div>
                            <div id="mapLoading" class="hidden absolute inset-0 bg-white/60 backdrop-blur-sm z-[2] flex items-center justify-center">
                                <div class="w-8 h-8 border-3 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-4 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Latitude</label>
                            <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $school->addresses->first()?->latitude) }}" readonly
                                   class="w-full bg-slate-100 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Longitude</label>
                            <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $school->addresses->first()?->longitude) }}" readonly
                                   class="w-full bg-slate-100 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-500 outline-none">
                        </div>
                        <div class="p-4 rounded-2xl bg-sky-50 border border-sky-100">
                            <p class="text-[0.65rem] text-sky-600 font-medium leading-relaxed">Geser penanda di peta atau klik lokasi mana pun untuk memperbarui koordinat sekolah secara presisi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-10">
                <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-10 rounded-2xl shadow-lg shadow-emerald-600/20 transition-all active:scale-95 disabled:opacity-50" :disabled="isSubmitting">
                    <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <svg x-show="isSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span x-text="isSubmitting ? 'Memproses...' : 'Simpan Profil Sekolah'"></span>
                </button>
            </div>
        </form>
        @else
            <div class="p-10 text-center rounded-3xl bg-slate-50 border border-slate-100">
                <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">Data sekolah belum tersedia</p>
                <p class="text-xs text-slate-400 mt-2">Hubungi Master Admin jika ini kesalahan</p>
            </div>
        @endif
    </div>
</div>

<script>
    function previewLogoSchool(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const placeholder = document.getElementById('logoPlaceholder');
                if (placeholder) placeholder.classList.add('hidden');
                const img = document.getElementById('previewLogo');
                img.src = e.target.result;
                img.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
