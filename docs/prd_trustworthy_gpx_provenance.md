# PRD: Trustworthy Hiking Route Recommendation dengan GPX Provenance, IPFS, dan Blockchain

## Problem Statement

RuteStrip V2 saat ini sudah memiliki sistem rekomendasi jalur pendakian berbasis SBERT dan cosine similarity, upload GPX, ekstraksi fitur rute, visualisasi peta, serta RAG chatbot. Sistem ini sudah cukup kuat untuk paper sebelumnya yang berfokus pada rekomendasi dan pemanfaatan data GPX.

Untuk paper SINTA 3 berikutnya, diperlukan kontribusi baru yang lebih jelas dari sisi kepercayaan data. Masalah utama yang ingin diselesaikan adalah file GPX yang digunakan dalam sistem rekomendasi belum memiliki mekanisme provenance, version control, integrity verification, dan penyimpanan terverifikasi. Akibatnya, pengguna dan reviewer belum dapat memastikan apakah GPX yang menjadi dasar rekomendasi benar-benar asli, belum berubah, memiliki riwayat versi yang jelas, dan dapat diverifikasi secara independen.

Fitur dalam folder `paper-jaic` mengusulkan pengembangan RuteStrip menjadi sistem rekomendasi jalur pendakian yang tidak hanya relevan secara semantik, tetapi juga terpercaya karena data GPX-nya dapat dilacak dan diverifikasi.

## Solution

Tambahkan modul Trustworthy GPX Dataset Management ke RuteStrip V2. Modul ini memperluas sistem rekomendasi yang sudah ada dengan kemampuan:

1. Menyimpan setiap upload GPX sebagai versi dataset.
2. Membuat hash SHA-256 untuk setiap file GPX.
3. Menyimpan metadata file GPX ke database.
4. Mengunggah GPX ke IPFS melalui Pinata atau provider IPFS sejenis.
5. Menyimpan CID IPFS sebagai metadata provenance.
6. Mendaftarkan metadata penting ke blockchain registry.
7. Menampilkan riwayat provenance dan versi GPX pada halaman detail rute.
8. Memverifikasi integritas GPX dengan membandingkan hash file lokal/IPFS dengan hash yang tersimpan.
9. Menyediakan evaluasi untuk recommendation quality, integrity accuracy, version recovery accuracy, IPFS performance, dan usability.

Tujuan akhirnya adalah menghasilkan sistem rekomendasi jalur pendakian yang memiliki dua aspek utama: relevansi rekomendasi dan kepercayaan dataset.

## Research Alignment

Judul paper yang dirujuk:

**Design and Implementation of a Trustworthy Hiking Route Recommendation System with GPX Dataset Provenance Using Blockchain and IPFS**

Kontribusi penelitian yang harus tercermin dalam sistem:

1. Sistem rekomendasi jalur pendakian berbasis SBERT dan cosine similarity.
2. Integrasi GPX dataset provenance ke workflow rekomendasi.
3. Version control untuk file GPX.
4. Integrity verification berbasis hash, IPFS, dan blockchain metadata registry.
5. Peningkatan trustworthiness pada sistem rekomendasi jalur pendakian.

Research questions yang didukung sistem:

1. Bagaimana rekomendasi berbasis SBERT dapat memberikan rekomendasi jalur pendakian yang relevan?
2. Bagaimana blockchain dan IPFS dapat mendukung provenance dataset GPX?
3. Seberapa efektif mekanisme version control dan integrity verification yang diusulkan?
4. Bagaimana persepsi pengguna terhadap usability dan usefulness sistem?

## User Stories

1. Sebagai pengguna, saya ingin mencari rekomendasi jalur pendakian berdasarkan preferensi natural language, sehingga saya dapat menemukan jalur yang sesuai kebutuhan.
2. Sebagai pengguna, saya ingin melihat status verifikasi GPX pada hasil rekomendasi, sehingga saya tahu apakah data rute yang direkomendasikan terpercaya.
3. Sebagai pengguna, saya ingin melihat riwayat versi GPX pada detail rute, sehingga saya dapat mengetahui perubahan data rute dari waktu ke waktu.
4. Sebagai pengguna, saya ingin melihat hash file GPX, CID IPFS, dan timestamp, sehingga saya dapat mengetahui jejak provenance file GPX.
5. Sebagai pengguna, saya ingin sistem memberi label verified atau invalid, sehingga saya mudah memahami status integritas rute.
6. Sebagai pengguna, saya ingin mengunduh GPX versi aktif, sehingga saya dapat menggunakan data rute yang sudah diverifikasi.
7. Sebagai pengguna, saya ingin melihat penjelasan singkat tentang arti status verifikasi, sehingga saya memahami dasar kepercayaan sistem.
8. Sebagai admin, saya ingin mengunggah GPX baru sebagai rute baru, sehingga sistem dapat memproses jalur pendakian baru.
9. Sebagai admin, saya ingin mengunggah GPX baru sebagai versi baru dari rute yang sudah ada, sehingga perubahan data rute dapat dicatat tanpa menghapus versi lama.
10. Sebagai admin, saya ingin menulis change log saat membuat versi baru, sehingga alasan perubahan dataset terdokumentasi.
11. Sebagai admin, saya ingin sistem otomatis menghitung SHA-256 file GPX, sehingga integritas file dapat diverifikasi.
12. Sebagai admin, saya ingin sistem otomatis mengunggah GPX ke IPFS, sehingga file memiliki referensi penyimpanan terdistribusi.
13. Sebagai admin, saya ingin sistem menyimpan CID IPFS dari setiap versi GPX, sehingga file dapat dilacak di IPFS.
14. Sebagai admin, saya ingin sistem mendaftarkan metadata GPX ke blockchain, sehingga provenance memiliki jejak audit immutable.
15. Sebagai admin, saya ingin melihat daftar semua versi GPX pada satu rute, sehingga pengelolaan dataset lebih mudah.
16. Sebagai admin, saya ingin memverifikasi ulang GPX secara manual, sehingga saya dapat memastikan file belum berubah.
17. Sebagai admin, saya ingin melakukan restore ke versi GPX tertentu, sehingga sistem dapat memulihkan data rute apabila versi terbaru bermasalah.
18. Sebagai admin, saya ingin melihat hasil upload time dan retrieval time IPFS, sehingga performa IPFS dapat dievaluasi.
19. Sebagai admin, saya ingin melihat transaksi blockchain terkait versi GPX, sehingga metadata registry dapat diaudit.
20. Sebagai admin, saya ingin export data provenance ke CSV, sehingga data dapat digunakan dalam tabel evaluasi paper.
21. Sebagai peneliti, saya ingin menghasilkan tabel Precision@5 dan Precision@10, sehingga kualitas rekomendasi dapat dilaporkan.
22. Sebagai peneliti, saya ingin menghasilkan tabel hash verification accuracy, sehingga efektivitas integrity verification dapat dilaporkan.
23. Sebagai peneliti, saya ingin menghasilkan tabel version recovery accuracy, sehingga efektivitas version control dapat dilaporkan.
24. Sebagai peneliti, saya ingin menghasilkan tabel upload time dan retrieval time IPFS, sehingga performa IPFS dapat dianalisis.
25. Sebagai peneliti, saya ingin menyimpan hasil SUS questionnaire, sehingga usability sistem dapat dievaluasi.
26. Sebagai reviewer paper, saya ingin melihat bahwa blockchain hanya digunakan untuk metadata registry, sehingga scope penelitian tetap realistis dan tidak berlebihan.
27. Sebagai reviewer paper, saya ingin melihat bahwa database tetap menjadi operational storage, sehingga arsitektur sistem tetap praktis.
28. Sebagai reviewer paper, saya ingin melihat perbandingan sebelum dan sesudah penambahan provenance, sehingga kontribusi penelitian terlihat jelas.

## Functional Requirements

### 1. GPX Version Control

Sistem harus mendukung penyimpanan banyak versi GPX untuk satu rute pendakian.

Kebutuhan:

1. Setiap upload GPX pertama menjadi versi 1.
2. Upload GPX berikutnya pada rute yang sama menjadi versi baru.
3. Setiap versi memiliki file path, hash, CID IPFS, uploader, timestamp, dan change log.
4. Satu versi ditandai sebagai active version.
5. Versi lama tidak boleh dihapus secara permanen dari riwayat provenance.
6. Admin dapat melihat semua versi dalam urutan waktu.
7. Admin dapat memulihkan versi lama menjadi versi aktif.

### 2. Hash Generation

Sistem harus membuat hash SHA-256 dari setiap file GPX.

Kebutuhan:

1. Hash dibuat saat upload file GPX.
2. Hash disimpan di database.
3. Hash digunakan sebagai dasar integrity verification.
4. Hash yang sama dapat digunakan untuk metadata blockchain registry.
5. Sistem harus dapat menghitung ulang hash dari file lokal atau file yang diambil dari IPFS.

### 3. IPFS Storage

Sistem harus mendukung upload file GPX ke IPFS.

Kebutuhan:

1. Integrasi awal menggunakan Pinata API atau provider IPFS kompatibel.
2. Sistem menyimpan CID yang dikembalikan oleh IPFS.
3. Sistem menyimpan URL gateway IPFS.
4. Sistem mencatat waktu upload IPFS.
5. Sistem dapat mengambil ulang file dari IPFS untuk verifikasi.
6. Sistem mencatat waktu retrieval IPFS.
7. Jika IPFS gagal, upload GPX ke sistem tetap dapat ditandai sebagai pending IPFS, bukan langsung menghapus data lokal.

### 4. Blockchain Metadata Registry

Blockchain digunakan untuk metadata registry, bukan untuk menyimpan file GPX.

Metadata minimal:

1. Route ID.
2. Version number.
3. File hash.
4. CID IPFS.
5. Timestamp.
6. Uploader identifier atau wallet address.

Kebutuhan:

1. Tahap awal dapat menggunakan Ganache/local blockchain untuk pengujian.
2. Tahap lanjutan dapat menggunakan Sepolia testnet.
3. Sistem menyimpan transaction hash di database.
4. Sistem dapat menampilkan status registrasi blockchain.
5. Jika blockchain gagal, versi GPX tetap tersimpan dengan status pending blockchain.

### 5. Integrity Verification

Sistem harus memverifikasi integritas GPX.

Alur verifikasi:

1. Ambil file GPX dari storage lokal atau IPFS.
2. Generate ulang SHA-256 hash.
3. Ambil hash referensi dari database atau blockchain metadata registry.
4. Bandingkan hash baru dengan hash referensi.
5. Tampilkan status verified jika hash cocok.
6. Tampilkan status invalid jika hash tidak cocok.
7. Simpan hasil verifikasi untuk evaluasi.

Status yang dibutuhkan:

1. `verified`: hash cocok.
2. `invalid`: hash tidak cocok.
3. `pending_ipfs`: file belum berhasil diunggah ke IPFS.
4. `pending_blockchain`: metadata belum berhasil didaftarkan ke blockchain.
5. `verification_failed`: proses verifikasi gagal karena masalah teknis.

### 6. Provenance History Page

Sistem harus menyediakan halaman provenance per rute.

Informasi yang ditampilkan:

1. Nama rute.
2. Versi GPX.
3. Status active version.
4. File hash.
5. CID IPFS.
6. Blockchain transaction hash.
7. Timestamp upload.
8. Uploader.
9. Change log.
10. Verification status.
11. Tombol verify ulang.
12. Tombol restore versi untuk admin.

### 7. Recommendation Result with Trust Indicator

Hasil rekomendasi harus menampilkan indikator trustworthiness.

Kebutuhan:

1. Hasil pencarian tetap diurutkan berdasarkan cosine similarity.
2. Setiap hasil menampilkan status GPX verified atau belum.
3. Sistem dapat menampilkan badge seperti `Verified GPX`, `Pending Verification`, atau `Invalid GPX`.
4. Untuk penelitian lanjutan, trust score dapat ditambahkan sebagai faktor tambahan, tetapi tidak wajib untuk MVP.

### 8. Evaluation Module

Sistem harus mendukung pengambilan data evaluasi untuk paper.

Evaluasi yang dibutuhkan:

1. Precision@5.
2. Precision@10.
3. Hash Verification Accuracy.
4. Version Recovery Accuracy.
5. IPFS Upload Time.
6. IPFS Retrieval Time.
7. Black box testing result.
8. SUS Score.

Output evaluasi:

1. CSV.
2. Tabel siap masuk paper.
3. Ringkasan metrik di dashboard admin atau halaman evaluasi sederhana.

## Non-Functional Requirements

1. Sistem harus tetap berjalan walaupun IPFS atau blockchain provider sedang gagal.
2. Upload GPX tidak boleh kehilangan file lokal jika proses IPFS/blockchain gagal.
3. Hash generation harus deterministic dan menggunakan algoritma standar SHA-256.
4. Data provenance tidak boleh bergantung pada session sementara.
5. Halaman provenance harus mudah dipahami oleh user non-teknis.
6. Konfigurasi API key Pinata dan blockchain RPC harus menggunakan `.env`.
7. Tidak boleh menyimpan private key blockchain langsung di source code.
8. Fitur admin harus dilindungi middleware autentikasi dan role admin.
9. Sistem harus tetap mempertahankan fitur rekomendasi yang sudah ada.
10. Perubahan schema harus dibuat melalui migration Laravel.

## Proposed Data Model

### gpx_versions

Menyimpan semua versi GPX untuk setiap rute.

Field yang dibutuhkan:

1. `id`
2. `hiking_route_id`
3. `version_number`
4. `gpx_file_path`
5. `file_hash`
6. `ipfs_cid`
7. `ipfs_url`
8. `blockchain_tx_hash`
9. `verification_status`
10. `change_log`
11. `uploaded_by`
12. `is_active`
13. `ipfs_upload_time_ms`
14. `ipfs_retrieval_time_ms`
15. `created_at`
16. `updated_at`

### verification_logs

Menyimpan riwayat proses verifikasi.

Field yang dibutuhkan:

1. `id`
2. `gpx_version_id`
3. `source`
4. `expected_hash`
5. `actual_hash`
6. `status`
7. `message`
8. `verified_by`
9. `verified_at`
10. `created_at`
11. `updated_at`

### blockchain_registry_logs

Menyimpan log registrasi metadata ke blockchain.

Field yang dibutuhkan:

1. `id`
2. `gpx_version_id`
3. `network`
4. `contract_address`
5. `transaction_hash`
6. `payload`
7. `status`
8. `error_message`
9. `created_at`
10. `updated_at`

### ipfs_upload_logs

Menyimpan log upload dan retrieval IPFS.

Field yang dibutuhkan:

1. `id`
2. `gpx_version_id`
3. `provider`
4. `cid`
5. `gateway_url`
6. `operation`
7. `duration_ms`
8. `status`
9. `error_message`
10. `created_at`
11. `updated_at`

### evaluation_results

Menyimpan hasil evaluasi untuk paper.

Field yang dibutuhkan:

1. `id`
2. `metric_name`
3. `metric_value`
4. `dataset_size`
5. `notes`
6. `created_at`
7. `updated_at`

## Proposed Modules

### 1. GpxVersionService

Tanggung jawab:

1. Membuat versi GPX baru.
2. Menentukan nomor versi berikutnya.
3. Menandai active version.
4. Restore versi lama.
5. Menghubungkan versi GPX dengan rute pendakian.

Alasan dibuat sebagai service:

1. Logic version control akan dipakai dari upload single, batch, dan restore.
2. Mudah diuji secara terpisah.
3. Mengurangi logic besar di controller.

### 2. GpxHashService

Tanggung jawab:

1. Generate SHA-256 hash file GPX.
2. Compare expected hash dan actual hash.
3. Memberikan hasil verified atau invalid.

Alasan dibuat sebagai service:

1. Core integrity verification harus sederhana dan testable.
2. Tidak bergantung pada Laravel controller.

### 3. IpfsStorageService

Tanggung jawab:

1. Upload GPX ke IPFS provider.
2. Simpan CID dan gateway URL.
3. Retrieve file dari IPFS.
4. Catat upload time dan retrieval time.

Alasan dibuat sebagai service:

1. Provider IPFS dapat diganti tanpa mengubah controller.
2. Memudahkan mocking saat test.

### 4. BlockchainRegistryService

Tanggung jawab:

1. Mendaftarkan metadata GPX ke blockchain.
2. Menyimpan transaction hash.
3. Mengambil metadata registry jika dibutuhkan.
4. Menangani status pending atau failed.

Alasan dibuat sebagai service:

1. Blockchain adalah integrasi eksternal yang perlu diisolasi.
2. Implementasi awal dapat fake/local, lalu diganti ke Ganache/Sepolia.

### 5. ProvenanceController

Tanggung jawab:

1. Menampilkan provenance history page.
2. Memicu verifikasi ulang.
3. Memproses restore versi.
4. Menampilkan detail metadata IPFS dan blockchain.

### 6. EvaluationService

Tanggung jawab:

1. Menghitung Precision@5 dan Precision@10.
2. Menghitung hash verification accuracy.
3. Menghitung version recovery accuracy.
4. Menghasilkan export CSV untuk tabel paper.

## Implementation Decisions

1. Sistem rekomendasi SBERT dan cosine similarity yang sudah ada tetap dipertahankan sebagai baseline recommendation module.
2. Trustworthiness tidak menggantikan similarity score pada MVP; trust indicator hanya ditampilkan sebagai metadata tambahan.
3. Database tetap menjadi operational storage utama.
4. IPFS digunakan untuk decentralized file reference dan retrieval verification.
5. Blockchain digunakan hanya untuk metadata registry dan immutable audit trail.
6. File GPX tidak disimpan langsung di blockchain.
7. Hash SHA-256 menjadi dasar utama integrity verification.
8. Setiap rute dapat memiliki banyak versi GPX.
9. Setiap versi GPX dapat memiliki status verifikasi sendiri.
10. Upload GPX tetap berhasil secara lokal meskipun IPFS/blockchain gagal, tetapi statusnya ditandai pending.
11. Admin menjadi aktor utama untuk upload, versioning, restore, dan export evaluasi.
12. User umum dapat melihat trust indicator dan provenance history, tetapi tidak dapat mengubah versi.
13. Evaluasi paper harus dapat diekspor ke CSV agar mudah dimasukkan ke dokumen penelitian.

## Environment Variables Needed

Konfigurasi yang dibutuhkan:

1. `PINATA_API_KEY`
2. `PINATA_SECRET_API_KEY`
3. `PINATA_JWT`
4. `IPFS_GATEWAY_URL`
5. `BLOCKCHAIN_RPC_URL`
6. `BLOCKCHAIN_PRIVATE_KEY`
7. `BLOCKCHAIN_CONTRACT_ADDRESS`
8. `BLOCKCHAIN_NETWORK`

Catatan keamanan:

1. Private key tidak boleh masuk repository.
2. `.env.example` boleh memuat nama variable tanpa value rahasia.
3. Untuk development awal, blockchain service boleh menggunakan mode fake/local registry sebelum smart contract selesai.

## External Dependencies Needed

### Laravel/PHP

Kemungkinan dependency:

1. HTTP client Laravel bawaan untuk Pinata API.
2. Package Web3 PHP jika integrasi blockchain dilakukan langsung dari Laravel.
3. Alternatif: Node.js script kecil untuk blockchain transaction jika Web3 PHP terlalu berat.

### Node.js / Blockchain

Jika memakai smart contract Ethereum:

1. Hardhat.
2. Ethers.js.
3. Solidity contract untuk metadata registry.
4. Ganache untuk local testing.
5. Sepolia RPC provider untuk testnet.

### IPFS

1. Pinata account.
2. Pinata API key/JWT.
3. Gateway URL.

## Smart Contract Scope

Smart contract minimal cukup menyimpan event atau mapping metadata.

Metadata registry minimal:

1. Route ID.
2. Version number.
3. File hash.
4. IPFS CID.
5. Timestamp.
6. Uploader address.

Fungsi minimal:

1. `registerGpxVersion(routeId, versionNumber, fileHash, ipfsCid)`
2. `getGpxVersion(routeId, versionNumber)`

Event minimal:

1. `GpxVersionRegistered(routeId, versionNumber, fileHash, ipfsCid, uploader, timestamp)`

## UI Requirements

### Route Detail Page

Tambahan tampilan:

1. Badge GPX verification status.
2. Link ke provenance history.
3. Informasi active GPX version.
4. Ringkasan CID IPFS dan hash.

### Provenance History Page

Komponen:

1. Timeline versi GPX.
2. Tabel metadata versi.
3. Badge status verifikasi.
4. Tombol verify ulang.
5. Tombol restore versi untuk admin.
6. Link IPFS gateway.
7. Link blockchain explorer jika menggunakan testnet.

### Admin Upload/Edit Route Page

Tambahan input:

1. Upload sebagai rute baru atau versi baru.
2. Change log versi.
3. Status upload IPFS.
4. Status registry blockchain.

### Evaluation Page

Komponen:

1. Ringkasan Precision@5 dan Precision@10.
2. Ringkasan hash verification accuracy.
3. Ringkasan version recovery accuracy.
4. Tabel upload/retrieval time IPFS.
5. Export CSV.

## Testing Decisions

Test harus fokus pada perilaku eksternal, bukan detail implementasi internal.

Yang perlu diuji:

1. Upload GPX membuat versi pertama.
2. Upload GPX baru pada rute yang sama membuat versi berikutnya.
3. Hash SHA-256 dibuat dan tersimpan.
4. File yang tidak berubah menghasilkan status verified.
5. File yang berubah menghasilkan status invalid.
6. Restore versi lama mengubah active version.
7. IPFS failure tidak menghapus GPX lokal.
8. Blockchain failure menghasilkan status pending blockchain.
9. Provenance page menampilkan semua versi.
10. Hasil rekomendasi tetap muncul dan menampilkan trust indicator.
11. Export evaluasi menghasilkan CSV.

Jenis test:

1. Unit test untuk `GpxHashService`.
2. Unit test untuk `GpxVersionService`.
3. Feature test untuk upload GPX versioning.
4. Feature test untuk integrity verification.
5. Feature test untuk provenance history page.
6. Feature test untuk restore version.
7. Feature test untuk export evaluation data.

External integration seperti IPFS dan blockchain sebaiknya menggunakan fake service pada automated tests.

## Evaluation Plan for Paper

### Recommendation Quality

Metrik:

1. Precision@5 > 0.75.
2. Precision@10 > 0.70.

Kebutuhan data:

1. Query uji.
2. Ground truth relevansi rute.
3. Hasil ranking rekomendasi.

### Integrity Evaluation

Metrik:

1. Hash Verification Accuracy = 100%.

Skenario:

1. File GPX asli diverifikasi.
2. File GPX yang dimodifikasi diverifikasi.
3. Sistem harus membedakan verified dan invalid.

### Version Control Evaluation

Metrik:

1. Version Recovery Accuracy = 100%.

Skenario:

1. Buat beberapa versi GPX untuk satu rute.
2. Restore versi lama.
3. Pastikan active version sesuai versi yang dipilih.
4. Pastikan hash dan metadata sesuai versi tersebut.

### IPFS Evaluation

Metrik:

1. Upload time.
2. Retrieval time.
3. Upload success rate.
4. Retrieval success rate.

### Usability Evaluation

Metrik:

1. SUS Score > 70.

Kebutuhan:

1. Form SUS.
2. Minimal responden uji.
3. Rekap skor.

## Implementation Phases

### Phase 1: Database dan Core Versioning

Output:

1. Migration `gpx_versions`.
2. Model GPX version.
3. Relasi dengan hiking route.
4. Service versioning.
5. Upload GPX membuat versi.

### Phase 2: Hash dan Integrity Verification

Output:

1. Service hash SHA-256.
2. Verification logs.
3. Manual verify action.
4. Badge verified/invalid.

### Phase 3: Provenance UI

Output:

1. Provenance history page.
2. Timeline versi.
3. Metadata hash, uploader, timestamp, change log.
4. Restore version untuk admin.

### Phase 4: IPFS Integration

Output:

1. Pinata integration.
2. CID storage.
3. IPFS upload/retrieval logs.
4. IPFS timing metrics.

### Phase 5: Blockchain Registry

Output:

1. Smart contract metadata registry.
2. Local Ganache testing.
3. Transaction hash storage.
4. Optional Sepolia deployment.

### Phase 6: Recommendation Trust Indicator

Output:

1. Verification badge pada hasil rekomendasi.
2. Link provenance dari hasil rekomendasi.
3. Optional trust score untuk future work.

### Phase 7: Evaluation and Paper Outputs

Output:

1. CSV Precision@5 dan Precision@10.
2. CSV hash verification accuracy.
3. CSV version recovery accuracy.
4. CSV IPFS performance.
5. Ringkasan tabel untuk paper.

## Out of Scope

Hal-hal berikut tidak wajib untuk MVP paper:

1. Mobile app.
2. Real-time GPS tracking.
3. Collaborative recommendation.
4. Contributor trust score.
5. Full production blockchain deployment.
6. Penyimpanan file GPX langsung di blockchain.
7. Decentralized identity untuk uploader.
8. Multi-chain support.
9. Trust-aware ranking yang mengubah skor rekomendasi.
10. Moderasi komunitas untuk GPX crowdsourcing.

## Risks and Mitigations

### Risk: Blockchain integration terlalu kompleks

Mitigasi:

Mulai dari fake/local registry atau Ganache. Untuk paper, cukup tunjukkan metadata registry dan transaction hash sebagai proof-of-concept.

### Risk: IPFS provider gagal atau lambat

Mitigasi:

Gunakan status pending IPFS dan tetap simpan file lokal. Catat failure sebagai bagian dari evaluasi.

### Risk: Scope terlalu besar untuk SINTA 3

Mitigasi:

Batasi kontribusi pada provenance, version control, dan verification. Trust score dan contributor reputation dijadikan future work.

### Risk: UI terlalu teknis untuk pengguna umum

Mitigasi:

Gunakan badge sederhana seperti Verified GPX, Pending, Invalid, dan tampilkan detail teknis di halaman provenance.

### Risk: Dataset belum cukup besar

Mitigasi:

Target minimal sesuai paper: 20 gunung, 50-100 rute, 50-100 GPX, dan beberapa rute dengan banyak versi.

## Dataset Requirements

Target dataset:

1. Minimal 20 gunung.
2. 50-100 jalur pendakian.
3. 50-100 file GPX.
4. Beberapa rute memiliki minimal 2 versi GPX.
5. Beberapa rute memiliki versi yang sengaja dimodifikasi untuk integrity testing.

Sumber dataset:

1. Public GPX repositories.
2. Komunitas pendakian.
3. OpenStreetMap derived routes.
4. Personal GPS tracking data.
5. Public hiking datasets.

## Definition of Done

Fitur dianggap selesai untuk kebutuhan paper jika:

1. Rute bisa memiliki banyak versi GPX.
2. Setiap versi GPX memiliki hash SHA-256.
3. Setiap versi GPX memiliki halaman provenance.
4. Sistem bisa menandai GPX sebagai verified atau invalid.
5. Sistem bisa restore versi lama.
6. Sistem bisa menyimpan CID IPFS minimal melalui Pinata.
7. Sistem bisa menyimpan transaction hash blockchain registry minimal dari local blockchain.
8. Hasil rekomendasi menampilkan trust indicator.
9. Data evaluasi dapat diekspor ke CSV.
10. Metrik paper dapat dihitung dan dilaporkan.

## Suggested Paper Deliverables

Output yang perlu disiapkan untuk penulisan paper:

1. Diagram arsitektur sistem baru.
2. Diagram alur provenance GPX.
3. Diagram proses integrity verification.
4. Tabel dataset GPX.
5. Tabel versi GPX.
6. Tabel hasil Precision@5 dan Precision@10.
7. Tabel hash verification accuracy.
8. Tabel version recovery accuracy.
9. Tabel IPFS upload/retrieval time.
10. Tabel black box testing.
11. Tabel SUS score.
12. Screenshot halaman rekomendasi dengan trust badge.
13. Screenshot halaman provenance history.
14. Screenshot halaman integrity verification.
15. Screenshot transaksi blockchain atau local blockchain log.

## Recommended MVP Priority

Urutan prioritas paling aman:

1. GPX versioning.
2. Hash verification.
3. Provenance page.
4. Restore version.
5. IPFS upload.
6. Blockchain metadata registry.
7. Evaluation export.
8. Trust badge pada rekomendasi.

Urutan ini dipilih karena versioning dan hash verification adalah inti penelitian. IPFS dan blockchain dapat ditambahkan setelah alur provenance lokal stabil.
