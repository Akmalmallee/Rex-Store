<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTracking;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\Promo;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Rex',
            'email' => 'admin@rex.com',
            'password' => 'password',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'User Test',
            'email' => 'user@test.com',
            'password' => 'password',
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        User::factory(3)->create();

        $categories = [];
        $categoryData = [
            ['name' => 'Baju', 'slug' => 'baju', 'description' => 'Koleksi baju fashion terbaru dengan berbagai model modern dan klasik.', 'image' => 'https://picsum.photos/seed/category-baju/400/400'],
            ['name' => 'Celana', 'slug' => 'celana', 'description' => 'Koleksi celana fashion dengan potongan rapi dan bahan berkualitas.', 'image' => 'https://picsum.photos/seed/category-celana/400/400'],
            ['name' => 'Jacket', 'slug' => 'jacket', 'description' => 'Koleksi jaket trendy untuk segala cuaca dan aktivitas.', 'image' => 'https://picsum.photos/seed/category-jacket/400/400'],
            ['name' => 'Topi', 'slug' => 'topi', 'description' => 'Koleksi topi stylish untuk melengkapi outfit harian Anda.', 'image' => 'https://picsum.photos/seed/category-topi/400/400'],
        ];

        foreach ($categoryData as $data) {
            $categories[$data['name']] = Category::create($data);
        }

        $brands = [];
        $brandData = [
            ['name' => 'Rex Elite', 'slug' => 'rex-elite', 'logo' => 'https://picsum.photos/seed/brand-rex-elite/200/200'],
            ['name' => 'Urban Wear', 'slug' => 'urban-wear', 'logo' => 'https://picsum.photos/seed/brand-urban-wear/200/200'],
            ['name' => 'Classic Mode', 'slug' => 'classic-mode', 'logo' => 'https://picsum.photos/seed/brand-classic-mode/200/200'],
            ['name' => 'Modern Fit', 'slug' => 'modern-fit', 'logo' => 'https://picsum.photos/seed/brand-modern-fit/200/200'],
            ['name' => 'Street Soul', 'slug' => 'street-soul', 'logo' => 'https://picsum.photos/seed/brand-street-soul/200/200'],
        ];

        foreach ($brandData as $data) {
            $brands[] = Brand::create($data);
        }

        $productsByCategory = [
            'Baju' => [
                ['name' => 'Kemeja Oxford Premium', 'slug' => 'kemeja-oxford-premium', 'price' => 199000, 'discount' => 15, 'description' => 'Kemeja Oxford premium dengan bahan katun berkualitas tinggi. Nyaman dipakai untuk acara formal maupun semi-formal. Tersedia dalam berbagai warna elegan.'],
                ['name' => 'Kaos Polos Basic', 'slug' => 'kaos-polos-basic', 'price' => 89000, 'discount' => 0, 'description' => 'Kaos polos basic yang wajib dimiliki. Bahan katun combed 30s yang adem dan lembut di kulit. Cocok untuk daily wear.'],
                ['name' => 'Kemeja Flanel Modern', 'slug' => 'kemeja-flanel-modern', 'price' => 179000, 'discount' => 20, 'description' => 'Kemeja flanel modern dengan motif kotak-kotak trendy. Bahan flanel tebal dan hangat, cocok untuk suasana santai.'],
                ['name' => 'T-Shirt Graphic Print', 'slug' => 'tshirt-graphic-print', 'price' => 129000, 'discount' => 10, 'description' => 'T-Shirt dengan graphic print eksklusif. Desain artistik dan unik. Bahan katun premium tidak mudah luntur.'],
                ['name' => 'Polo Shirt Sporty', 'slug' => 'polo-shirt-sporty', 'price' => 159000, 'discount' => 0, 'description' => 'Polo shirt dengan desain sporty dan elegan. Kerah rapi dengan kancing premium. Cocok untuk acara casual dan semi-formal.'],
                ['name' => 'Kemeja Linen Casual', 'slug' => 'kemeja-linen-casual', 'price' => 169000, 'discount' => 25, 'description' => 'Kemeja linen casual yang ringan dan adem. Bahan linen berkualitas tinggi dengan potongan modern. Sangat nyaman untuk cuaca tropis.'],
                ['name' => 'Kaos Oversize Trendy', 'slug' => 'kaos-oversize-trendy', 'price' => 99000, 'discount' => 0, 'description' => 'Kaos oversize dengan potongan relaxed fit. Tren fashion terkini dengan bahan katun heavy duty yang adem.'],
                ['name' => 'Batik Modern Pria', 'slug' => 'batik-modern-pria', 'price' => 249000, 'discount' => 30, 'description' => 'Batik modern dengan motif kontemporer. Perpaduan tradisi dan gaya modern. Cocok untuk acara formal dan kondangan.'],
                ['name' => 'Kemeja Denim Klasik', 'slug' => 'kemeja-denim-klasik', 'price' => 219000, 'discount' => 20, 'description' => 'Kemeja denim klasik yang timeless. Bahan denim premium dengan jahitan rapi. Awet dan makin nyaman dipakai.'],
                ['name' => 'Henley Shirt Vintage', 'slug' => 'henley-shirt-vintage', 'price' => 139000, 'discount' => 15, 'description' => 'Henley shirt dengan sentuhan vintage. Desain简约 dengan kancing di bagian leher. Bahan katun lembut dan nyaman.'],
            ],
            'Celana' => [
                ['name' => 'Celana Chino Slim Fit', 'slug' => 'celana-chino-slim-fit', 'price' => 199000, 'discount' => 0, 'description' => 'Celana chino slim fit dengan bahan stretch premium. Potongan modern yang pas di badan. Cocok untuk kerja dan casual.'],
                ['name' => 'Jeans Straight Blue', 'slug' => 'jeans-straight-blue', 'price' => 299000, 'discount' => 20, 'description' => 'Jeans straight leg dengan warna blue klasik. Bahan denim berkualitas tinggi. Nyaman dipakai seharian.'],
                ['name' => 'Celana Formal Wool', 'slug' => 'celana-formal-wool', 'price' => 349000, 'discount' => 15, 'description' => 'Celana formal berbahan wool premium. Potongan rapi untuk acara formal dan meeting penting.'],
                ['name' => 'Cargo Pants Army', 'slug' => 'cargo-pants-army', 'price' => 179000, 'discount' => 0, 'description' => 'Cargo pants dengan warna army hijau. Banyak kantong fungsional. Cocok untuk aktivitas outdoor.'],
                ['name' => 'Celana Pendek Chino', 'slug' => 'celana-pendek-chino', 'price' => 129000, 'discount' => 10, 'description' => 'Celana pendek chino untuk gaya kasual. Panjang di atas lutut dengan potongan modern. Nyaman untuk hangout.'],
                ['name' => 'Jogger Pants Casual', 'slug' => 'jogger-pants-casual', 'price' => 159000, 'discount' => 0, 'description' => 'Jogger pants casual dengan bahan fleece lembut. Karet di pinggang dan kaki. Cocok untuk santai dan olahraga.'],
                ['name' => 'Jeans Skinny Hitam', 'slug' => 'jeans-skinny-hitam', 'price' => 279000, 'discount' => 25, 'description' => 'Jeans skinny fit warna hitam solid. Bahan denim stretch super nyaman. Padu padan mudah dengan berbagai atasan.'],
                ['name' => 'Celana Linen Santai', 'slug' => 'celana-linen-santai', 'price' => 169000, 'discount' => 0, 'description' => 'Celana linen santai dengan potongan longgar. Bahan linen adem dan ringan. Pilihan tepat untuk cuaca panas.'],
                ['name' => 'Trousers Premium', 'slug' => 'trousers-premium', 'price' => 399000, 'discount' => 30, 'description' => 'Trousers premium dengan bahan wol polyester blend. Potongan tailored yang elegan. Untuk penampilan profesional.'],
                ['name' => 'Celana Kulot Wanita', 'slug' => 'celana-kulot-wanita', 'price' => 189000, 'discount' => 15, 'description' => 'Celana kulot wanita dengan potongan lebar dan anggun. Bahan flowy yang nyaman. Cocok untuk gaya kasual hingga formal.'],
            ],
            'Jacket' => [
                ['name' => 'Jaket Bomber Classic', 'slug' => 'jaket-bomber-classic', 'price' => 299000, 'discount' => 20, 'description' => 'Jaket bomber classic dengan bahan drill premium. Rib di pinggang dan lengan. Desain timeless yang selalu trendy.'],
                ['name' => 'Hoodie Streetwear', 'slug' => 'hoodie-streetwear', 'price' => 249000, 'discount' => 0, 'description' => 'Hoodie streetwear dengan bahan fleece tebal. Hoodie dengan kantong kanguru dan sabuk hoodie. Nyaman dan hangat.'],
                ['name' => 'Jaket Denim Vintage', 'slug' => 'jaket-denim-vintage', 'price' => 349000, 'discount' => 25, 'description' => 'Jaket denim vintage dengan efek washed. Kancing brass premium. Klasik dan awet sepanjang masa.'],
                ['name' => 'Blazer Formal Modern', 'slug' => 'blazer-formal-modern', 'price' => 499000, 'discount' => 30, 'description' => 'Blazer formal modern dengan bahan wool blend. Potongan slim fit yang elegan. Cocok untuk acara formal dan bisnis.'],
                ['name' => 'Parka Waterproof', 'slug' => 'parka-waterproof', 'price' => 389000, 'discount' => 15, 'description' => 'Parka waterproof dengan teknologi anti air. Hoodie besar dengan fur. Cocok untuk musim hujan dan dingin.'],
                ['name' => 'Windbreaker Sporty', 'slug' => 'windbreaker-sporty', 'price' => 259000, 'discount' => 0, 'description' => 'Windbreaker sporty dengan bahan ringan dan anti angin. Desain aerodinamis. Cocok untuk olahraga outdoor.'],
                ['name' => 'Jaket Kulit Premium', 'slug' => 'jaket-kulit-premium', 'price' => 459000, 'discount' => 35, 'description' => 'Jaket kulit premium asli. Bahan kulit sapi berkualitas tinggi. Desain klasik yang maskulin dan elegan.'],
                ['name' => 'Cardigan Rajut', 'slug' => 'cardigan-rajut', 'price' => 199000, 'discount' => 10, 'description' => 'Cardigan rajut dengan bahan acrylic lembut. Rajutan rapi dengan kancing depan. Hangat dan nyaman untuk daily wear.'],
                ['name' => 'Varsity Jacket Retro', 'slug' => 'varsity-jacket-retro', 'price' => 329000, 'discount' => 20, 'description' => 'Varsity jacket retro dengan kombinasi wol dan kulit. Detail huruf di dada. Gaya kampus klasik Amerika.'],
                ['name' => 'Trench Coat Elegant', 'slug' => 'trench-coat-elegant', 'price' => 429000, 'discount' => 25, 'description' => 'Trench coat elegant dengan bahan waterproof. Sabuk di pinggang. Gaya detective klasik yang anggun.'],
            ],
            'Topi' => [
                ['name' => 'Topi Baseball Cap', 'slug' => 'topi-baseball-cap', 'price' => 89000, 'discount' => 0, 'description' => 'Topi baseball cap classic dengan bahan katun twill. Visor lentur dan nyaman. Cocok untuk aktivitas sehari-hari.'],
                ['name' => 'Beanie Rajut', 'slug' => 'beanie-rajut', 'price' => 69000, 'discount' => 10, 'description' => 'Beanie rajut hangat untuk cuaca dingin. Rajutan tebal dan elastis. Nyaman dipakai dan tetap stylish.'],
                ['name' => 'Bucket Hat', 'slug' => 'bucket-hat', 'price' => 79000, 'discount' => 0, 'description' => 'Bucket hat trendy dengan motif unik. Bahan katuk tebal. Pelindung wajah dari sinar matahari. Gaya casual kekinian.'],
                ['name' => 'Fedora Classic', 'slug' => 'fedora-classic', 'price' => 129000, 'discount' => 20, 'description' => 'Topi fedora classic dengan pita hitam. Bahan wol felt berkualitas. Gaya elegan untuk acara semi-formal.'],
                ['name' => 'Snapback Street', 'slug' => 'snapback-street', 'price' => 99000, 'discount' => 0, 'description' => 'Snapback dengan desain street style. Visor datar dengan logo embroidery. Bisa diatur ukurannya.'],
                ['name' => 'Topi Golf Sporty', 'slug' => 'topi-golf-sporty', 'price' => 109000, 'discount' => 15, 'description' => 'Topi golf sporty dengan bahan breathable. Desain mesh di bagian samping. Ringan dan nyaman untuk olahraga.'],
                ['name' => 'Panama Hat', 'slug' => 'panama-hat', 'price' => 149000, 'discount' => 25, 'description' => 'Topi panama dengan anyaman rapi. Bahan jerami berkualitas. Gaya tropis yang klasik dan elegan untuk liburan.'],
                ['name' => 'Trucker Cap Mesh', 'slug' => 'trucker-cap-mesh', 'price' => 79000, 'discount' => 0, 'description' => 'Trucker cap dengan bagian belakang mesh. Visor melengkung dengan foam depan. Gaya casual klasik Amerika.'],
                ['name' => 'Beret French Style', 'slug' => 'beret-french-style', 'price' => 119000, 'discount' => 20, 'description' => 'Beret gaya Prancis yang anggun. Bahan wol lembut. Cocok untuk penampilan artsy dan elegan.'],
                ['name' => 'Visor Simple', 'slug' => 'visor-simple', 'price' => 59000, 'discount' => 0, 'description' => 'Visor simple tanpa atasan. Desain minimalis dengan bahan ringan. Cocok untuk olahraga dan aktivitas outdoor.'],
            ],
        ];

        $colorPool = [
            ['color' => 'Hitam', 'color_code' => '#000000'],
            ['color' => 'Putih', 'color_code' => '#FFFFFF'],
            ['color' => 'Abu', 'color_code' => '#808080'],
            ['color' => 'Navy', 'color_code' => '#000080'],
            ['color' => 'Maroon', 'color_code' => '#800000'],
            ['color' => 'Hijau Army', 'color_code' => '#4B5320'],
        ];

        $products = [];

        foreach ($productsByCategory as $categoryName => $productList) {
            $category = $categories[$categoryName];

            foreach ($productList as $index => $productData) {
                $product = Product::factory()->create([
                    'category_id' => $category->id,
                    'brand_id' => $brands[array_rand($brands)]->id,
                    'name' => $productData['name'],
                    'slug' => $productData['slug'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'discount' => $productData['discount'],
                    'stock' => fake()->numberBetween(10, 100),
                    'thumbnail' => 'https://picsum.photos/seed/product-' . $productData['slug'] . '/800/1000',
                    'rating' => fake()->randomFloat(2, 3, 5),
                    'is_active' => true,
                ]);

                $imageCount = fake()->numberBetween(3, 5);
                for ($i = 1; $i <= $imageCount; $i++) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => 'https://picsum.photos/seed/product-' . $product->slug . '-img-' . $i . '/800/1000',
                        'is_primary' => $i === 1,
                    ]);
                }

                foreach (['S', 'M', 'L'] as $size) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'stock' => fake()->numberBetween(5, 30),
                    ]);
                }

                $selectedColors = fake()->randomElements($colorPool, 2);
                foreach ($selectedColors as $color) {
                    ProductColor::create([
                        'product_id' => $product->id,
                        'color' => $color['color'],
                        'color_code' => $color['color_code'],
                        'stock' => fake()->numberBetween(5, 30),
                    ]);
                }

                $products[] = $product;
            }
        }

        $reviewComments = [
            'Produknya bagus banget! Sesuai dengan deskripsi. Recommended buat kalian yang cari fashion berkualitas.',
            'Kualitasnya mantap, pengiriman cepat. Saya sangat puas dengan pembelian ini.',
            'Bahannya nyaman dipakai, ukurannya pas di badan. Bakal belanja lagi di sini.',
            'Warnanya sesuai dengan gambar. Puas banget sama kualitas jahitannya.',
            'Sudah beli beberapa kali, selalu konsisten kualitasnya. Pelayanan juga ramah.',
            'Produk original dan berkualitas. Pengiriman sangat cepat, packing rapi.',
            'Desainnya kekinian banget, cocok buat anak muda. Recommended seller!',
            'Bahan tebal dan nyaman, jahitan rapi. Worth it dengan harga segitu.',
        ];

        $users = User::all();

        for ($i = 0; $i < 30; $i++) {
            Review::create([
                'user_id' => $users->random()->id,
                'product_id' => $products[array_rand($products)]->id,
                'rating' => fake()->numberBetween(3, 5),
                'comment' => $reviewComments[array_rand($reviewComments)],
            ]);
        }

        $orderStatuses = ['pending', 'paid', 'process', 'shipped', 'completed', 'completed', 'cancelled'];
        $testUser = User::where('email', 'user@test.com')->first();

        foreach ($orderStatuses as $i => $status) {
            $subtotal = fake()->numberBetween(150000, 800000);
            $shippingCost = fake()->randomElement([10000, 15000, 20000, 30000]);
            $discount = fake()->randomElement([0, 0, 0, 50000]);
            $total = $subtotal + $shippingCost - $discount;

            $order = Order::create([
                'user_id' => $testUser->id,
                'invoice_number' => 'INV-SEED-' . strtoupper(uniqid()),
                'status' => $status,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount' => $discount,
                'total' => $total,
                'address' => fake()->address(),
                'city' => fake()->randomElement(['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Bekasi']),
                'phone' => '08123456789' . $i,
                'shipping_courier' => fake()->randomElement(['JNE', 'J&T', 'SiCepat', 'AnterAja']),
                'payment_method' => fake()->randomElement(['transfer_bank', 'cod', 'dana', 'gopay']),
                'payment_status' => in_array($status, ['paid', 'process', 'shipped', 'completed']) ? 'success' : ($status === 'cancelled' ? 'failed' : 'pending'),
                'created_at' => now()->subDays(count($orderStatuses) - $i),
                'updated_at' => now()->subDays(count($orderStatuses) - $i),
            ]);

            $itemCount = fake()->numberBetween(1, 3);
            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products[array_rand($products)];
                $qty = fake()->numberBetween(1, 2);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->final_price,
                    'size' => fake()->randomElement(['S', 'M', 'L']),
                    'color' => fake()->randomElement(['Hitam', 'Putih', 'Navy']),
                    'quantity' => $qty,
                    'subtotal' => $product->final_price * $qty,
                ]);
            }

            $trackingFlow = [
                'pending' => 'Pesanan dibuat',
            ];
            if (in_array($status, ['paid', 'process', 'shipped', 'completed'])) {
                $trackingFlow['paid'] = 'Pembayaran berhasil dikonfirmasi';
            }
            if (in_array($status, ['process', 'shipped', 'completed'])) {
                $trackingFlow['process'] = 'Pesanan sedang diproses oleh penjual';
            }
            if (in_array($status, ['shipped', 'completed'])) {
                $trackingFlow['shipped'] = 'Pesanan telah dikirim';
            }
            if ($status === 'completed') {
                $trackingFlow['completed'] = 'Pesanan telah selesai';
            }
            if ($status === 'cancelled') {
                $trackingFlow['cancelled'] = 'Pesanan dibatalkan';
            }

            $dayOffset = 0;
            foreach ($trackingFlow as $trackStatus => $description) {
                OrderTracking::create([
                    'order_id' => $order->id,
                    'status' => $trackStatus,
                    'description' => $description,
                    'created_at' => $order->created_at->addDays($dayOffset),
                    'updated_at' => $order->created_at->addDays($dayOffset),
                ]);
                $dayOffset++;
            }
        }

        $bannerData = [
            ['title' => 'New Collection 2025', 'subtitle' => 'Temukan gaya terbaru untuk tampilan yang lebih stylish', 'image' => 'https://picsum.photos/seed/banner-new-collection-2025/1920/800', 'link' => '/products', 'is_active' => true],
            ['title' => 'Summer Sale', 'subtitle' => 'Diskon hingga 50% untuk koleksi musim panas', 'image' => 'https://picsum.photos/seed/banner-summer-sale/1920/800', 'link' => '/products?promo=summer', 'is_active' => true],
            ['title' => 'Premium Fashion', 'subtitle' => 'Koleksi fashion premium dengan kualitas terbaik', 'image' => 'https://picsum.photos/seed/banner-premium-fashion/1920/800', 'link' => '/category/jacket', 'is_active' => true],
        ];

        foreach ($bannerData as $data) {
            Banner::create($data);
        }

        $promoData = [
            ['title' => 'Flash Sale 50%', 'description' => 'Diskon 50% untuk produk terpilih. Periode terbatas!', 'image' => 'https://picsum.photos/seed/promo-flash-sale/1920/800', 'discount_percent' => 50, 'is_active' => true, 'start_at' => now(), 'end_at' => now()->addDays(7)],
            ['title' => 'Buy 1 Get 1', 'description' => 'Beli 1 gratis 1 untuk semua produk fashion.', 'image' => 'https://picsum.photos/seed/promo-buy1get1/1920/800', 'discount_percent' => 50, 'is_active' => true, 'start_at' => now(), 'end_at' => now()->addDays(14)],
        ];

        foreach ($promoData as $data) {
            Promo::create($data);
        }

        $couponData = [
            ['code' => 'WELCOME10', 'type' => 'percentage', 'value' => 10, 'min_order' => 0, 'usage_limit' => 100, 'used_count' => 0, 'is_active' => true, 'expires_at' => now()->addMonths(6)],
            ['code' => 'SALE50K', 'type' => 'fixed', 'value' => 50000, 'min_order' => 200000, 'usage_limit' => 50, 'used_count' => 0, 'is_active' => true, 'expires_at' => now()->addMonths(3)],
            ['code' => 'FREESHIP', 'type' => 'percentage', 'value' => 100, 'min_order' => 100000, 'usage_limit' => 200, 'used_count' => 0, 'is_active' => true, 'expires_at' => now()->addMonths(1)],
        ];

        foreach ($couponData as $data) {
            Coupon::create($data);
        }
    }
}
