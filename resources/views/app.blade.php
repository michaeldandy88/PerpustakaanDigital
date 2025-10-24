<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Perpustakaan Digital</title>

  {{-- Vite akan inject script dev server saat `npm run dev` --}}
  @vite('resources/js/app.ts')
</head>
<body>
  {{-- Mount point Vue --}}
  <div id="app"></div>

  {{-- Optional: fallback untuk users yang non-js --}}
  <noscript>
    <p>Silakan aktifkan JavaScript untuk menggunakan aplikasi.</p>
  </noscript>
</body>
</html>