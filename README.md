# Hospital Ward — Electronic System (Laravel scaffold)

هذه الحزمة هي scaffold مبدئي لنظام ربط أقسام التنويم والتغذية والصيدلية، مع دعم طباعة ZPL لطابعات Zebra (Code128) وداشبورد تفاعلي يحدث تلقائيًا باستخدام WebSockets (Laravel WebSockets + Laravel Echo).

المتطلبات:
- PHP 8.1+
- Composer
- Node.js + npm
- zip, unzip, git

كيفية الإنشاء المحلي (باستخدام السكربت):
1. احفظ السكربت `create_project_and_zip.sh` على جهازك واجعله قابلًا للتشغيل:
   chmod +x create_project_and_zip.sh
2. قم بنسخ/لصق الملفات المرفقة داخل مجلد `bootstrap_extra` كما هو موضّح في الرسائل.
3. شغّل السكربت:
   ./create_project_and_zip.sh
4. ستحصل على ملف `hospital-system.zip` في المجلد الأعلى (نقله إلى السيرفر الداخلي وفكّه).

تكوين بعد فك الـ zip على السيرفر:
1. انسخ `.env.example` إلى `.env` وغيّر القيم (DB_CONNECTION, DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD, APP_URL).
2. composer install
3. npm install && npm run build
4. php artisan key:generate
5. php artisan migrate --seed
6. شغّل Laravel WebSockets (لو أردت التحديث الفوري):
   php artisan websockets:serve
7. شغّل queue worker إن استخدمت queue:
   php artisan queue:work

طباعة إلى طابعة Zebra عبر الشبكة:
- اضبط IP الطابعة والمنفذ (عادة 9100) في إعدادات الطباعة من لوحة Admin أو في .env (PRINTER_IP).
- النظام يولّد ZPL جاهز للإرسال؛ يمكن إرساله عبر TCP إلى IP:PORT.

دعم اللغة:
- الواجهات مدعومة بالعربية والإنجليزية عبر ملفات resources/lang.

ملاحظات:
- هذا scaffold MVP ويحتاج تخصيص واجهة/تصميم احترافي (يمكن استخدام قالب Admin مثل AdminKit أو أي قالب آخر عبر Tailwind/Bootstrap).
- إن أردت أجهز نسخة zip مرفوعة على GitHub repo (إن وفرت وصولًا) أو أقدّم تثبيتًا مباشرًا على سيرفرك لو زودتني بمعلومات الوصول الآمن.