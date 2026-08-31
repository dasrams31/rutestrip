from __future__ import annotations

import html
import zipfile
from pathlib import Path


OUT = Path("presentasi/RuteStrip_Presentasi_SINTA3_GEMASI2026.pptx")
SLIDE_W = 12192000
SLIDE_H = 6858000

COLORS = {
    "bg": "0C1810",
    "card": "142218",
    "surface": "1A2E22",
    "primary": "22C55E",
    "primary_dark": "16A34A",
    "primary_light": "86EFAC",
    "text": "E8F5EC",
    "muted": "9CB8A6",
    "yellow": "FBBF24",
    "blue": "60A5FA",
    "red": "F87171",
}


def esc(text: str) -> str:
    return html.escape(text, quote=True)


def solid_fill(color: str) -> str:
    return f'<a:solidFill><a:srgbClr val="{color}"/></a:solidFill>'


def no_line() -> str:
    return '<a:ln><a:noFill/></a:ln>'


def shape_xml(
    shape_id: int,
    x: int,
    y: int,
    cx: int,
    cy: int,
    fill: str,
    geom: str = "rect",
    line: str | None = None,
    radius: bool = False,
) -> str:
    geom_name = "roundRect" if radius else geom
    line_xml = line if line is not None else no_line()
    return f"""
      <p:sp>
        <p:nvSpPr><p:cNvPr id="{shape_id}" name="Shape {shape_id}"/><p:cNvSpPr/><p:nvPr/></p:nvSpPr>
        <p:spPr>
          <a:xfrm><a:off x="{x}" y="{y}"/><a:ext cx="{cx}" cy="{cy}"/></a:xfrm>
          <a:prstGeom prst="{geom_name}"><a:avLst/></a:prstGeom>
          {solid_fill(fill)}
          {line_xml}
        </p:spPr>
        <p:txBody><a:bodyPr/><a:lstStyle/><a:p/></p:txBody>
      </p:sp>"""


def line_xml(color: str, width: int = 12700) -> str:
    return f'<a:ln w="{width}"><a:solidFill><a:srgbClr val="{color}"/></a:solidFill></a:ln>'


def text_box(
    shape_id: int,
    x: int,
    y: int,
    cx: int,
    cy: int,
    lines: list[str],
    size: int = 2400,
    color: str = COLORS["text"],
    bold: bool = False,
    align: str = "l",
    font: str = "Segoe UI",
) -> str:
    paragraphs = []
    for line in lines:
        b = '<a:b/>' if bold else ''
        paragraphs.append(
            f'<a:p><a:pPr algn="{align}"/><a:r><a:rPr lang="id-ID" sz="{size}" dirty="0">{b}'
            f'<a:solidFill><a:srgbClr val="{color}"/></a:solidFill><a:latin typeface="{font}"/></a:rPr>'
            f'<a:t>{esc(line)}</a:t></a:r><a:endParaRPr lang="id-ID" sz="{size}"/></a:p>'
        )
    return f"""
      <p:sp>
        <p:nvSpPr><p:cNvPr id="{shape_id}" name="Text {shape_id}"/><p:cNvSpPr txBox="1"/><p:nvPr/></p:nvSpPr>
        <p:spPr><a:xfrm><a:off x="{x}" y="{y}"/><a:ext cx="{cx}" cy="{cy}"/></a:xfrm><a:prstGeom prst="rect"><a:avLst/></a:prstGeom><a:noFill/>{no_line()}</p:spPr>
        <p:txBody><a:bodyPr wrap="square" lIns="0" tIns="0" rIns="0" bIns="0"/><a:lstStyle/>{''.join(paragraphs)}</p:txBody>
      </p:sp>"""


def bullet_box(shape_id: int, x: int, y: int, cx: int, cy: int, bullets: list[str], size: int = 2300) -> str:
    paragraphs = []
    for bullet in bullets:
        paragraphs.append(
            f'<a:p><a:pPr marL="342900" indent="-228600"><a:buChar char="•"/></a:pPr>'
            f'<a:r><a:rPr lang="id-ID" sz="{size}" dirty="0"><a:solidFill><a:srgbClr val="{COLORS["muted"]}"/></a:solidFill><a:latin typeface="Segoe UI"/></a:rPr>'
            f'<a:t>{esc(bullet)}</a:t></a:r><a:endParaRPr lang="id-ID" sz="{size}"/></a:p>'
        )
    return f"""
      <p:sp>
        <p:nvSpPr><p:cNvPr id="{shape_id}" name="Bullets {shape_id}"/><p:cNvSpPr txBox="1"/><p:nvPr/></p:nvSpPr>
        <p:spPr><a:xfrm><a:off x="{x}" y="{y}"/><a:ext cx="{cx}" cy="{cy}"/></a:xfrm><a:prstGeom prst="rect"><a:avLst/></a:prstGeom><a:noFill/>{no_line()}</p:spPr>
        <p:txBody><a:bodyPr wrap="square" lIns="0" tIns="0" rIns="0" bIns="0"/><a:lstStyle/>{''.join(paragraphs)}</p:txBody>
      </p:sp>"""


def card(shape_id: int, x: int, y: int, cx: int, cy: int, title: str, body: str, accent: str = COLORS["primary"]) -> str:
    return "".join([
        shape_xml(shape_id, x + 70000, y + 90000, cx, cy, "07110C", radius=True),
        shape_xml(shape_id + 1, x, y, cx, cy, COLORS["card"], line=line_xml("284E37", 10000), radius=True),
        shape_xml(shape_id + 2, x, y, cx, 76000, accent),
        shape_xml(shape_id + 3, x + 210000, y + 230000, 380000, 380000, accent, "ellipse"),
        text_box(shape_id + 4, x + 700000, y + 210000, cx - 920000, 420000, [title], 2250, accent, True),
        text_box(shape_id + 5, x + 250000, y + 820000, cx - 500000, cy - 960000, [body], 1720, COLORS["muted"]),
    ])


def pill(shape_id: int, x: int, y: int, cx: int, text: str, color: str = COLORS["primary"]) -> str:
    return "".join([
        shape_xml(shape_id, x, y, cx, 360000, "102016", line=line_xml(color, 9000), radius=True),
        text_box(shape_id + 1, x + 170000, y + 85000, cx - 340000, 210000, [text.upper()], 1150, color, True, "ctr"),
    ])


def stat_card(shape_id: int, x: int, y: int, cx: int, number: str, label: str, color: str) -> str:
    return "".join([
        shape_xml(shape_id, x + 50000, y + 70000, cx, 1180000, "07110C", radius=True),
        shape_xml(shape_id + 1, x, y, cx, 1180000, COLORS["card"], line=line_xml("284E37", 9000), radius=True),
        text_box(shape_id + 2, x + 170000, y + 210000, cx - 340000, 390000, [number], 3100, color, True, "ctr"),
        text_box(shape_id + 3, x + 170000, y + 720000, cx - 340000, 260000, [label], 1350, COLORS["muted"], False, "ctr"),
    ])


def background() -> str:
    return "".join([
        shape_xml(2, 0, 0, SLIDE_W, SLIDE_H, COLORS["bg"]),
        shape_xml(3, 0, 0, SLIDE_W, 520000, "102016"),
        shape_xml(4, 680000, 640000, 2100000, 62000, COLORS["primary"]),
        shape_xml(5, 3000000, 640000, 760000, 62000, "86EFAC"),
        shape_xml(6, 10450000, 360000, 1040000, 1040000, "102016", "ellipse", line=line_xml("284E37", 9000)),
        shape_xml(7, 10800000, 700000, 350000, 350000, COLORS["primary"], "ellipse"),
        shape_xml(8, -550000, 5150000, 5200000, 2300000, "07110C", "triangle"),
        shape_xml(9, 2300000, 4720000, 6400000, 2700000, "102016", "triangle"),
        shape_xml(10, 7350000, 5040000, 6000000, 2400000, "183222", "triangle"),
        shape_xml(11, 0, 6350000, SLIDE_W, 510000, "07110C"),
    ])


def title(slide_title: str, kicker: str | None = None) -> str:
    items = []
    if kicker:
        items.append(pill(20, 720000, 560000, 1650000, kicker, COLORS["primary_light"]))
    items.append(text_box(23, 720000, 1010000, 10500000, 720000, [slide_title], 3250, COLORS["text"], True))
    items.append(shape_xml(24, 720000, 1760000, 1350000, 55000, COLORS["primary"]))
    return "".join(items)


def footer(slide_no: int) -> str:
    return "".join([
        text_box(900, 720000, 6400000, 3200000, 220000, ["RuteStrip | SBERT Route Recommendation"], 1050, "5F7F6C"),
        text_box(901, 10450000, 6400000, 900000, 220000, [f"{slide_no:02d}/{TOTAL_SLIDES:02d}"], 1050, COLORS["primary_light"], True, "r"),
    ])


slides: list[str] = []
TOTAL_SLIDES = 12


def add_slide(content: str) -> None:
    slide_no = len(slides) + 1
    slides.append(f"""<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<p:sld xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main">
  <p:cSld><p:spTree><p:nvGrpSpPr><p:cNvPr id="1" name=""/><p:cNvGrpSpPr/><p:nvPr/></p:nvGrpSpPr><p:grpSpPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="0" cy="0"/><a:chOff x="0" y="0"/><a:chExt cx="0" cy="0"/></a:xfrm></p:grpSpPr>{background()}{content}{footer(slide_no)}</p:spTree></p:cSld><p:clrMapOvr><a:masterClrMapping/></p:clrMapOvr>
</p:sld>""")


add_slide("".join([
    text_box(30, 720000, 560000, 4000000, 300000, ["PORTOFOLIO AKADEMIK & KOMPETISI"], 1300, COLORS["primary_light"], True),
    text_box(31, 720000, 1050000, 9000000, 1500000, ["RuteStrip"], 6200, COLORS["primary"], True),
    text_box(32, 720000, 2350000, 7800000, 800000, ["Sistem Rekomendasi Rute Pendakian Gunung menggunakan Content-Based Filtering berbasis SBERT dan Cosine Similarity"], 2300, COLORS["text"], False),
    card(40, 720000, 4100000, 4000000, 1450000, "Rama Danadipa Putra Wijaya", "NIM 23.12.2956\nUniversitas Amikom Yogyakarta", COLORS["primary"]),
    card(50, 5100000, 4100000, 5000000, 1450000, "Rekam Jejak", "Juara 2 GEMASI 2026 kategori Data Science\nPaper SINTA 3 SISTEMASI Vol. 15 No. 4, 2026", COLORS["yellow"]),
]))

add_slide(title("Identitas & Riwayat Publikasi", "profil") + "".join([
    card(40, 720000, 2050000, 3300000, 1650000, "Identitas", "Nama: Rama Danadipa Putra Wijaya\nNIM: 23.12.2956\nProgram Studi Sistem Informasi", COLORS["primary"]),
    card(50, 4440000, 2050000, 3300000, 1650000, "Kompetisi", "GEMASI 2026\nKategori: Data Science\nCapaian: Juara 2", COLORS["yellow"]),
    card(60, 8160000, 2050000, 3300000, 1650000, "Publikasi", "SISTEMASI, SINTA 3\nVol. 15 No. 4, 2026\nDOI: 10.32520/stmsi.v15i4.6087", COLORS["blue"]),
    text_box(70, 900000, 4620000, 10400000, 650000, ["RuteStrip diposisikan sebagai karya yang punya dua keluaran: produk sistem rekomendasi untuk pendaki dan kontribusi akademik yang sudah dipublikasikan."], 2200, COLORS["muted"], False, "ctr"),
]))

add_slide(title("Data Artikel SISTEMASI", "artikel") + "".join([
    bullet_box(40, 900000, 2050000, 6100000, 3000000, [
        "Judul: Sistem Rekomendasi Rute Pendakian Gunung menggunakan Content-Based Filtering berbasis SBERT dan Cosine Similarity",
        "Jurnal: Sistemasi: Jurnal Sistem Informasi",
        "Volume 15, Nomor 4, 2026, halaman 1241-1256",
        "Received: 18 Januari 2026; accepted: 8 April 2026",
        "DOI: https://doi.org/10.32520/stmsi.v15i4.6087",
    ], 2100),
    card(60, 7900000, 2200000, 3000000, 1700000, "Kata Kunci", "content-based filtering\ncosine similarity\npendakian gunung\nsistem rekomendasi\nSBERT", COLORS["primary"]),
    card(70, 7900000, 4250000, 3000000, 1200000, "Dataset Paper", "39 jalur pendakian di Pulau Jawa dengan data GPX dan deskripsi literatur", COLORS["yellow"]),
]))

add_slide(title("Latar Belakang Pengembangan", "problem") + "".join([
    card(40, 850000, 2050000, 3000000, 2300000, "Informasi Tersebar", "Pendaki masih mengandalkan forum, media sosial, dan rekomendasi informal yang sulit dibandingkan secara objektif.", COLORS["red"]),
    card(50, 4440000, 2050000, 3000000, 2300000, "Risiko Mismatch", "Jalur yang tidak sesuai kemampuan dapat meningkatkan risiko kelelahan, keterlambatan, atau kondisi darurat.", COLORS["yellow"]),
    card(60, 8030000, 2050000, 3000000, 2300000, "Butuh Pemahaman Semantik", "Query seperti 'jalur landai untuk pemula' perlu dipahami sebagai makna, bukan sekadar kata kunci.", COLORS["primary"]),
    text_box(70, 1100000, 5050000, 10000000, 500000, ["Tujuan proyek: membantu pendaki memilih rute berdasarkan karakteristik jalur dan preferensi bahasa alami."], 2300, COLORS["text"], True, "ctr"),
]))

add_slide(title("Konsep Solusi RuteStrip", "solution") + "".join([
    bullet_box(40, 900000, 2050000, 5000000, 3000000, [
        "Upload GPX untuk membaca koordinat, elevasi, jarak, dan trackpoint.",
        "Atribut numerik dikonversi menjadi narasi agar bisa diproses model bahasa.",
        "SBERT mengubah narasi jalur dan query pengguna menjadi embedding 384 dimensi.",
        "Cosine Similarity menghitung kedekatan query dengan setiap jalur.",
        "Top-N route ditampilkan sebagai rekomendasi.",
    ], 2200),
    card(60, 6900000, 2250000, 3800000, 2100000, "Kenapa Content-Based?", "Tidak butuh histori banyak user. Cocok untuk dataset jalur pendakian yang item-nya punya atribut kuat: jarak, elevasi, grade, durasi, dan deskripsi.", COLORS["primary"]),
]))

add_slide(title("Data & Fusi Informasi", "dataset") + "".join([
    card(40, 850000, 2050000, 3000000, 1900000, "Data Spasial", "GPX berisi latitude, longitude, elevation, dan trackpoint jalur pendakian.", COLORS["blue"]),
    card(50, 4440000, 2050000, 3000000, 1900000, "Data Deskriptif", "Informasi vegetasi, panorama, sumber air, karakter medan, dan tips pendakian.", COLORS["primary"]),
    card(60, 8030000, 2050000, 3000000, 1900000, "Data Fusion", "Numerik GPX + deskripsi literatur digabung menjadi narasi lengkap per jalur.", COLORS["yellow"]),
    text_box(70, 850000, 4650000, 10200000, 500000, ["Project lokal saat ini menyimpan 117 file GPX mentah dan 21 route aktif dengan embedding; paper menggunakan 39 jalur Pulau Jawa sebagai dataset evaluasi."], 2200, COLORS["muted"], False, "ctr"),
]))

add_slide(title("Tahap 1: Ekstraksi GPX", "algorithm") + "".join([
    bullet_box(40, 900000, 2050000, 5600000, 2600000, [
        "Parse file GPX menggunakan gpxpy.",
        "Hitung jarak 3D lintasan.",
        "Hitung kenaikan elevasi dari perubahan elevasi positif.",
        "Estimasi durasi memakai Rumus Naismith: distance/5 + elevation_gain/600.",
        "Hitung average grade: elevation_gain / (distance × 1000) × 100.",
    ], 2150),
    card(60, 7400000, 2300000, 3300000, 1800000, "Output", "distance_km\nelevation_gain_m\nnaismith_duration_hour\naverage_grade_pct\nroute_coordinates", COLORS["primary"]),
]))

add_slide(title("Tahap 2: Preprocessing Teks", "algorithm") + "".join([
    card(40, 850000, 2050000, 3000000, 1900000, "Cleaning", "Menghapus URL, simbol, karakter noise, angka berdiri sendiri, dan whitespace berlebih.", COLORS["blue"]),
    card(50, 4440000, 2050000, 3000000, 1900000, "Case Folding", "Menyeragamkan teks menjadi lowercase agar variasi kapital tidak dihitung sebagai kata berbeda.", COLORS["primary"]),
    card(60, 8030000, 2050000, 3000000, 1900000, "Selective Stopword", "Stopword umum dihapus, tetapi negasi dan kata domain seperti landai, curam, mudah, sulit tetap dipertahankan.", COLORS["yellow"]),
    text_box(70, 900000, 4650000, 10100000, 600000, ["Stemming sengaja tidak digunakan karena SBERT memanfaatkan konteks dan morfologi kata untuk memahami makna kalimat."], 2200, COLORS["muted"], False, "ctr"),
]))

add_slide(title("Tahap 3: SBERT Embedding", "algorithm") + "".join([
    card(40, 900000, 2050000, 4200000, 2100000, "Model", "paraphrase-multilingual-MiniLM-L12-v2\nTransformer 12 layer\nMendukung Bahasa Indonesia\nOutput 384 dimensi", COLORS["primary"]),
    card(50, 6000000, 2050000, 4200000, 2100000, "Peran", "Mengubah narasi jalur dan input pengguna menjadi dense vector yang merepresentasikan makna, bukan sekadar frekuensi kata.", COLORS["blue"]),
    text_box(60, 1150000, 4750000, 9800000, 600000, ["Contoh: sistem dapat mendekatkan makna 'jalur landai' dengan 'cocok untuk pemula' walaupun frasanya tidak identik."], 2300, COLORS["text"], True, "ctr"),
]))

add_slide(title("Tahap 4: Cosine Similarity & Ranking", "algorithm") + "".join([
    text_box(40, 1400000, 2050000, 9300000, 650000, ["Sim(Q, D) = (Q · D) / (||Q|| × ||D||)"], 3600, COLORS["primary"], True, "ctr", "Consolas"),
    bullet_box(50, 1100000, 3300000, 9700000, 1900000, [
        "Q adalah embedding query pengguna.",
        "D adalah embedding tiap jalur pendakian.",
        "Skor semakin tinggi berarti arah vektor semakin mirip secara semantik.",
        "Semua jalur diurutkan descending dan ditampilkan sebagai Top-N rekomendasi.",
    ], 2300),
]))

add_slide(title("Hasil Evaluasi Paper", "result") + "".join([
    card(40, 850000, 2050000, 2400000, 1700000, "Precision@5", "0,60\nrata-rata", COLORS["primary"]),
    card(50, 3600000, 2050000, 2400000, 1700000, "Baseline TF-IDF", "0,25\nrata-rata", COLORS["red"]),
    card(60, 6350000, 2050000, 2400000, 1700000, "Peningkatan", "+140%\nvs TF-IDF", COLORS["yellow"]),
    card(70, 9100000, 2050000, 2400000, 1700000, "Embedding", "384\ndimensi", COLORS["blue"]),
    bullet_box(80, 1100000, 4350000, 9600000, 1200000, [
        "Query 'trek menantang elevasi tinggi' mencapai Precision@5 = 1,00.",
        "Query 'gunung dengan sabana dan sunrise' juga mencapai Precision@5 = 1,00 karena SBERT menangkap relasi semantik implisit.",
    ], 2150),
]))

add_slide(title("Implementasi Sistem", "project") + "".join([
    card(40, 850000, 2050000, 3000000, 2100000, "Laravel", "Web app, routing, auth, dashboard admin, upload GPX, penyimpanan data dan embedding.", COLORS["primary"]),
    card(50, 4440000, 2050000, 3000000, 2100000, "Python Processor", "FastAPI, gpxpy, SBERT, scikit-learn, embedding, dan pemrosesan GPX. Bisa lokal atau Hugging Face Space.", COLORS["blue"]),
    card(60, 8030000, 2050000, 3000000, 2100000, "Fitur Tambahan", "Chatbot RAG Gemini, provenance GPX, IPFS, hashing, versioning, dan blockchain registry.", COLORS["yellow"]),
]))

add_slide(title("Dampak & Nilai Karya", "impact") + "".join([
    bullet_box(40, 900000, 2050000, 5200000, 2700000, [
        "Membantu pendaki memilih jalur berdasarkan kemampuan dan preferensi bahasa alami.",
        "Mengubah data GPX mentah menjadi informasi rekomendasi yang mudah dipahami.",
        "Membuktikan bahwa pendekatan NLP modern bisa relevan untuk domain wisata alam dan keselamatan pendakian.",
        "Menjadi portfolio yang menggabungkan produk, riset, dan kompetisi data science.",
    ], 2200),
    card(60, 7050000, 2350000, 3600000, 1900000, "Validasi Eksternal", "Juara 2 GEMASI 2026 Data Science dan terbit di jurnal SISTEMASI SINTA 3.", COLORS["primary"]),
]))

add_slide(title("Penutup", "closing") + "".join([
    text_box(40, 1200000, 2100000, 9800000, 900000, ["RuteStrip menunjukkan bahwa data jalur pendakian tidak berhenti sebagai file GPX, tetapi bisa diolah menjadi rekomendasi yang kontekstual, terukur, dan berguna untuk pengambilan keputusan pendaki."], 2600, COLORS["text"], True, "ctr"),
    card(50, 2100000, 3950000, 3500000, 1300000, "Kontak", "Rama Danadipa Putra Wijaya\nNIM 23.12.2956", COLORS["primary"]),
    card(60, 6450000, 3950000, 3500000, 1300000, "Publikasi", "SISTEMASI SINTA 3\nDOI: 10.32520/stmsi.v15i4.6087", COLORS["blue"]),
]))


def slide_rels() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slideLayout" Target="../slideLayouts/slideLayout1.xml"/>
</Relationships>"""


def write_package() -> None:
    OUT.parent.mkdir(parents=True, exist_ok=True)
    with zipfile.ZipFile(OUT, "w", zipfile.ZIP_DEFLATED) as z:
        z.writestr("[Content_Types].xml", content_types())
        z.writestr("_rels/.rels", root_rels())
        z.writestr("docProps/core.xml", core_props())
        z.writestr("docProps/app.xml", app_props())
        z.writestr("ppt/presentation.xml", presentation_xml())
        z.writestr("ppt/_rels/presentation.xml.rels", presentation_rels())
        z.writestr("ppt/slideMasters/slideMaster1.xml", slide_master())
        z.writestr("ppt/slideMasters/_rels/slideMaster1.xml.rels", master_rels())
        z.writestr("ppt/slideLayouts/slideLayout1.xml", slide_layout())
        z.writestr("ppt/slideLayouts/_rels/slideLayout1.xml.rels", layout_rels())
        z.writestr("ppt/theme/theme1.xml", theme_xml())
        for i, slide in enumerate(slides, 1):
            z.writestr(f"ppt/slides/slide{i}.xml", slide)
            z.writestr(f"ppt/slides/_rels/slide{i}.xml.rels", slide_rels())


def content_types() -> str:
    slide_overrides = "".join(
        f'<Override PartName="/ppt/slides/slide{i}.xml" ContentType="application/vnd.openxmlformats-officedocument.presentationml.slide+xml"/>'
        for i in range(1, len(slides) + 1)
    )
    return f"""<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>
  <Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>
  <Override PartName="/ppt/presentation.xml" ContentType="application/vnd.openxmlformats-officedocument.presentationml.presentation.main+xml"/>
  <Override PartName="/ppt/slideMasters/slideMaster1.xml" ContentType="application/vnd.openxmlformats-officedocument.presentationml.slideMaster+xml"/>
  <Override PartName="/ppt/slideLayouts/slideLayout1.xml" ContentType="application/vnd.openxmlformats-officedocument.presentationml.slideLayout+xml"/>
  <Override PartName="/ppt/theme/theme1.xml" ContentType="application/vnd.openxmlformats-officedocument.theme+xml"/>
  {slide_overrides}
</Types>"""


def root_rels() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="ppt/presentation.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>
  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>
</Relationships>"""


def core_props() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <dc:title>RuteStrip - Presentasi SINTA 3 dan GEMASI 2026</dc:title>
  <dc:creator>OpenCode</dc:creator>
  <cp:lastModifiedBy>OpenCode</cp:lastModifiedBy>
  <dcterms:created xsi:type="dcterms:W3CDTF">2026-07-01T00:00:00Z</dcterms:created>
  <dcterms:modified xsi:type="dcterms:W3CDTF">2026-07-01T00:00:00Z</dcterms:modified>
</cp:coreProperties>"""


def app_props() -> str:
    return f"""<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">
  <Application>OpenCode</Application><PresentationFormat>Widescreen</PresentationFormat><Slides>{len(slides)}</Slides>
</Properties>"""


def presentation_xml() -> str:
    sld_ids = "".join(f'<p:sldId id="{255+i}" r:id="rId{i}"/>' for i in range(1, len(slides) + 1))
    return f"""<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<p:presentation xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main">
  <p:sldMasterIdLst><p:sldMasterId id="2147483648" r:id="rId{len(slides)+1}"/></p:sldMasterIdLst>
  <p:sldIdLst>{sld_ids}</p:sldIdLst>
  <p:sldSz cx="{SLIDE_W}" cy="{SLIDE_H}" type="wide"/><p:notesSz cx="6858000" cy="9144000"/>
</p:presentation>"""


def presentation_rels() -> str:
    rels = [f'<Relationship Id="rId{i}" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slide" Target="slides/slide{i}.xml"/>' for i in range(1, len(slides) + 1)]
    rels.append(f'<Relationship Id="rId{len(slides)+1}" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slideMaster" Target="slideMasters/slideMaster1.xml"/>')
    rels.append(f'<Relationship Id="rId{len(slides)+2}" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme" Target="theme/theme1.xml"/>')
    return f"<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Relationships xmlns=\"http://schemas.openxmlformats.org/package/2006/relationships\">{''.join(rels)}</Relationships>"


def slide_master() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<p:sldMaster xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main">
  <p:cSld><p:spTree><p:nvGrpSpPr><p:cNvPr id="1" name=""/><p:cNvGrpSpPr/><p:nvPr/></p:nvGrpSpPr><p:grpSpPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="0" cy="0"/><a:chOff x="0" y="0"/><a:chExt cx="0" cy="0"/></a:xfrm></p:grpSpPr></p:spTree></p:cSld>
  <p:sldLayoutIdLst><p:sldLayoutId id="2147483649" r:id="rId1"/></p:sldLayoutIdLst><p:txStyles><p:titleStyle/><p:bodyStyle/><p:otherStyle/></p:txStyles>
</p:sldMaster>"""


def master_rels() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slideLayout" Target="../slideLayouts/slideLayout1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme" Target="../theme/theme1.xml"/>
</Relationships>"""


def slide_layout() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<p:sldLayout xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main" type="blank" preserve="1">
  <p:cSld name="Blank"><p:spTree><p:nvGrpSpPr><p:cNvPr id="1" name=""/><p:cNvGrpSpPr/><p:nvPr/></p:nvGrpSpPr><p:grpSpPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="0" cy="0"/><a:chOff x="0" y="0"/><a:chExt cx="0" cy="0"/></a:xfrm></p:grpSpPr></p:spTree></p:cSld>
</p:sldLayout>"""


def layout_rels() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/slideMaster" Target="../slideMasters/slideMaster1.xml"/>
</Relationships>"""


def theme_xml() -> str:
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<a:theme xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" name="RuteStrip Nature">
  <a:themeElements><a:clrScheme name="RuteStrip"><a:dk1><a:srgbClr val="0C1810"/></a:dk1><a:lt1><a:srgbClr val="E8F5EC"/></a:lt1><a:dk2><a:srgbClr val="142218"/></a:dk2><a:lt2><a:srgbClr val="9CB8A6"/></a:lt2><a:accent1><a:srgbClr val="22C55E"/></a:accent1><a:accent2><a:srgbClr val="60A5FA"/></a:accent2><a:accent3><a:srgbClr val="FBBF24"/></a:accent3><a:accent4><a:srgbClr val="F87171"/></a:accent4><a:accent5><a:srgbClr val="16A34A"/></a:accent5><a:accent6><a:srgbClr val="86EFAC"/></a:accent6><a:hlink><a:srgbClr val="60A5FA"/></a:hlink><a:folHlink><a:srgbClr val="86EFAC"/></a:folHlink></a:clrScheme><a:fontScheme name="RuteStrip"><a:majorFont><a:latin typeface="Segoe UI"/></a:majorFont><a:minorFont><a:latin typeface="Segoe UI"/></a:minorFont></a:fontScheme><a:fmtScheme name="RuteStrip"><a:fillStyleLst><a:solidFill><a:schemeClr val="phClr"/></a:solidFill></a:fillStyleLst><a:lnStyleLst><a:ln w="9525"><a:solidFill><a:schemeClr val="phClr"/></a:solidFill></a:ln></a:lnStyleLst><a:effectStyleLst><a:effectStyle><a:effectLst/></a:effectStyle></a:effectStyleLst><a:bgFillStyleLst><a:solidFill><a:schemeClr val="phClr"/></a:solidFill></a:bgFillStyleLst></a:fmtScheme></a:themeElements><a:objectDefaults/><a:extraClrSchemeLst/>
</a:theme>"""


write_package()
print(OUT)
