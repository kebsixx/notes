# QuickNotes

QuickNotes adalah web app catatan sederhana berbasis PHP + PostgreSQL: login, simpan catatan, cari, edit, dan hapus.

## Fitur

- Auth: signup, login, logout
- CSRF protection di semua aksi POST
- Notes: create, read, update, delete
- Search notes + pagination
- Flash message (success/error)
- UI berbasis Tailwind (CDN)
- Theme switcher: `slate`, `forest`, `ocean` (tersimpan via `localStorage`)
- Toggle show/hide password di form auth

## Struktur Folder (ringkas)

Struktur utama:

- `app/Controllers/`
  - `AuthController.php`: proses login/logout/signup + validasi CSRF
  - `NoteController.php`: CRUD notes + search + pagination
- `config/`
  - `database.php`: konfigurasi + koneksi PostgreSQL (PDO)
- `public/`
  - `index.php`: entrypoint utama (render `views/notes/index.php`)
  - `signup.php`: entrypoint signup (render `views/auth/signup.php`)
  - `assets/js/auth-ui.js`: toggle show/hide password
- `views/`
  - `auth/`: halaman auth (login panel + signup)
  - `layout/`: komponen UI (alert + session bar)
  - `notes/`: halaman notes (list + card + form)

Shortcut:

- `index.php`: redirect ke `public/index.php`
- `signup.php`: redirect ke `public/signup.php`

## Cara Menjalankan (lokal)

1. Pastikan PHP tersedia.
2. Siapkan PostgreSQL dan sesuaikan kredensial di `config/database.php`.
3. Buat tabel yang dibutuhkan (lihat bagian Database).
4. Jalankan server PHP built-in:

```bash
php -S localhost:8000 -t public
```

5. Buka:
- `http://localhost:8000/index.php`
- `http://localhost:8000/signup.php`

## Database (Sesuai Struktur Table Kamu)

QuickNotes mengakses tabel:
- `public.users` (kolom: `id`, `username`, `password_hash`)
- `public.notes` (kolom: `id`, `content`, `user_id`, `created_at`)

Skema database (DDL):

```sql
CREATE SEQUENCE IF NOT EXISTS users_id_seq;
CREATE SEQUENCE IF NOT EXISTS notes_id_seq;

CREATE TABLE "public"."users" (
  "id" int4 NOT NULL DEFAULT nextval('users_id_seq'::regclass),
  "username" varchar NOT NULL,
  "password_hash" text NOT NULL,
  "created_at" timestamp NOT NULL DEFAULT now(),
  PRIMARY KEY ("id")
);

CREATE TABLE "public"."notes" (
  "id" int4 NOT NULL DEFAULT nextval('notes_id_seq'::regclass),
  "content" text NOT NULL,
  "created_at" timestamp DEFAULT CURRENT_TIMESTAMP,
  "user_id" int4 NOT NULL,
  PRIMARY KEY ("id")
);
```

Import SQL (contoh):

```bash
psql -d kampusdb -f db/users-notes.sql
psql -d kampusdb -f db/notes-notes.sql
```

Catatan:
- Pada skema ini, `notes.id` sudah memiliki default sequence. Namun aplikasi saat ini mengisi `notes.id` dengan `MAX(id)+1` (lihat `app/Controllers/NoteController.php`). Jika ingin lebih rapi, insert bisa dilakukan tanpa mengisi `id` agar sequence yang mengatur.
