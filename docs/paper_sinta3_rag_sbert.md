# Penerapan Retrieval-Augmented Generation dan Sentence-BERT pada Chatbot Rekomendasi Jalur Pendakian Berbasis Data GPX

Penulis Pertama 1*, Penulis Kedua 2, Penulis Ketiga 3  
1,2,3 Program Studi Teknik Informatika, Nama Universitas/Institusi  
email1@domain.ac.id, email2@domain.ac.id, email3@domain.ac.id

## Article Info

Received: -  
Revised: -  
Accepted: -

## ABSTRACT

Hiking route selection requires information about distance, elevation gain, difficulty level, estimated duration, route characteristics, and practical basecamp information. However, conventional search and recommendation systems often rely on keyword matching and are limited in understanding natural language queries such as beginner-friendly routes, scenic sunrise trails, or challenging routes with high elevation. This study develops a hiking route recommendation system by integrating Retrieval-Augmented Generation (RAG) and Sentence-BERT (SBERT) using GPX data and textual route descriptions. GPX files are processed to extract route distance, elevation gain, Naismith-based duration, average grade, minimum elevation, maximum elevation, and route difficulty. These numerical attributes are converted into Indonesian narrative text and combined with curated route descriptions to form a knowledge representation. SBERT with the `paraphrase-multilingual-MiniLM-L12-v2` model is used to generate 384-dimensional embeddings, while cosine similarity is applied to retrieve the most relevant routes. The retrieved routes are then injected into a RAG prompt and processed by Google Gemini to generate contextual responses. The system was evaluated using 39 hiking routes from Indonesia and four natural language query scenarios. The SBERT-based recommendation achieved an average Precision@3 of 0.67 and Precision@5 of 0.60, outperforming TF-IDF with an average Precision@5 of 0.25. The RAG module successfully generated responses grounded in retrieved route data by including route names, similarity scores, distance, elevation, duration, basecamp information, and safety-oriented recommendations. These results indicate that the integration of RAG and SBERT can improve semantic route retrieval and provide more informative hiking route recommendations.

**Keywords:** GPX, Hiking Route, Recommendation System, Retrieval-Augmented Generation, Sentence-BERT

## Abstrak

Pemilihan jalur pendakian membutuhkan informasi mengenai jarak, kenaikan elevasi, tingkat kesulitan, estimasi durasi, karakteristik jalur, serta informasi praktis basecamp. Namun, sistem pencarian dan rekomendasi konvensional umumnya masih berbasis pencocokan kata kunci sehingga terbatas dalam memahami pertanyaan bahasa alami seperti jalur untuk pemula, jalur dengan pemandangan sunrise, atau jalur menantang dengan elevasi tinggi. Penelitian ini mengembangkan sistem rekomendasi jalur pendakian dengan mengintegrasikan Retrieval-Augmented Generation (RAG) dan Sentence-BERT (SBERT) berbasis data GPX dan deskripsi tekstual. Data GPX diproses untuk mengekstraksi jarak, kenaikan elevasi, estimasi durasi berdasarkan rumus Naismith, grade rata-rata, elevasi minimum, elevasi maksimum, dan tingkat kesulitan. Atribut numerik tersebut dikonversi menjadi narasi Bahasa Indonesia dan digabungkan dengan deskripsi jalur untuk membentuk representasi pengetahuan. Model SBERT `paraphrase-multilingual-MiniLM-L12-v2` digunakan untuk menghasilkan embedding berdimensi 384, sedangkan cosine similarity digunakan untuk mengambil jalur paling relevan. Jalur hasil retrieval kemudian dimasukkan ke dalam prompt RAG dan diproses oleh Google Gemini untuk menghasilkan respons kontekstual. Pengujian dilakukan pada 39 jalur pendakian di Indonesia dengan empat skenario query bahasa alami. Rekomendasi berbasis SBERT memperoleh rata-rata Precision@3 sebesar 0,67 dan Precision@5 sebesar 0,60, lebih tinggi dibandingkan TF-IDF dengan Precision@5 sebesar 0,25. Modul RAG mampu menghasilkan respons berbasis konteks data rute yang memuat nama jalur, skor kemiripan, jarak, elevasi, durasi, informasi basecamp, dan saran keselamatan. Hasil ini menunjukkan bahwa integrasi RAG dan SBERT dapat meningkatkan pencarian semantik dan memberikan rekomendasi jalur pendakian yang lebih informatif.

**Kata kunci:** GPX, Jalur Pendakian, Retrieval-Augmented Generation, Sentence-BERT, Sistem Rekomendasi

## I. Pendahuluan

Aktivitas pendakian gunung merupakan salah satu bentuk wisata alam yang banyak diminati di Indonesia. Dalam praktiknya, pemilihan jalur pendakian tidak hanya bergantung pada nama gunung, tetapi juga pada karakteristik rute seperti jarak tempuh, kenaikan elevasi, estimasi waktu, tingkat kecuraman, ketersediaan basecamp, fasilitas, musim terbaik, dan kondisi medan. Informasi tersebut penting karena setiap pendaki memiliki preferensi dan kemampuan fisik yang berbeda. Pendaki pemula cenderung membutuhkan jalur dengan tingkat kesulitan rendah dan estimasi waktu yang tidak terlalu panjang, sedangkan pendaki berpengalaman dapat memilih jalur yang lebih menantang dengan elevasi tinggi atau jarak tempuh lebih panjang. Pada domain pariwisata, sistem rekomendasi berperan membantu pengguna memilih destinasi atau aktivitas yang sesuai dengan preferensi dan konteks perjalanan [13], [14].

Permasalahan yang muncul adalah informasi jalur pendakian sering tersebar dalam berbagai sumber, seperti rekaman GPX, deskripsi komunitas pendaki, ulasan perjalanan, dan informasi basecamp. Sistem pencarian konvensional yang berbasis kata kunci belum cukup efektif untuk memahami maksud pengguna dalam bahasa alami. Misalnya, query “jalur santai untuk pemula” memiliki kedekatan makna dengan “jalur mudah dan landai”, tetapi sistem berbasis kata kunci dapat gagal apabila kata yang digunakan tidak sama persis. Selain itu, sistem rekomendasi yang hanya menampilkan daftar rute sering kali belum mampu menjelaskan alasan rekomendasi secara naratif dan kontekstual.

Penelitian sebelumnya pada sistem rekomendasi umumnya memanfaatkan pendekatan content-based filtering, collaborative filtering, atau pencocokan kata kunci [1]-[5]. Metode berbasis TF-IDF dapat digunakan untuk membandingkan dokumen, tetapi representasi tersebut masih memiliki keterbatasan dalam memahami konteks semantik [11], [12]. Perkembangan model bahasa berbasis transformer memungkinkan teks direpresentasikan sebagai embedding semantik [9], [10]. Sentence-BERT (SBERT) merupakan salah satu model yang dirancang untuk menghasilkan embedding kalimat sehingga cocok digunakan pada pencarian semantik dan rekomendasi berbasis kemiripan makna [6].

Di sisi lain, perkembangan Large Language Model (LLM) memungkinkan sistem menghasilkan jawaban naratif yang lebih mudah dipahami pengguna. Namun, penggunaan LLM secara langsung berisiko menghasilkan halusinasi karena model dapat memberikan jawaban yang tidak bersumber dari data sistem. Retrieval-Augmented Generation (RAG) menjadi pendekatan yang relevan karena menggabungkan tahap retrieval terhadap basis pengetahuan dengan tahap generation menggunakan LLM [7], [8]. Dengan RAG, jawaban chatbot dapat dibatasi pada konteks data yang berhasil diambil sehingga respons menjadi lebih terarah, informatif, dan dapat ditelusuri ke sumber data rute.

Berdasarkan permasalahan tersebut, penelitian ini mengembangkan sistem rekomendasi jalur pendakian gunung dengan mengintegrasikan RAG dan SBERT berbasis data GPX dan deskripsi tekstual. Data GPX digunakan untuk menghasilkan fitur numerik seperti jarak, elevasi, durasi Naismith, dan grade rata-rata. Fitur tersebut kemudian dikonversi menjadi narasi dan digabungkan dengan deskripsi manual untuk membentuk representasi pengetahuan jalur. SBERT digunakan untuk pencarian semantik, sedangkan RAG digunakan untuk menghasilkan respons rekomendasi berbasis konteks.

Rumusan masalah dalam penelitian ini adalah sebagai berikut. Pertama, bagaimana merancang dan mengintegrasikan Retrieval-Augmented Generation dan Sentence-BERT dalam sistem rekomendasi jalur pendakian gunung berbasis data GPX dan deskripsi tekstual. Kedua, bagaimana memanfaatkan data GPX dan deskripsi tekstual untuk membentuk representasi pengetahuan jalur pendakian yang mendukung pencarian semantik dan respons berbasis RAG. Ketiga, bagaimana performa sistem dalam menghasilkan rekomendasi jalur pendakian berdasarkan relevansi hasil pencarian dan kualitas respons yang diberikan.

Tujuan penelitian ini adalah merancang sistem rekomendasi jalur pendakian berbasis integrasi SBERT dan RAG, membangun representasi pengetahuan dari fusi data GPX dan deskripsi tekstual, serta mengevaluasi performa rekomendasi berdasarkan Precision@K dan kualitas respons chatbot. Kontribusi utama penelitian ini adalah penerapan fusi data GPX dan narasi tekstual untuk pencarian semantik, integrasi SBERT sebagai retriever dalam arsitektur RAG, serta pengembangan chatbot pendakian yang menghasilkan respons berbasis konteks data rute.

## II. Metode

### A. Desain Penelitian

Penelitian ini menggunakan pendekatan penelitian terapan dengan membangun prototipe sistem rekomendasi jalur pendakian. Sistem dikembangkan dalam bentuk aplikasi web berbasis Laravel, Python, MySQL, Blade, TailwindCSS, Leaflet, SBERT, dan Google Gemini. Alur penelitian terdiri dari pengumpulan data GPX dan deskripsi jalur, ekstraksi fitur GPX, fusi data numerik dan tekstual, preprocessing teks, pembentukan embedding menggunakan SBERT, pencarian semantik menggunakan cosine similarity, integrasi RAG, dan evaluasi sistem.

Secara umum, arsitektur sistem terdiri dari tiga lapisan. Lapisan presentasi menyediakan antarmuka pencarian, detail jalur, peta interaktif, dan chatbot. Lapisan aplikasi menangani validasi input, pengambilan data rute, penyimpanan riwayat percakapan, dan orkestrasi proses retrieval-generation. Lapisan machine learning memproses data GPX, membangun embedding, menghitung kemiripan, dan menjalankan pipeline RAG.

### B. Dataset Jalur Pendakian

Dataset penelitian terdiri dari 39 jalur pendakian dari 18 gunung di Indonesia. Data jalur berasal dari berkas GPX yang memuat trackpoint pendakian dan dilengkapi dengan deskripsi tekstual berbahasa Indonesia. Setiap rute memiliki atribut nama jalur, nama gunung, provinsi, jarak tempuh, kenaikan elevasi, estimasi durasi, grade rata-rata, elevasi minimum, elevasi maksimum, tingkat kesulitan, jumlah trackpoint, deskripsi manual, dan narasi gabungan.

Tabel I menunjukkan ringkasan dataset yang digunakan.

**TABEL I. Ringkasan Dataset Jalur Pendakian**

| No | Nama Jalur | Gunung | Provinsi | Jumlah Trackpoint |
| --- | --- | --- | --- | --- |
| 1 | Argopuro - Bremi Baderan | Argopuro | Jawa Timur | 492 |
| 2 | Agung - Pura Pasar Agung | Agung | Bali | 156 |
| 3 | Agung - Besakih | Agung | Bali | 1.247 |
| 4 | Anak Krakatau | Krakatau | Lampung | 87 |
| 5 | Arjuno - Lawang | Arjuno | Jawa Timur | 423 |
| 6 | Arjuno - Tambak Watu | Arjuno | Jawa Timur | 389 |
| 7 | Butak - Sirah Kencong | Butak | Jawa Timur | 312 |
| 8 | Butak - Tuyomerto | Butak | Jawa Timur | 287 |
| 9 | Ciremai - Apuy Linggarjati | Ciremai | Jawa Barat | 534 |
| 10 | Ciremai - Linggarjati Palutungan | Ciremai | Jawa Barat | 478 |
| ... | ... | ... | ... | ... |
| 39 | Sumbing Via Butuh Kaliangkrik | Sumbing | Jawa Tengah | 298 |

### C. Ekstraksi Fitur GPX

Data GPX diproses untuk memperoleh atribut numerik yang menggambarkan karakteristik fisik jalur. Ekstraksi dilakukan menggunakan parser GPX untuk membaca titik koordinat, elevasi, dan urutan trackpoint. Atribut yang dihasilkan meliputi jarak tempuh tiga dimensi, kenaikan elevasi positif kumulatif, estimasi waktu pendakian berdasarkan rumus Naismith, grade rata-rata, elevasi minimum, dan elevasi maksimum. Estimasi waktu Naismith digunakan karena menjadi salah satu pendekatan klasik untuk memperkirakan durasi perjalanan berdasarkan jarak dan kenaikan elevasi [15].

Estimasi durasi dihitung menggunakan rumus Naismith sebagai berikut.

\[
T = \frac{D}{5} + \frac{E}{600}
\]

Pada rumus tersebut, T adalah estimasi durasi dalam jam, D adalah jarak dalam kilometer, dan E adalah kenaikan elevasi dalam meter. Grade rata-rata dihitung menggunakan persamaan berikut.

\[
G = \frac{E}{D \times 1000} \times 100
\]

Pada persamaan tersebut, G adalah grade rata-rata dalam persen. Ringkasan atribut hasil ekstraksi GPX ditunjukkan pada Tabel II.

**TABEL II. Atribut Hasil Ekstraksi Data GPX**

| Atribut | Rumus/Metode | Satuan | Rentang Nilai |
| --- | --- | --- | --- |
| Jarak tempuh | Panjang lintasan GPX 3D | Kilometer | 3,85 - 114,51 |
| Kenaikan elevasi | Jumlah selisih elevasi positif | Meter | 0 - 8.120 |
| Durasi Naismith | D/5 + E/600 | Jam | 0,55 - 29,26 |
| Grade rata-rata | E/(D x 1000) x 100 | Persen | 0,00 - 21,89 |
| Elevasi minimum | Nilai elevasi terendah | mdpl | 773 - 2.200 |
| Elevasi maksimum | Nilai elevasi tertinggi | mdpl | 2.350 - 3.371 |

### D. Fusi Data GPX dan Deskripsi Tekstual

Fusi data dilakukan untuk membentuk representasi pengetahuan jalur pendakian. Atribut numerik dari GPX dikonversi menjadi narasi Bahasa Indonesia menggunakan template kalimat. Narasi otomatis tersebut digabungkan dengan deskripsi manual sehingga setiap jalur memiliki teks yang mencakup aspek kuantitatif dan kualitatif.

Contoh konversi atribut numerik menjadi narasi ditunjukkan pada Tabel III.

**TABEL III. Contoh Konversi Data Numerik ke Narasi**

| Atribut | Nilai | Narasi Otomatis |
| --- | --- | --- |
| distance_km | 11,10 | Jalur pendakian dengan jarak panjang sekitar 11,10 kilometer |
| elevation_gain_m | 1.910 | Total kenaikan elevasi 1.910 meter dengan ketinggian dari 1.600 m hingga 3.142 m |
| naismith_duration_hour | 5,40 | Estimasi waktu tempuh lama selama 5,40 jam menggunakan rumus Naismith |
| average_grade_pct | 17,21 | Karakteristik jalur sangat curam dengan grade rata-rata 17,21% |
| difficulty | Sulit | Tingkat kesulitan sulit dan cocok untuk pendaki berpengalaman |

Sebagai contoh, deskripsi manual “jalur dengan sabana luas dan pemandangan sunrise spektakuler” digabungkan dengan narasi numerik mengenai jarak, elevasi, durasi, grade, dan tingkat kesulitan. Hasil fusi ini memungkinkan model memahami hubungan antara preferensi pengguna dan karakteristik aktual jalur.

### E. Preprocessing Teks

Preprocessing teks dilakukan sebelum proses embedding. Tahapan preprocessing meliputi data cleaning, case folding, dan stopword removal selektif. Data cleaning menghapus URL, karakter khusus, dan noise yang tidak relevan. Case folding menyeragamkan huruf menjadi huruf kecil. Stopword removal selektif menghapus kata umum, tetapi tetap mempertahankan kata negasi dan kata sifat penting seperti tidak, bukan, mudah, sulit, curam, landai, pemula, santai, dan menantang.

Pendekatan selektif digunakan karena kata negasi dan kata sifat memiliki pengaruh penting pada domain pendakian. Contohnya, “jalur tidak sulit” memiliki makna berbeda dari “jalur sulit”. Penghapusan kata negasi dapat merusak makna query maupun deskripsi jalur.

### F. Pencarian Semantik Menggunakan SBERT

Model SBERT yang digunakan adalah `sentence-transformers/paraphrase-multilingual-MiniLM-L12-v2`. Model ini menghasilkan embedding berdimensi 384 dan mendukung bahasa Indonesia. Setiap narasi jalur diubah menjadi vektor embedding. Query pengguna juga diproses menjadi embedding dengan model yang sama. Kemiripan antara query dan setiap jalur dihitung menggunakan cosine similarity, yang umum digunakan dalam temu kembali informasi untuk membandingkan kedekatan vektor dokumen dan query [6], [12].

Cosine similarity dihitung menggunakan persamaan berikut.

\[
Sim(Q,D)=\frac{\sum_{i=1}^{n} Q_iD_i}{\sqrt{\sum_{i=1}^{n} Q_i^2}\sqrt{\sum_{i=1}^{n} D_i^2}}
\]

Q merupakan vektor query, D merupakan vektor dokumen jalur, dan n adalah jumlah dimensi embedding. Hasil similarity digunakan untuk mengurutkan jalur dari yang paling relevan hingga yang paling tidak relevan.

### G. Integrasi Retrieval-Augmented Generation

RAG diintegrasikan untuk menghasilkan respons chatbot berbasis data jalur. Pipeline RAG terdiri dari tiga tahap, yaitu retrieve, augment, dan generate. Pada tahap retrieve, query pengguna diproses menggunakan SBERT dan dibandingkan dengan embedding seluruh jalur. Sistem mengambil top-k jalur paling relevan berdasarkan cosine similarity. Pada implementasi ini, chatbot menggunakan top-k sebesar 3 untuk membentuk konteks respons.

Pada tahap augment, data rute hasil retrieval disusun menjadi prompt yang berisi nama jalur, deskripsi naratif, jarak, kenaikan elevasi, estimasi durasi, grade rata-rata, basecamp, alamat, biaya masuk, fasilitas, musim terbaik, tips, dan skor kemiripan. Prompt juga menyertakan riwayat percakapan terakhir agar sistem dapat mempertahankan konteks multi-turn.

Pada tahap generate, prompt yang telah diperkaya dikirim ke Google Gemini untuk menghasilkan jawaban dalam Bahasa Indonesia. Sistem memberikan instruksi agar model hanya menjawab berdasarkan retrieved context, menyatakan secara jujur jika informasi tidak tersedia, menyertakan data numerik jika relevan, menjelaskan alasan rekomendasi, dan memprioritaskan keselamatan pendaki. Dengan mekanisme ini, RAG digunakan untuk mengurangi risiko halusinasi dan meningkatkan keterlacakan jawaban terhadap data rute.

### H. Evaluasi Sistem

Evaluasi dilakukan pada dua aspek, yaitu relevansi rekomendasi dan kualitas respons RAG. Relevansi rekomendasi dievaluasi menggunakan Precision@K. Metrik ini mengukur proporsi item relevan pada K hasil teratas.

\[
Precision@K=\frac{Jumlah\ item\ relevan\ dalam\ Top-K}{K}
\]

Pengujian dilakukan pada empat skenario query bahasa alami, yaitu “jalur mudah untuk pemula”, “trek menantang elevasi tinggi”, “pendakian singkat 2-3 jam”, dan “gunung dengan sabana dan sunrise”. Hasil SBERT dibandingkan dengan TF-IDF sebagai baseline. Kualitas respons RAG dievaluasi secara kualitatif berdasarkan empat kriteria, yaitu kesesuaian dengan konteks retrieval, kelengkapan informasi, kejelasan alasan rekomendasi, dan kepatuhan untuk tidak mengarang data di luar konteks.

## III. Hasil dan Pembahasan

### A. Hasil Representasi Pengetahuan Jalur

Pengolahan data menghasilkan 39 dokumen naratif jalur pendakian. Setiap dokumen memadukan deskripsi manual dengan hasil ekstraksi GPX. Dengan pendekatan ini, sistem tidak hanya memiliki data numerik, tetapi juga representasi teks yang dapat diproses oleh model bahasa. Hasil preprocessing menunjukkan penurunan jumlah kata dari 2.847 menjadi 1.923 kata atau berkurang 32,5%. Kata unik berkurang dari 312 menjadi 198 kata atau berkurang 36,5%. Pengurangan ini membantu menghilangkan noise tanpa menghapus kata penting domain pendakian.

Tabel IV menunjukkan statistik preprocessing dataset.

**TABEL IV. Statistik Preprocessing Dataset**

| Metrik | Sebelum Preprocessing | Sesudah Preprocessing | Perubahan |
| --- | --- | --- | --- |
| Total kata | 2.847 | 1.923 | -32,5% |
| Kata unik | 312 | 198 | -36,5% |
| Rata-rata panjang dokumen | 73 kata | 49 kata | -32,9% |

Hasil ini menjawab rumusan masalah kedua, yaitu pemanfaatan data GPX dan deskripsi tekstual untuk membentuk representasi pengetahuan. Data GPX menyediakan atribut objektif seperti jarak, elevasi, dan durasi, sedangkan deskripsi tekstual menyediakan konteks kualitatif seperti sabana, sunrise, vegetasi, dan tingkat kenyamanan jalur. Kombinasi keduanya membuat representasi jalur lebih lengkap untuk mendukung pencarian semantik dan respons RAG.

### B. Hasil Embedding SBERT

Seluruh narasi jalur berhasil dikonversi menjadi embedding SBERT berdimensi 384. Waktu encoding total untuk 39 dokumen adalah 0,92 detik, dengan rata-rata 23,59 milidetik per dokumen. Rentang nilai embedding berada pada -1 hingga 1 dengan norma rata-rata 1,0247.

**TABEL V. Statistik Embedding SBERT**

| Metrik | Nilai |
| --- | --- |
| Jumlah dokumen | 39 |
| Dimensi vektor | 384 |
| Waktu encoding total | 0,92 detik |
| Rata-rata waktu per dokumen | 23,59 ms |
| Rentang nilai vektor | -1,0 hingga 1,0 |
| Norma rata-rata | 1,0247 |

Penggunaan SBERT memungkinkan sistem menangkap kedekatan makna antarfrasa. Misalnya, frasa “jalur santai” dapat memiliki kedekatan makna dengan “jalur mudah untuk pemula”. Hal ini sulit dicapai oleh metode berbasis pencocokan kata literal seperti TF-IDF.

### C. Hasil Pencarian Semantik

Pengujian pencarian semantik dilakukan menggunakan empat skenario query. Tabel VI menunjukkan hasil evaluasi Precision@3 dan Precision@5.

**TABEL VI. Hasil Evaluasi Precision@K**

| Skenario | Query | Precision@3 | Precision@5 |
| --- | --- | --- | --- |
| 1 | jalur mudah untuk pemula | 0,67 | 0,40 |
| 2 | trek menantang elevasi tinggi | 1,00 | 1,00 |
| 3 | pendakian singkat 2-3 jam | 0,00 | 0,00 |
| 4 | gunung dengan sabana dan sunrise | 1,00 | 1,00 |
| Rata-rata | - | 0,67 | 0,60 |

Pada query “jalur mudah untuk pemula”, sistem berhasil menempatkan dua jalur dengan tingkat kesulitan mudah pada posisi teratas, yaitu Agung - Pura Pasar Agung dan Ijen - Sempol. Namun, Precision@5 hanya mencapai 0,40 karena tiga hasil berikutnya memiliki label sulit. Pada query “trek menantang elevasi tinggi”, seluruh top-5 relevan karena sistem mengambil jalur dengan elevasi besar dan tingkat kesulitan sedang hingga sulit, seperti Merbabu Via Thekelan, Semeru, Merbabu Via Selo, Merbabu Via Suwanting, dan Ciremai - Linggarjati.

Pada query “pendakian singkat 2-3 jam”, sistem belum berhasil menghasilkan rekomendasi relevan. Hasil top-5 memiliki durasi di atas batas yang diminta. Kegagalan ini menunjukkan bahwa SBERT belum cukup kuat untuk menangani constraint numerik eksplisit apabila tidak dipadukan dengan filter numerik. Pada query “gunung dengan sabana dan sunrise”, seluruh top-5 relevan karena sistem berhasil memahami konteks kualitatif seperti sabana, sunrise, kawah, dan pemandangan.

### D. Perbandingan SBERT dan TF-IDF

Untuk melihat efektivitas SBERT, hasil rekomendasi dibandingkan dengan TF-IDF. Tabel VII menunjukkan perbandingan Precision@5.

**TABEL VII. Perbandingan Precision@5 SBERT dan TF-IDF**

| Skenario | SBERT | TF-IDF | Selisih |
| --- | --- | --- | --- |
| 1 | 0,40 | 0,20 | +0,20 |
| 2 | 1,00 | 0,60 | +0,40 |
| 3 | 0,00 | 0,00 | 0,00 |
| 4 | 1,00 | 0,20 | +0,80 |
| Rata-rata | 0,60 | 0,25 | +0,35 |

SBERT memperoleh rata-rata Precision@5 sebesar 0,60, sedangkan TF-IDF memperoleh 0,25. Peningkatan sebesar 0,35 atau sekitar 140% menunjukkan bahwa representasi semantik lebih efektif dibandingkan pencocokan kata literal. Keunggulan paling besar terjadi pada query “gunung dengan sabana dan sunrise”, karena SBERT mampu menangkap hubungan semantik antara fitur pemandangan, pengalaman visual, dan deskripsi jalur.

Hasil ini menjawab rumusan masalah ketiga pada aspek relevansi hasil pencarian. Sistem mampu menghasilkan rekomendasi yang relevan pada query konseptual dan deskriptif. Namun, sistem masih membutuhkan pengembangan tambahan untuk query yang mengandung batas numerik spesifik, seperti durasi 2-3 jam.

### E. Hasil Integrasi RAG dan Chatbot

Integrasi RAG diwujudkan melalui modul chatbot RuteStrip AI. Pada saat pengguna mengirim pertanyaan, sistem menyimpan pesan pengguna, mengambil seluruh jalur yang memiliki embedding, menjalankan retrieval menggunakan SBERT dan cosine similarity, membentuk augmented prompt dari top-k rute, lalu menghasilkan jawaban menggunakan Google Gemini. Hasil respons dan daftar rute yang diretrieval disimpan ke dalam riwayat percakapan.

Tabel VIII menunjukkan pemetaan komponen RAG pada sistem.

**TABEL VIII. Komponen RAG pada Sistem RuteStrip**

| Tahap RAG | Implementasi | Keluaran |
| --- | --- | --- |
| Retrieve | SBERT embedding dan cosine similarity | Top-k rute paling relevan |
| Augment | Penyusunan konteks rute ke dalam prompt | Prompt berisi data rute, skor, dan riwayat chat |
| Generate | Google Gemini | Jawaban naratif berbasis konteks |
| Persistence | Database chat message | Riwayat percakapan dan retrieved routes |

Respons RAG dirancang agar tidak hanya menyebut nama jalur, tetapi juga menjelaskan alasan rekomendasi. Informasi yang digunakan dalam prompt meliputi deskripsi jalur, jarak, elevasi, durasi Naismith, grade rata-rata, basecamp, fasilitas, musim terbaik, tips, dan skor kemiripan. Dengan demikian, chatbot dapat menjawab pertanyaan seperti “rekomendasikan jalur untuk pemula”, “jalur mana yang paling menantang”, atau “berapa estimasi waktu Merbabu via Selo” secara lebih informatif.

Evaluasi kualitas respons dilakukan berdasarkan empat kriteria. Tabel IX menunjukkan hasil evaluasi kualitatif pada skenario utama.

**TABEL IX. Evaluasi Kualitatif Respons RAG**

| Query | Konteks Retrieval | Kelengkapan Informasi | Alasan Rekomendasi | Pembatasan Halusinasi |
| --- | --- | --- | --- | --- |
| Jalur mudah untuk pemula | Sesuai dengan rute mudah pada top hasil | Memuat nama jalur, jarak, dan tingkat kesulitan | Menjelaskan kecocokan untuk pemula | Jawaban dibatasi pada data jalur |
| Trek menantang elevasi tinggi | Sesuai dengan rute elevasi tinggi | Memuat elevasi, durasi, dan grade | Menjelaskan tantangan berdasarkan elevasi | Tidak menambahkan rute di luar data |
| Pendakian singkat 2-3 jam | Retrieval kurang sesuai | Menampilkan data durasi yang tersedia | Menunjukkan keterbatasan data | Sistem diarahkan menyatakan data tidak tersedia |
| Gunung dengan sabana dan sunrise | Sesuai dengan rute berpemandangan | Memuat fitur jalur dan skor kemiripan | Menjelaskan kecocokan pemandangan | Jawaban berbasis retrieved context |

Hasil tersebut menunjukkan bahwa RAG menjawab rumusan masalah pertama dan ketiga pada aspek integrasi dan kualitas respons. Integrasi RAG memungkinkan sistem menghasilkan jawaban yang lebih natural dibandingkan daftar rekomendasi biasa. Selain itu, prompt sistem secara eksplisit menginstruksikan model untuk tidak mengarang informasi di luar konteks data, sehingga respons lebih terikat pada basis pengetahuan.

### F. Implementasi Sistem Web

Sistem diimplementasikan sebagai aplikasi web. Pengguna dapat mengunggah file GPX, melihat detail jalur, melakukan pencarian semantik, menyimpan favorit, melihat peta interaktif, dan menggunakan chatbot RAG. Modul upload GPX mendukung pemrosesan tunggal maupun batch. Setiap file GPX divalidasi, diproses oleh Python, dikonversi menjadi fitur, dibentuk narasinya, diubah menjadi embedding, dan disimpan ke database.

Pada modul pencarian, Laravel mengambil data rute yang memiliki embedding, Python menghasilkan embedding query, kemudian cosine similarity dihitung untuk mengurutkan rekomendasi. Pada modul chatbot, Laravel memanggil script Python RAG dengan parameter query, data jalur, API key Gemini, model generatif, top-k, dan riwayat percakapan. Output dikembalikan dalam format JSON berisi status, respons, retrieved routes, timing, dan metadata model.

Tabel X menunjukkan pengujian fungsional sistem.

**TABEL X. Pengujian Fungsional Sistem**

| No | Skenario | Input | Output yang Diharapkan | Status |
| --- | --- | --- | --- | --- |
| 1 | Upload GPX tunggal | 1 file GPX dan deskripsi | Jalur tersimpan dengan embedding | Berhasil |
| 2 | Batch upload GPX | Beberapa file GPX | Semua jalur terproses | Berhasil |
| 3 | Pencarian pemula | Query jalur mudah | Rekomendasi dengan skor similarity | Berhasil |
| 4 | Pencarian pemandangan | Query sabana dan sunrise | Rute relevan muncul pada top hasil | Berhasil |
| 5 | Detail jalur | Klik hasil pencarian | Detail, statistik, dan peta tampil | Berhasil |
| 6 | RAG chatbot | Pertanyaan bahasa alami | Jawaban berbasis retrieved context | Berhasil |
| 7 | Riwayat chat | Multi-turn conversation | Pesan tersimpan per session | Berhasil |

### G. Analisis Pembahasan

Penelitian ini menunjukkan bahwa fusi data GPX dan deskripsi tekstual dapat membentuk basis pengetahuan yang lebih kaya dibandingkan penggunaan atribut tunggal. Data GPX memberikan objektivitas terhadap karakteristik jalur, sedangkan deskripsi tekstual memberikan konteks pengalaman pendakian. Dengan SBERT, representasi gabungan tersebut dapat digunakan untuk memahami query bahasa alami secara semantik.

Integrasi RAG menjadi pengembangan penting karena sistem tidak berhenti pada daftar hasil rekomendasi. Pengguna dapat melakukan percakapan untuk meminta rekomendasi, alasan pemilihan jalur, estimasi waktu, informasi basecamp, atau tips pendakian. Tahap retrieval memastikan respons generatif bersumber dari data jalur yang relevan, sedangkan tahap generation membuat informasi lebih mudah dipahami.

Meskipun demikian, masih terdapat keterbatasan. Pertama, dataset hanya terdiri dari 39 jalur sehingga cakupan gunung Indonesia belum menyeluruh. Kedua, query numerik spesifik seperti “2-3 jam” belum ditangani dengan baik oleh SBERT maupun TF-IDF. Ketiga, evaluasi kualitas respons RAG masih bersifat kualitatif dan perlu diperkuat dengan penilaian pengguna atau annotator independen. Keempat, kualitas respons generatif bergantung pada kelengkapan data rute dan konfigurasi model Gemini.

Untuk penelitian lanjutan, sistem dapat dikembangkan dengan filter numerik eksplisit, seperti rentang durasi, rentang elevasi, dan tingkat kesulitan. Selain itu, evaluasi RAG dapat diperluas menggunakan metrik groundedness, answer relevance, context precision, user satisfaction, dan inter-rater agreement. Dataset juga dapat diperluas ke jalur pendakian di Sumatera, Kalimantan, Sulawesi, Nusa Tenggara, Maluku, dan Papua.

## IV. Kesimpulan

Penelitian ini berhasil merancang dan mengintegrasikan Retrieval-Augmented Generation dan Sentence-BERT dalam sistem rekomendasi jalur pendakian gunung berbasis data GPX dan deskripsi tekstual. Integrasi dilakukan melalui pipeline retrieve, augment, dan generate, dengan SBERT sebagai retriever semantik dan Google Gemini sebagai generator respons. Sistem mampu mengambil top-k jalur relevan berdasarkan cosine similarity, membentuk konteks dari data rute, dan menghasilkan jawaban chatbot yang informatif serta berbasis data.

Data GPX dan deskripsi tekstual berhasil dimanfaatkan untuk membentuk representasi pengetahuan jalur pendakian. Atribut numerik seperti jarak, elevasi, durasi Naismith, grade rata-rata, dan tingkat kesulitan dikonversi menjadi narasi dan digabungkan dengan deskripsi manual. Representasi tersebut menghasilkan embedding SBERT berdimensi 384 yang mendukung pencarian semantik dan respons RAG.

Berdasarkan evaluasi, sistem rekomendasi berbasis SBERT memperoleh rata-rata Precision@3 sebesar 0,67 dan Precision@5 sebesar 0,60. Hasil ini lebih baik dibandingkan TF-IDF yang memperoleh Precision@5 sebesar 0,25. Modul RAG meningkatkan kualitas penyajian rekomendasi karena mampu memberikan jawaban naratif yang memuat alasan pemilihan jalur, data numerik, informasi basecamp, dan saran keselamatan berdasarkan konteks hasil retrieval. Keterbatasan utama sistem terdapat pada query numerik spesifik, sehingga penelitian lanjutan perlu menambahkan filter numerik dan evaluasi respons RAG yang lebih terukur.

## Ucapan Terima Kasih

Bagian ini dapat dihapus jika penelitian tidak mendapat dukungan pendanaan. Penulis mengucapkan terima kasih kepada Program Studi Teknik Informatika, Nama Universitas/Institusi, serta pihak yang membantu pengumpulan dan validasi data jalur pendakian.

## Daftar Pustaka

[1] G. Adomavicius and A. Tuzhilin, “Toward the next generation of recommender systems: A survey of the state-of-the-art and possible extensions,” IEEE Transactions on Knowledge and Data Engineering, vol. 17, no. 6, pp. 734-749, 2005.

[2] J. Bobadilla, F. Ortega, A. Hernando, and A. Gutierrez, “Recommender systems survey,” Knowledge-Based Systems, vol. 46, pp. 109-132, 2013.

[3] S. Zhang, L. Yao, A. Sun, and Y. Tay, “Deep learning based recommender system: A survey and new perspectives,” ACM Computing Surveys, vol. 52, no. 1, pp. 1-38, 2019.

[4] D. Jannach, A. Manzoor, W. Cai, and L. Chen, “A survey on conversational recommender systems,” ACM Computing Surveys, vol. 54, no. 5, pp. 1-36, 2021.

[5] J. Beel, B. Gipp, S. Langer, and C. Breitinger, “Research-paper recommender systems: A literature survey,” International Journal on Digital Libraries, vol. 17, no. 4, pp. 305-338, 2016.

[6] N. Reimers and I. Gurevych, “Sentence-BERT: Sentence embeddings using Siamese BERT-networks,” in Proceedings of the 2019 Conference on Empirical Methods in Natural Language Processing, 2019, pp. 3982-3992.

[7] P. Lewis, E. Perez, A. Piktus, F. Petroni, V. Karpukhin, N. Goyal, H. Kuttler, M. Lewis, W. Yih, T. Rocktaschel, S. Riedel, and D. Kiela, “Retrieval-augmented generation for knowledge-intensive NLP tasks,” in Advances in Neural Information Processing Systems, vol. 33, pp. 9459-9474, 2020.

[8] Y. Gao, Y. Xiong, X. Gao, K. Jia, J. Pan, Y. Bi, Y. Dai, J. Sun, H. Wang, and H. Wang, “Retrieval-augmented generation for large language models: A survey,” arXiv preprint arXiv:2312.10997, 2023.

[9] A. Vaswani, N. Shazeer, N. Parmar, J. Uszkoreit, L. Jones, A. N. Gomez, L. Kaiser, and I. Polosukhin, “Attention is all you need,” in Advances in Neural Information Processing Systems, vol. 30, 2017.

[10] J. Devlin, M. W. Chang, K. Lee, and K. Toutanova, “BERT: Pre-training of deep bidirectional transformers for language understanding,” in Proceedings of NAACL-HLT, 2019, pp. 4171-4186.

[11] G. Salton and C. Buckley, “Term-weighting approaches in automatic text retrieval,” Information Processing & Management, vol. 24, no. 5, pp. 513-523, 1988.

[12] C. D. Manning, P. Raghavan, and H. Schutze, Introduction to Information Retrieval. Cambridge: Cambridge University Press, 2008.

[13] D. R. Fesenmaier, K. W. Wober, and H. Werthner, Destination Recommendation Systems: Behavioural Foundations and Applications. Wallingford: CABI, 2006.

[14] F. Ricci, L. Rokach, and B. Shapira, “Recommender systems: Introduction and challenges,” in Recommender Systems Handbook, 2nd ed. Boston: Springer, 2015, pp. 1-34.

[15] W. W. Naismith, “Cruach Ardran, Stobinian, and Ben More,” Scottish Mountaineering Club Journal, vol. 2, pp. 136, 1892.
