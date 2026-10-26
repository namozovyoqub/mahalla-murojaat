# Mahalla onlayn murojaat tizimi

Zamonaviy, xavfsiz va qulay onlayn murojaat tizimi (PHP 8 + MySQL 8 / SQLite, Bootstrap 5). XAMPP va Vercel serverless muhitida ishlash uchun to'liq moslashtirilgan.

## Asosiy imkoniyatlar
- Fuqarolar uchun: Ro'yxatdan o'tish, OneID orqali kirish (simulyatsiya), murojaat (ariza, shikoyat, taklif) yuborish, fayl biriktirish, murojaat holatini maxsus kod orqali kuzatish (Track), parolni tiklash.
- Admin panel: Murojaatlarni ko'rish, filtrlash va qidirish (ID, kod, sarlavha, fuqaro ismi), statusni o'zgartirish, admin izohini kiritish, o'zgarishlar tarixi (loglar), yangiliklar qo'shish/tahrirlash/o'chirish, foydalanuvchilar ro'yxati.
- Ikki tilli interfeys (UZ / RU) va Tungi rejim (Dark Mode).
- Integratsiyalangan AI chatbot maslahatchisi.

## Texnologiyalar
- **PHP 8+** (PDO, prepared statements, strict types)
- **MySQL 8+** yoki avtomatik **SQLite** fallback (Vercel serverless uchun)
- **Frontend**: Bootstrap 5, Font Awesome 6, Inter font, Responsive UI
- **Xavfsizlik**: `password_hash` / `password_verify`, CSRF token, XSS escape (`e()`), SQL Injection himoyasi, xavfsiz session va cookie sozlamalari, yuklangan fayllar tekshiruvi.

## Katalog tuzilmasi
```text
/
├─ admin/              # Admin paneli sahifalari
├─ api/                # Vercel serverless router (api/index.php)
├─ config/             # DB konfiguratsiyasi va avto-migratsiya
├─ includes/           # Header, footer, auth, csrf, helpers, tillar
├─ public/             # Foydalanuvchi sahifalari va statik aktivlar
├─ uploads/            # Yuklangan fayllar
├─ database.sql        # MySQL DB sxemasi
├─ vercel.json         # Vercel deploy sozlamalari
└─ README.md
```

## O'rnatish va ishga tushirish (XAMPP / Local)
1. Loyihani `C:\xampp\htdocs\Mahalla` papkasiga joylashtiring.
2. XAMPP Control Panel orqali Apache va MySQL ni ishga tushiring.
3. phpMyAdmin orqali `database.sql` faylini import qiling (yoki tizim o'zi avtomatik yaratadi).
4. Brauzerda oching: `http://localhost/Mahalla/` yoki `http://localhost/Mahalla/public/login.php`
5. Admin hisobi (birlamchi):
   - **Email:** `admin@mahalla.local`
   - **Parol:** `admin123` (yoki `123456`)

## Vercel platformasiga joylash (Deploy to Vercel)
Loyiha `vercel.json` va `api/index.php` orqali Vercel uchun to'liq sozlangan:
1. `vercel` buyrug'i orqali deploy qiling:
   ```bash
   vercel --prod
   ```
2. Loyiha Vercel'da serverless rejimida SQLite yoki masofaviy MySQL orqali ishlaydi.
3. Agar tashqi MySQL (PlanetScale, Aiven, Supabase, TiDB) ulamoqchi bo'lsangiz, Vercel Environment Variables bo'limida quyidagilarni kiriting:
   - `DB_HOST`
   - `DB_NAME`
   - `DB_USER`
   - `DB_PASS`
   - `DB_PORT`

## Litsenziya
O'quv va amaliy maqsadlar uchun yaratilgan.
