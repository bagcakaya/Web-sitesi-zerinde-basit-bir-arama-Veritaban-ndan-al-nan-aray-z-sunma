# Cari Proje (MySQL)

Bu mini proje, `CARI_KARTLAR` tablosu üzerinde basit bir arama arayüzü sunar.

## Kurulum
1. phpMyAdmin > `project` veritabanını seçin (gerekirse oluşturun).
2. `create_table_cari.sql` dosyasını çalıştırın (tablo ve örnek veriler oluşur).
3. Dosyaları web köküne kopyalayın:
   - XAMPP: `C:\xampp\htdocs\cari_proje\`
4. Tarayıcıdan açın: `http://localhost/cari_proje/cari_search.php`

## Dosyalar
- `config.php`: MySQL bağlantısı (PDO)
- `create_table_cari.sql`: Tablo ve örnek veriler
- `cari_search.php`: Arama formu ve sonuç tablosu

## Arama
- Sıra_No için tam eşleşme
- `Cari_Grup`, `Ek_Ad`, `Adı` alanlarında LIKE araması

## Not
- Tablo ve kolon isimleri Türkçe karakter içerir; `utf8mb4` ile oluşturulmuştur.
