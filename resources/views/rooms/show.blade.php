@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Tombol Kembali -->
        <div class="mb-6">
            <a href="{{ route('rooms.index') }}"
                class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-2">
                ← {{ __('Kembali ke Daftar Kamar') }}
            </a>
        </div>

        <!-- Header Judul -->
        <div class="mb-6">
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">{{ $room->property->name }}</span>
            <h1 class="text-3xl font-extrabold text-gray-900 mt-1">{{ $room->name }}</h1>
        </div>

        <!-- Galeri Foto (Grid Layout) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10 rounded-xl overflow-hidden shadow-sm">
            @php
                $primaryImage = $room->images->where('is_primary', true)->first() ?? $room->images->first();
                $otherImages = $room->images->where('is_primary', false);
            @endphp

            <!-- Foto Utama (Besar di Kiri) -->
            <div class="md:col-span-2 h-100 bg-gray-100">
                @if ($primaryImage)
                    <img src="{{ asset('storage/' . $primaryImage->image_path) }}" alt="{{ $room->name }}"
                        class="w-full h-full object-cover">
                @else
                    <div class="flex items-center justify-center h-full text-gray-400">No Image Available</div>
                @endif
            </div>

            <!-- Foto Tambahan (Grid di Kanan) -->
            <div class="grid grid-rows-2 gap-4 h-100">
                @foreach ($otherImages->take(2) as $img)
                    <div class="bg-gray-100 overflow-hidden h-full">
                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="Gallery"
                            class="w-full h-full object-cover">
                    </div>
                @endforeach
                @if ($otherImages->count() < 2)
                    <!-- Placeholder jika foto tambahan kurang dari 2 -->
                    <div class="bg-gray-100 flex items-center justify-center text-gray-300">
                        StayEase Room
                    </div>
                @endif
            </div>
        </div>

        <!-- Konten Utama & Sidebar Form Booking -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            <!-- Kolom Kiri: Detail & Fasilitas -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Deskripsi Kamar -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-950 mb-4">{{ __('Deskripsi Kamar') }}</h2>
                    <div class="prose text-gray-600 leading-relaxed">
                        {!! $room->description !!}
                    </div>
                </div>

                <!-- Fasilitas Kamar -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-950 mb-4">{{ __('Fasilitas Kamar') }}</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($room->amenities as $amenity)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <span class="text-indigo-600 text-lg">
                                    <i class="{{ $amenity->icon_class ?? 'fas fa-check' }}"></i>
                                </span>
                                <span class="text-sm font-medium text-gray-700">{{ $amenity->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Widget Booking (Sticky) -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-md sticky top-24">
                    <div class="mb-6 pb-6 border-b border-gray-100">
                        <span class="text-xs text-gray-400 block">{{ __('Mulai dari') }}</span>
                        <span class="text-3xl font-extrabold text-indigo-600">THB
                            {{ number_format($room->base_price, 0) }}</span>
                        <span class="text-sm text-gray-400">/{{ __('malam') }}</span>
                    </div>

                    <!-- Form Simulasi Pemesanan -->
                    <form action="#" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-500 uppercase mb-2">{{ __('Check In') }}</label>
                            <input type="date" name="check_in" required
                                class="w-full rounded-lg border-gray-200 p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-500 uppercase mb-2">{{ __('Check Out') }}</label>
                            <input type="date" name="check_out" required
                                class="w-full rounded-lg border-gray-200 p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-500 uppercase mb-2">{{ __('Tamu') }}</label>
                            <select name="guests"
                                class="w-full rounded-lg border-gray-200 p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                                @for ($i = 1; $i <= $room->capacity_adults; $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ __('Dewasa') }}</option>
                                @endfor
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3.5 rounded-lg text-sm transition-colors shadow-sm mt-6">
                            {{ __('Pesan Sekarang') }}
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
