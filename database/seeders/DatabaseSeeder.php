<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Booking;
use App\Models\Discount;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Room;
use App\Models\RoomImage;
use App\Models\SeasonalRate;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Data Fasilitas (Amenity) - Multibahasa
        $wifi = Amenity::create([
            'name' => ['en' => 'High-Speed Wi-Fi', 'th' => 'ไวไฟความเร็วสูง'],
            'icon_class' => 'fas fa-wifi',
        ]);

        $pool = Amenity::create([
            'name' => ['en' => 'Swimming Pool', 'th' => 'สระว่ายน้ำ'],
            'icon_class' => 'fas fa-swimming-pool',
        ]);

        $ac = Amenity::create([
            'name' => ['en' => 'Air Conditioning', 'th' => 'เครื่องปรับอากาศ'],
            'icon_class' => 'fas fa-snowflake',
        ]);

        $breakfast = Amenity::create([
            'name' => ['en' => 'Free Breakfast', 'th' => 'อาหารเช้าฟรี'],
            'icon_class' => 'fas fa-coffee',
        ]);

        // 2. Buat Data Properti (Property) - Multibahasa
        $property = Property::create([
            'name' => ['en' => 'Pattaya Oceanview Apartment', 'th' => 'พัทยา โอเชียนวิว อพาร์ทเมนท์'],
            'slug' => 'pattaya-oceanview-apartment',
            'address' => '123 Beach Road, Pattaya City, Bang Lamung District, Chon Buri 20150, Thailand',
            'is_active' => true,
        ]);

        // 3. Buat Data Kamar (Room) - Multibahasa
        $room = Room::create([
            'property_id' => $property->id,
            'name' => ['en' => 'Deluxe Seaview Suite', 'th' => 'ดีลักซ์ ซีวิว สวีท'],
            'slug' => 'deluxe-seaview-suite',
            'description' => [
                'en' => '<p>Experience the ultimate comfort with breathtaking views of the Pattaya ocean. This suite includes a king-size bed, a private balcony, and premium amenities.</p>',
                'th' => '<p>สัมผัสความสะดวกสบายสูงสุดพร้อมวิวทะเลพัทยาที่สวยงาม ห้องสวีทนี้ประกอบด้วยเตียงคิงไซส์ ระเบียงส่วนตัว และสิ่งอำนวยความสะดวกระดับพรีเมียม</p>'
            ],
            'base_price' => 2500, // THB
            'capacity_adults' => 2,
            'capacity_children' => 1,
            'room_size' => 45, // m2
            'is_active' => true,
        ]);

        // Hubungkan Kamar dengan Fasilitas (Many-to-Many Pivot)
        $room->amenities()->attach([$wifi->id, $pool->id, $ac->id, $breakfast->id]);

        // 4. Buat Data Galeri Foto (Room Image)
        // Catatan: Karena menggunakan dummy, gambar tidak akan benar-benar ada di folder storage, 
        // tapi link path-nya tersimpan di database untuk keperluan layout.
        RoomImage::create([
            'room_id' => $room->id,
            'image_path' => 'dummy/room-main.jpg',
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        RoomImage::create([
            'room_id' => $room->id,
            'image_path' => 'dummy/room-bathroom.jpg',
            'is_primary' => false,
            'sort_order' => 2,
        ]);

        // 5. Buat Data Kupon Diskon (Discount)
        $discount = Discount::create([
            'code' => 'WELCOMEPATTAYA',
            'type' => 'percentage',
            'value' => 10, // 10%
            'max_uses' => 100,
            'used_count' => 0,
            'valid_from' => Carbon::now(),
            'valid_until' => Carbon::now()->addMonths(3),
        ]);

        // 6. Buat Data Harga Musiman (Seasonal Rate)
        SeasonalRate::create([
            'room_id' => $room->id,
            'name' => 'Songkran Festival High Season',
            'start_date' => Carbon::create(null, 4, 12)->format('Y-m-d'),
            'end_date' => Carbon::create(null, 4, 16)->format('Y-m-d'),
            'price' => 3500, // Harga naik saat Songkran
        ]);

        // 7. Buat Data Transaksi (Booking)
        $booking = Booking::create([
            'booking_code' => 'BK-' . strtoupper(Str::random(8)),
            'room_id' => $room->id,
            'guest_first_name' => 'John',
            'guest_last_name' => 'Doe',
            'guest_email' => 'johndoe@example.com',
            'guest_phone' => '+66812345678',
            'check_in' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'check_out' => Carbon::now()->addDays(8)->format('Y-m-d'), // 3 malam
            'adult_count' => 2,
            'child_count' => 0,
            'discount_id' => $discount->id,
            'status' => 'paid',
            'total_amount' => 6750, // (2500 * 3) - 10% diskon
            'special_requests' => 'Non-smoking room, high floor please.',
        ]);

        // 8. Buat Data Pembayaran (Payment)
        Payment::create([
            'booking_id' => $booking->id,
            'transaction_id' => 'TRX-' . strtoupper(Str::random(10)),
            'gateway' => 'midtrans',
            'payment_method' => 'Credit Card',
            'amount' => 6750,
            'status' => 'success',
            'paid_at' => Carbon::now(),
        ]);
    }
}
