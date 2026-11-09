# GUIDE.md — Panduan Projek CMS Laravel 13

> Dokumen ini adalah aturan utama projek. **Developer dan AI assistant (Claude CLI/agent maupun Claude web) wajib membacanya penuh sebelum menulis atau mengubah kode.** Dokumen ini berdiri sendiri: bisa dipakai dengan atau tanpa agent.

---

## 1. Tujuan dan Scope

**Tujuan.** Boilerplate CMS + company profile yang menjadi standar kode di kantor. Dipakai oleh 6 developer pemula; masing-masing membangun company profile berbeda dari boilerplate yang sama. Karena itu struktur harus seragam, sederhana, dan **satu file = satu tanggung jawab**.

**Masalah yang ingin dihindari:** kode duplikat, query di route, controller gemuk (spaghetti), validasi di controller, package berlebihan (Filament dsb.), serta try/catch dan transaksi yang tidak konsisten.

**Stack (final):**

| Bagian | Pilihan |
|---|---|
| Framework | Laravel 13, monolith (backend dan view dalam satu projek) |
| View | Blade |
| CSS | Tailwind CSS 4 (CSS-first, lewat Vite) |
| JS | Alpine.js, untuk perilaku interaktif di dalam Blade component (bukan logika bisnis) |
| Auth | Laravel Breeze (Blade), **hanya login dan logout** |
| Arsitektur | Service pattern **tanpa** repository |

**Di luar scope** (jangan ditambahkan tanpa persetujuan lead): Livewire, Inertia, Vue/React, Filament atau admin-panel package lain, API publik/SPA, repository pattern, interface per service, multi-tenant, microservice, dan package baru apa pun.

---

## 2. Cara Memakai Guide Ini

Guide ini berlaku untuk semua developer, dengan atau tanpa agent.

| Cara kerja | Yang dilakukan |
|---|---|
| Agent CLI/IDE (mis. Claude Code) | Letakkan `GUIDE.md` di root projek. Buat file `CLAUDE.md` berisi satu baris `@GUIDE.md` agar guide terbaca otomatis setiap sesi. Alternatifnya, minta manual: "Baca GUIDE.md dulu." |
| Claude web (claude.ai) | Tambahkan `GUIDE.md` ke Project knowledge (fitur Projects), atau lampirkan/paste isi lengkapnya di awal chat baru. Claude web **tidak bisa melihat repo**, jadi paste juga file projek yang relevan (model, service, view yang sudah ada). |
| Tanpa AI | Ikuti manual: bagian 4 (penempatan logika), bagian 9 (alur referensi), dan bagian 11 (checklist). |

**Prompt pembuka untuk chat Claude web** (salin, lalu isi bagian dalam kurung):

```
Baca GUIDE.md terlampir dan ikuti seluruh aturannya.
Projek: CMS Laravel 13 (monolith, Blade, Tailwind 4, Alpine.js).
Tugas: [jelaskan tugas, mis. "buat modul Banner: judul, gambar, urutan"].
File yang sudah ada dan relevan: [paste isi file: model, service, view, dll].
Jika ada file yang perlu kamu lihat tetapi belum saya berikan, minta dulu. Jangan menebak.
```

### Instruksi untuk AI Assistant (berlaku di Claude CLI maupun Claude web)

1. Baca GUIDE.md ini sebelum bekerja. Ikuti struktur, penamaan, dan pola di sini. Tiru **Alur Referensi** (bagian 9) untuk modul baru.
2. Ubah hanya file yang relevan dengan tugas. Jangan refactor, rename, atau merapikan file lain yang tidak diminta.
3. Jangan memasang package baru dan jangan upgrade versi major tanpa izin.
4. Jika permintaan developer bertentangan dengan guide ini, jelaskan singkat konfliknya lalu kerjakan dengan cara yang sesuai guide. Jangan melanggar diam-diam.
5. Jika ragu logika harus ditaruh di mana, tanya dulu sebelum menulis kode.
6. Developer adalah pemula. Setelah selesai, jelaskan singkat file apa yang dibuat/diubah dan **mengapa** ditaruh di file tersebut.
7. Urutan membuat modul baru: Migration → Model → Form Request → Service → Controller → Route → View.
8. Sebelum menyatakan selesai, jalankan checklist di bagian 11.
9. Jika tidak bisa melihat isi repo (mis. Claude web), jangan berasumsi ada file, class, atau kolom tertentu. Minta developer menempelkan file yang diperlukan sebelum mengubahnya.
10. Beri judul **path lengkap** pada setiap blok kode (mis. `app/Services/GalleryService.php`). Tulis file baru secara utuh agar bisa disalin. Untuk file yang sudah ada, tunjukkan bagian yang diubah beserta lokasinya, atau kirim file utuh bila perubahannya banyak. Tutup dengan daftar perintah (`php artisan ...`, `npm ...`) yang harus dijalankan developer, berurutan.

---

## 3. Struktur Projek

```
app/
├── Helpers/            # utilitas stateless yang dipakai banyak modul (UploadHelper)
├── Http/
│   ├── Controllers/
│   │   ├── Admin/      # controller panel admin (CRUD)
│   │   ├── Auth/       # bawaan Breeze: login dan logout
│   │   └── Web/        # controller halaman publik (company profile)
│   ├── Middleware/     # hanya jika benar-benar perlu
│   └── Requests/
│       ├── Admin/      # Form Request modul admin: validasi dan authorize
│       └── Auth/       # bawaan Breeze (LoginRequest)
├── Models/
│   └── Concerns/       # trait milik model (LogsActivity)
├── Services/           # logika bisnis per modul (GalleryService)
├── Enums/              # konstanta bernama (status, tipe)
└── Providers/
bootstrap/app.php       # routing, middleware, exception handling (tidak ada Http/Kernel.php)
config/                 # konfigurasi. Satu-satunya tempat yang boleh memanggil env()
database/
├── migrations/         # perubahan skema
├── factories/
└── seeders/
public/                 # entry point web dan hasil build Vite
resources/
├── css/app.css         # entry Tailwind 4: @import "tailwindcss" dan @theme
├── js/app.js           # inisialisasi Alpine
└── views/
    ├── layouts/        # app (admin panel), guest (login), public (company profile)
    ├── components/     # Blade component reusable (aturan di bagian 8)
    │   ├── ui/         # elemen dasar, dipakai admin dan landing (x-ui.button, x-ui.input, x-ui.modal)
    │   ├── admin/      # khusus admin CMS (x-admin.page-header, x-admin.table)
    │   └── web/        # khusus landing page (x-web.hero, x-web.section)
    ├── admin/<modul>/  # index, create, edit, _form
    ├── web/            # halaman publik
    └── auth/           # halaman login (bawaan Breeze)
routes/
├── web.php             # route publik; me-require auth.php dan admin.php
├── auth.php            # login dan logout
└── admin.php           # route panel admin
storage/                # file upload (disk public; jalankan php artisan storage:link)
tests/Feature/          # test per modul
```

`app/Observers/` **tidak dipakai secara default**. Buat hanya jika ada efek samping model selain pencatatan aktivitas, dan setelah disetujui lead.

---

## 4. Penempatan Logika (Wajib Diikuti)

| Layer | Tugas | Dilarang |
|---|---|---|
| `routes/*.php` | Memetakan URL ke method controller, grup middleware, nama route | Closure berisi logika, query, validasi |
| Controller | Menerima request, memanggil Service, mengembalikan view/redirect, satu `try/catch` pada aksi tulis | Query Eloquent, validasi inline, upload file, logika bisnis |
| Form Request | Validasi, `authorize()`, pesan error, `prepareForValidation()` | Query kompleks, logika bisnis |
| Service | Semua logika bisnis dan akses data (baca/tulis), upload lewat Helper, transaksi | Menerima objek `Request`, mengembalikan view/redirect, memakai `session()` atau `request()` |
| Model | `$fillable`, `casts()`, relasi, scope, accessor | Logika bisnis, upload, urusan HTTP |
| Trait `LogsActivity` | Mencatat log aktivitas otomatis dari event model | Dipanggil manual dari Service/Controller |
| Helper | Utilitas stateless yang dipakai 2 modul atau lebih | Menyimpan state, query bisnis |
| Enum | Nilai tetap (status, tipe) | String "ajaib" tersebar di kode |
| View (Blade) | Menampilkan data, loop/kondisi sederhana, memakai Blade component | Query, blok `@php` berisi logika, hitungan bisnis, menulis ulang markup elemen yang sudah punya component |
| Blade component | Tampilan elemen UI reusable (props, slot, Alpine untuk perilakunya) | Query, logika bisnis |
| Alpine.js | Perilaku UI di dalam Blade component (modal, dropdown, tab, preview gambar) | Fetch data, logika bisnis, `x-data` yang ditulis berulang di halaman |

---

## 5. Aturan Service Pattern

- **Satu Service per modul/model** di `app/Services/` (contoh `GalleryService`). Kelas biasa: tanpa interface, tanpa base class, tanpa repository.
- Di-inject lewat constructor controller (`private GalleryService $galleryService`). Dilarang `new GalleryService` dan method static.
- Service menerima data polos (`array`, Model, `UploadedFile`) dan mengembalikan Model/Collection/Paginator/`void`. Service tidak menyentuh HTTP.
- **Semua** query Eloquent (baca dan tulis) ada di Service. Query yang dipakai ulang dijadikan scope di Model.
- Controller publik (`Web/`) juga membaca data lewat Service, bukan langsung dari Model.
- Nama method: `paginate`, `find`, `store`, `update`, `delete`. Aksi khusus memakai kata kerja jelas (`publish`, `reorder`).
- Service boleh memakai Helper dan Service lain (satu arah, tanpa saling memanggil melingkar).
- Satu method = satu tujuan. Method lebih dari ±30 baris dipecah menjadi method `private` di Service yang sama.
- Pencatatan aktivitas **bukan** tugas Service (lihat trait `LogsActivity`).

---

## 6. Error Handling dan Transaksi

1. **Controller:** satu `try/catch (Throwable $e)` pada aksi tulis (`store`, `update`, `destroy`), membungkus hanya pemanggilan Service. Di `catch`: `report($e)`, lalu `back()->withInput()->with('error', '...')`. Aksi baca tidak perlu try/catch.
2. **Service:** tidak menelan exception. `try/catch` hanya boleh untuk membersihkan file yang baru diupload, lalu `throw $e;` (lihat Alur Referensi).
3. **Transaksi:** pakai `DB::transaction(function () { ... })` **hanya jika ada lebih dari satu operasi tulis database**. Jangan `beginTransaction/commit/rollBack` manual, dan jangan menaruh `try/catch` di dalam closure transaksi.

   ```php
   DB::transaction(function () use ($data) {
       $post = Post::create($data);
       $post->tags()->sync($data['tag_ids']);
   });
   ```
4. Kegagalan validasi ditangani Laravel lewat Form Request. Jangan di-catch.
5. Jangan menampilkan `$e->getMessage()` ke user. Tampilkan pesan umum, detail masuk log lewat `report()`.

---

## 7. Aturan Penulisan Kode

### Gaya
- PSR-12 lewat Laravel Pint: jalankan `./vendor/bin/pint` sebelum commit.
- Type hint pada semua parameter dan return type.
- Pakai early return. Hindari `if` bersarang lebih dari 2 tingkat.
- Nama class/method/variabel/kolom: **bahasa Inggris**. Komentar: bahasa Indonesia, hanya menjelaskan *mengapa* (bukan *apa*). Pesan ke user (flash, label): bahasa Indonesia.
- Hapus `dd()`, `dump()`, `var_dump()`, dan kode yang dikomentari sebelum selesai.
- **DRY:** sebelum membuat sesuatu, cek apakah helper/component/service serupa sudah ada. Jika logika yang sama muncul 2 kali atau lebih, ekstrak ke layer yang benar (bagian 4).

### Penamaan

| Elemen | Aturan | Contoh |
|---|---|---|
| Model | Singular, PascalCase | `Gallery` |
| Tabel | Plural, snake_case | `galleries` |
| Kolom | snake_case | `created_by` |
| Controller | `{Model}Controller` | `GalleryController` |
| Service | `{Model}Service` | `GalleryService` |
| Form Request | `Store{Model}Request`, `Update{Model}Request` | `StoreGalleryRequest` |
| Method/variabel | camelCase | `$galleryService` |
| Nama route | `admin.{plural}.{aksi}` | `admin.galleries.index` |
| URL | kebab-case | `/admin/galleries` |
| File view | kebab-case di folder modul | `admin/galleries/index.blade.php` |
| Blade component | kebab-case, dikelompokkan `ui/`, `admin/`, `web/` | `<x-ui.flash-message>`, `<x-admin.page-header>` |

### Model dan Database
- `$fillable` eksplisit. Dilarang `$guarded = []`.
- Perubahan skema hanya lewat migration. Jangan mengedit migration yang sudah di-commit; buat migration baru.
- Foreign key memakai `foreignId()->constrained()`.
- Hindari N+1 dengan `with()`. Daftar data wajib `paginate()`, bukan `all()`.
- Model yang perlu dicatat aktivitasnya cukup `use LogsActivity;`.

### Keamanan
- Semua input lewat Form Request. Ambil data dengan `validated()` atau `safe()`, bukan `$request->all()`.
- Upload: validasi `image`, `mimes`, `max`; simpan lewat `UploadHelper` ke disk `public`.
- Blade memakai `{{ }}`. `{!! !!}` hanya untuk HTML yang sudah disanitasi.
- Setiap form memakai `@csrf` (dan `@method` bila perlu). Route admin dilindungi middleware `auth`.
- `env()` hanya di `config/`. Di tempat lain pakai `config()`.
- Jangan commit `.env` atau kredensial.

### Auth (Breeze)
Hanya login dan logout. Fitur Breeze lain (register, lupa/reset password, verifikasi email, profil) tidak dipakai dan tidak boleh ditambahkan kembali tanpa izin.

---

## 8. Frontend: Tailwind, Blade Component, dan Alpine.js

### Tailwind CSS 4
- Konfigurasi CSS-first di `resources/css/app.css` (`@import "tailwindcss";` dan `@theme { ... }`). Tidak ada `tailwind.config.js`.
- Pakai utility class langsung di Blade. Markup yang berulang dijadikan Blade component (aturan lengkap di bawah). `@apply` hanya untuk kasus kecil dan langka.
- Warna/font/spasi merek dijadikan token di `@theme`, bukan nilai arbitrer yang diulang (`text-[#123456]`).
- Mobile-first (`sm:`, `md:`, `lg:`).
- Build lewat Vite (`npm run dev` / `npm run build`). Dilarang CDN, jQuery, Bootstrap.
- Flash message (`success`/`error`) ditampilkan **sekali** di layout lewat `<x-ui.flash-message>`, bukan ditulis ulang per halaman.

### Blade Component Reusable

Tujuan: tampilan dan perilaku UI **konsisten di semua halaman**. Developer tidak menulis ulang tombol, input, modal, dan sejenisnya di setiap halaman.

| Area | Aturan |
|---|---|
| Admin CMS | **Wajib** memakai component untuk setiap elemen UI yang sudah tersedia. Elemen yang akan dipakai di lebih dari satu halaman dibuat dulu sebagai component. |
| Landing page | Tidak wajib, tetapi **dianjurkan** (navbar, hero, section, card, tombol). Pakai component `ui/` yang sama agar gaya tetap seragam. |

**Component dasar yang harus tersedia di boilerplate** (dibuat sekali, dipakai semua modul):

| Component | Fungsi |
|---|---|
| `<x-ui.button>` | Tombol; prop `variant` (primary/secondary/danger) dan `type` |
| `<x-ui.input>` | Label + input + pesan error dalam satu paket |
| `<x-ui.textarea>` | Sama seperti input, untuk textarea |
| `<x-ui.select>` | Sama seperti input, untuk select |
| `<x-ui.file-upload>` | Input file + preview gambar (Alpine) |
| `<x-ui.modal>` | Modal (Alpine) |
| `<x-ui.confirm-delete>` | Konfirmasi hapus: modal + form `DELETE` |
| `<x-ui.flash-message>` | Flash success/error yang bisa ditutup (Alpine) |
| `<x-ui.badge>` | Label status |
| `<x-admin.page-header>` | Judul halaman + tombol aksi |
| `<x-admin.table>` | Tabel standar admin |

**Aturan membuat component:**

1. Pakai **anonymous component** (satu file `.blade.php`, props lewat `@props`). Jangan membuat class component di `app/View/Components` tanpa izin lead.
2. Variasi lewat prop (`variant`, `size`), bukan component baru per warna atau ukuran.
3. Teruskan atribut dengan `$attributes->merge(['class' => '...'])` agar `name`, `required`, dan class tambahan dari pemakai tetap bekerja.
4. Isi lewat slot (`{{ $slot }}` dan named slot).
5. Component hanya menampilkan. Tidak ada query atau logika bisnis; data masuk lewat props.
6. Perilaku interaktif (buka/tutup modal, preview gambar) hidup **di dalam component** memakai Alpine, sehingga halaman cukup memanggil component tanpa menulis `x-data`.
7. Komunikasi antar-component memakai event Alpine (`$dispatch('open-modal', 'nama-modal')`).
8. Cek folder `components/` sebelum membuat yang baru. Pakai ulang atau tambah prop; jangan duplikat.
9. Baris pertama file component berisi komentar Blade yang mencantumkan daftar props, agar mudah dipakai developer lain.
10. Component bawaan Breeze (modal, dropdown, input, button) menjadi dasar `ui/`: dipindahkan dan di-restyle sekali pada setup boilerplate. Developer tidak membuat versi sendiri.

### Alpine.js
- Untuk perilaku interaktif **di dalam Blade component**: modal, dropdown, tab, accordion, flash yang bisa ditutup, preview gambar.
- Halaman admin tidak menulis `x-data` sendiri. Butuh perilaku baru? Buat atau extend component.
- Dilarang: fetch/axios untuk data, logika bisnis, store global kompleks, plugin tambahan tanpa izin.
- `x-data` di dalam component dijaga kecil (±5 baris). Lebih besar dari itu, pindahkan ke `Alpine.data('nama', () => ({ ... }))` di `resources/js/`.
- Alpine sudah ter-install bawaan Breeze dan di-start di `resources/js/app.js`. Jangan load dari CDN.

Contoh component dengan Alpine (preview gambar sebelum upload):

```blade
{{-- resources/views/components/ui/file-upload.blade.php --}}
{{-- Props: name (wajib), label, current (URL gambar saat ini, untuk halaman edit) --}}
@props(['name', 'label' => null, 'current' => null])

<div class="space-y-2"
     x-data="{
         preview: @js($current),
         pick(e) {
             const file = e.target.files[0];
             if (file) this.preview = URL.createObjectURL(file);
         },
     }">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif

    <input type="file" id="{{ $name }}" name="{{ $name }}" accept="image/*"
           @change="pick($event)"
           {{ $attributes->merge(['class' => 'block w-full text-sm']) }}>

    <img x-show="preview" :src="preview" alt="Preview" class="h-32 rounded-md object-cover">

    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
```

Halaman cukup memanggil: `<x-ui.file-upload name="image" label="Gambar" />`.

---

## 9. Alur Referensi: CRUD Gallery (Data + Upload File)

Ini contoh baku. Modul baru **harus meniru pola ini**. (Baris `use`/import pada controller dan model dihilangkan agar ringkas.)

**Perintah artisan:**

```bash
php artisan make:model Gallery -m
php artisan make:request Admin/StoreGalleryRequest
php artisan make:request Admin/UpdateGalleryRequest
php artisan make:class Services/GalleryService
php artisan make:controller Admin/GalleryController --resource
```

### Migration

```php
Schema::create('galleries', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->string('image');
    $table->timestamps();
});

Schema::create('activity_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->string('action');
    $table->string('subject_type');
    $table->unsignedBigInteger('subject_id');
    $table->json('changes')->nullable();
    $table->timestamps();
});
```

### Model

```php
// app/Models/Gallery.php
class Gallery extends Model
{
    use LogsActivity;

    protected $fillable = ['title', 'description', 'image'];
}
```

### Trait pencatat aktivitas

```php
// app/Models/Concerns/LogsActivity.php
namespace App\Models\Concerns;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(fn (Model $model) => static::logActivity($model, 'created'));
        static::updated(fn (Model $model) => static::logActivity($model, 'updated'));
        static::deleted(fn (Model $model) => static::logActivity($model, 'deleted'));
    }

    protected static function logActivity(Model $model, string $action): void
    {
        ActivityLog::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'subject_type' => $model::class,
            'subject_id'   => $model->getKey(),
            'changes'      => $action === 'updated' ? $model->getChanges() : null,
        ]);
    }
}
```

Catatan: model `ActivityLog` (`$fillable` + `casts()` `changes => array`) **tidak boleh** memakai trait ini (menghindari rekursi).

### Helper upload

```php
// app/Helpers/UploadHelper.php
namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class UploadHelper
{
    public static function store(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'public')
            ?: throw new RuntimeException('Gagal menyimpan file.');
    }

    public static function delete(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
```

### Form Request (validasi)

```php
// app/Http/Requests/Admin/StoreGalleryRequest.php
class StoreGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // akses sudah dibatasi middleware auth di routes/admin.php
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image'       => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
```

`UpdateGalleryRequest` sama, tetapi `image` menjadi `['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']`.

### Service (logika bisnis)

```php
// app/Services/GalleryService.php
namespace App\Services;

use App\Helpers\UploadHelper;
use App\Models\Gallery;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

class GalleryService
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Gallery::latest()->paginate($perPage);
    }

    public function store(array $data, UploadedFile $image): Gallery
    {
        $path = UploadHelper::store($image, 'galleries');

        try {
            return Gallery::create([...$data, 'image' => $path]);
        } catch (Throwable $e) {
            UploadHelper::delete($path); // bersihkan file yatim, lalu lempar ulang
            throw $e;
        }
    }

    public function update(Gallery $gallery, array $data, ?UploadedFile $image = null): Gallery
    {
        $oldPath = $gallery->image;
        $newPath = $image ? UploadHelper::store($image, 'galleries') : null;

        try {
            $gallery->update($newPath ? [...$data, 'image' => $newPath] : $data);
        } catch (Throwable $e) {
            UploadHelper::delete($newPath);
            throw $e;
        }

        if ($newPath) {
            UploadHelper::delete($oldPath); // hapus file lama hanya setelah DB berhasil
        }

        return $gallery;
    }

    public function delete(Gallery $gallery): void
    {
        $gallery->delete();
        UploadHelper::delete($gallery->image);
    }
}
```

### Controller (tipis)

```php
// app/Http/Controllers/Admin/GalleryController.php
class GalleryController extends Controller
{
    public function __construct(private GalleryService $galleryService) {}

    public function index(): View
    {
        return view('admin.galleries.index', [
            'galleries' => $this->galleryService->paginate(),
        ]);
    }

    public function create(): View
    {
        return view('admin.galleries.create');
    }

    public function store(StoreGalleryRequest $request): RedirectResponse
    {
        try {
            $this->galleryService->store($request->safe()->except('image'), $request->file('image'));
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Gagal menyimpan galeri.');
        }

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil ditambahkan.');
    }

    public function edit(Gallery $gallery): View
    {
        return view('admin.galleries.edit', ['gallery' => $gallery]);
    }

    public function update(UpdateGalleryRequest $request, Gallery $gallery): RedirectResponse
    {
        try {
            $this->galleryService->update($gallery, $request->safe()->except('image'), $request->file('image'));
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Gagal memperbarui galeri.');
        }

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        try {
            $this->galleryService->delete($gallery);
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'Gagal menghapus galeri.');
        }

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil dihapus.');
    }
}
```

### Route

```php
// routes/admin.php
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('galleries', GalleryController::class)->except('show');
});
```

### View

Folder `resources/views/admin/galleries/`:

- `index.blade.php`: daftar memakai `<x-admin.page-header>`, `<x-admin.table>`, dan `<x-ui.confirm-delete>`, plus pagination
- `create.blade.php` dan `edit.blade.php`: berisi tag `<form>` (action, `@method('PUT')` pada edit, `enctype="multipart/form-data"`), lalu `@include('admin.galleries._form')`
- `_form.blade.php`: satu form dipakai bersama (hindari duplikasi create/edit), seluruhnya disusun dari Blade component:

```blade
@csrf
<x-ui.input name="title" label="Judul" :value="old('title', $gallery->title ?? '')" required />
<x-ui.textarea name="description" label="Deskripsi" :value="old('description', $gallery->description ?? '')" />
<x-ui.file-upload name="image" label="Gambar"
                  :current="isset($gallery) ? asset('storage/'.$gallery->image) : null" />
<x-ui.button type="submit">Simpan</x-ui.button>
```

---

## 10. Daftar Larangan (Ringkas)

- Filament, Livewire, Inertia, jQuery, Bootstrap, atau package baru tanpa izin.
- Query/logika di `routes/*.php`.
- Query Eloquent, `$request->validate()` inline, atau upload file di Controller.
- Repository pattern, interface per service, dan `new Service()` / Service static.
- Memanggil `ActivityLog::create()` secara manual dari Service/Controller.
- `DB::beginTransaction()/commit()/rollBack()` manual dan `try/catch` ganda yang berulang.
- `$guarded = []`, `env()` di luar `config/`, `{!! !!}` untuk input user, `$request->all()`.
- `Model::all()` pada daftar data (wajib `paginate()`).
- Blok `@php` berisi query/logika di Blade dan fetch data lewat Alpine.
- Menulis ulang markup atau `x-data` untuk tombol, input, modal, dan elemen lain yang sudah punya component (wajib di admin), atau membuat component duplikat.
- Menambahkan kembali fitur Breeze selain login dan logout.
- Menyisakan `dd()`/`dump()`/kode yang dikomentari.
- (AI) Mengubah file di luar scope tugas, atau menebak isi file yang tidak terlihat (minta developer menempelkannya).

---

## 11. Checklist Sebelum Selesai

- [ ] Setiap logika berada di layer yang benar (bagian 4)
- [ ] Controller tipis: tanpa query, validasi, atau upload
- [ ] Validasi ada di Form Request
- [ ] Elemen UI berulang di halaman admin memakai Blade component yang sudah ada (tanpa markup atau Alpine duplikat)
- [ ] Tidak ada kode duplikat; helper/component yang sudah ada dipakai ulang
- [ ] `try/catch` dan transaksi mengikuti bagian 6
- [ ] Tidak ada pencatatan log manual; model memakai `LogsActivity` bila perlu dicatat
- [ ] Tidak ada package baru yang ditambahkan
- [ ] `./vendor/bin/pint` bersih dan `php artisan test` lulus (jika ada test untuk modul tersebut)
- [ ] Tidak ada `dd()`/`dump()`/kode komentar yang tertinggal
