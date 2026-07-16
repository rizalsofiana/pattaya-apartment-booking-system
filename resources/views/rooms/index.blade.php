@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Search & Filter Bar -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-10">
            <form action="{{ route('rooms.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">{{ __('Check In') }}</label>
                    <input type="date" name="check_in" value="{{ request('check_in') }}"
                        class="w-full rounded-lg border-gray-200 p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">{{ __('Check Out') }}</label>
                    <input type="date" name="check_out" value="{{ request('check_out') }}"
                        class="w-full rounded-lg border-gray-200 p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-500 uppercase mb-2">{{ __('Tamu (Dewasa)') }}</label>
                    <input type="number" name="guests" min="1" value="{{ request('guests', 1) }}"
                        class="w-full rounded-lg border-gray-200 p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                </div>
                <div>
                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium p-3 rounded-lg text-sm transition-colors shadow-sm">
                        {{ __('Cari Kamar') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Room Listing Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($rooms as $room)
                <div
                    class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 flex flex-col transition-all hover:shadow-md">
                    <!-- Image Showcase -->
                    <div class="relative h-48 bg-gray-200">
                        @if ($room->images->first())
                            <img src="{{ asset('storage/' . $room->images->first()->image_path) }}"
                                alt="{{ $room->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400 text-sm">No Image Available
                            </div>
                        @endif
                        <span
                            class="absolute top-3 left-3 bg-white/95 backdrop-blur px-2.5 py-1 rounded-md text-xs font-semibold text-indigo-600 shadow-sm">
                            {{ $room->property->name }}
                        </span>
                    </div>

                    <!-- Room Info -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <!-- Data ini dari database, Spatie yang urus otomatis -->
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $room->name }}</h3>
                            <p class="text-xs text-gray-400 mb-4">{{ $room->room_size }} m² • {{ __('Maksimal') }}
                                {{ $room->capacity_adults }} {{ __('Dewasa') }}</p>

                            <!-- Amenities -->
                            <div class="flex flex-wrap gap-1.5 mb-6">
                                @foreach ($room->amenities as $amenity)
                                    <span class="bg-gray-100 text-gray-600 text-[11px] px-2 py-0.5 rounded">
                                        {{ $amenity->name }} <!-- Data dari database -->
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price & Action -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                            <div>
                                <span class="text-xs text-gray-400 block">{{ __('Mulai dari') }}</span>
                                <span class="text-xl font-extrabold text-indigo-600">THB
                                    {{ number_format($room->base_price, 0) }}</span>
                                <span class="text-xs text-gray-400">{{ __('/malam') }}</span>
                            </div>
                            <a href="{{ route('rooms.show', $room->slug) }}"
                                class="bg-gray-900 hover:bg-indigo-600 text-white text-xs font-semibold px-4 py-2.5 rounded-lg transition-colors shadow-sm">
                                {{ __('Lihat Detail') }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-xl border border-dashed border-gray-200">
                    <p class="text-gray-500">{{ __('Tidak ada kamar yang memenuhi kriteria pencarianmu.') }}</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
