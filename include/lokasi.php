<?php
// Mengatur header untuk JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Jika diperlukan

// Validasi dan sanitasi input
$query = isset($_GET['query']) ? trim(strtolower($_GET['query'])) : '';

// Daftar lokasi
$lokasi = [
    // Kabupaten di Jawa Tengah
    "banjarnegara" => "Banjarnegara, Jawa Tengah",
    "banyumas" => "Banyumas, Jawa Tengah",
    "batang" => "Batang, Jawa Tengah",
    "blora" => "Blora, Jawa Tengah",
    "boyolali" => "Boyolali, Jawa Tengah",
    "brebes" => "Brebes, Jawa Tengah",
    "cilacap" => "Cilacap, Jawa Tengah",
    "demak" => "Demak, Jawa Tengah",
    "grobogan" => "Grobogan, Jawa Tengah",
    "jepara" => "Jepara, Jawa Tengah",
    "karanganyar" => "Karanganyar, Jawa Tengah",
    "kebumen" => "Kebumen, Jawa Tengah",
    "kendal" => "Kendal, Jawa Tengah",
    "klaten" => "Klaten, Jawa Tengah",
    "kudus" => "Kudus, Jawa Tengah",
    "magelang" => "Magelang, Jawa Tengah",
    "pati" => "Pati, Jawa Tengah",
    "pemalang" => "Pemalang, Jawa Tengah",
    "purbalingga" => "Purbalingga, Jawa Tengah",
    "purworejo" => "Purworejo, Jawa Tengah",
    "rembang" => "Rembang, Jawa Tengah",
    "salatiga" => "Salatiga, Jawa Tengah",
    "semarang" => "Semarang, Jawa Tengah",
    "sragen" => "Sragen, Jawa Tengah",
    "sukoharjo" => "Sukoharjo, Jawa Tengah",
    "surakarta" => "Surakarta, Jawa Tengah",
    "temanggung" => "Temanggung, Jawa Tengah",
    "tegal" => "Tegal, Jawa Tengah",
    "wonosobo" => "Wonosobo, Jawa Tengah",
    "pekalongan" => "Pekalongan, Jawa Tengah",

    // Kabupaten di Jawa Barat
    "bandung" => "Bandung, Jawa Barat",
    "bandung_barat" => "Bandung Barat, Jawa Barat",
    "banda" => "Banda, Jawa Barat",
    "bekasi" => "Bekasi, Jawa Barat",
    "bogor" => "Bogor, Jawa Barat",
    "cianjur" => "Cianjur, Jawa Barat",
    "cirebon" => "Cirebon, Jawa Barat",
    "garut" => "Garut, Jawa Barat",
    "indramayu" => "Indramayu, Jawa Barat",
    "karawang" => "Karawang, Jawa Barat",
    "kuningan" => "Kuningan, Jawa Barat",
    "majalega" => "Majalengka, Jawa Barat",
    "purwakarta" => "Purwakarta, Jawa Barat",
    "subang" => "Subang, Jawa Barat",
    "sukabumi" => "Sukabumi, Jawa Barat",
    "sumedang" => "Sumedang, Jawa Barat",
    "tasikmalaya" => "Tasikmalaya, Jawa Barat",

    // Kabupaten di Jawa Timur
    "banyuwangi" => "Banyuwangi, Jawa Timur",
    "bangkalan" => "Bangkalan, Jawa Timur",
    "batu" => "Batu, Jawa Timur",
    "blitar" => "Blitar, Jawa Timur",
    "bojonegoro" => "Bojonegoro, Jawa Timur",
    "bondowoso" => "Bondowoso, Jawa Timur",
    "jember" => "Jember, Jawa Timur",
    "jombang" => "Jombang, Jawa Timur",
    "kediri" => "Kediri, Jawa Timur",
    "lamongan" => "Lamongan, Jawa Timur",
    "lumajang" => "Lumajang, Jawa Timur",
    "madiun" => "Madiun, Jawa Timur",
    "magetan" => "Magetan, Jawa Timur",
    "malang" => "Malang, Jawa Timur",
    "nganjuk" => "Nganjuk, Jawa Timur",
    "ngawi" => "Ngawi, Jawa Timur",
    "pacitan" => "Pacitan, Jawa Timur",
    "pamekasan" => "Pamekasan, Jawa Timur",
    "ponorogo" => "Ponorogo, Jawa Timur",
    "probolinggo" => "Probolinggo, Jawa Timur",
    "sampang" => "Sampang, Jawa Timur",
    "sidoarjo" => "Sidoarjo, Jawa Timur",
    "situbondo" => "Situbondo, Jawa Timur",
    "sumenep" => "Sumenep, Jawa Timur",
    "trenggalek" => "Trenggalek, Jawa Timur",
    "tuban" => "Tuban, Jawa Timur",
    "tulungagung" => "Tulungagung, Jawa Timur",

    // Kota dan Kabupaten di DKI Jakarta
    "jakarta_barat" => "Jakarta Barat, DKI Jakarta",
    "jakarta_pusat" => "Jakarta Pusat, DKI Jakarta",
    "jakarta_selatan" => "Jakarta Selatan, DKI Jakarta",
    "jakarta_timur" => "Jakarta Timur, DKI Jakarta",
    "jakarta_utara" => "Jakarta Utara, DKI Jakarta",
    "kepulauan_seribu" => "Kepulauan Seribu, DKI Jakarta",
];

// Hasil pencarian
$results = [];
if ($query !== '') {
    foreach ($lokasi as $key => $value) {
        if (stripos($key, $query) !== false || stripos($value, $query) !== false) {
            $results[] = $value;
        }
    }
}

// Mengembalikan hasil dalam format JSON
echo json_encode($results);
