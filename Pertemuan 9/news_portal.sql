-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2026 at 01:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `news_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Nasional', 'nasional', 'Berita dalam negeri'),
(2, 'Teknologi', 'teknologi', 'Gawai, internet, dan inovasi'),
(3, 'Olahraga', 'olahraga', 'Kabar dari lapangan'),
(4, 'Ekonomi', 'ekonomi', 'Bisnis, pasar, dan keuangan');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `news_id`, `name`, `email`, `comment`, `created_at`) VALUES
(1, 1, 'Bhalil', 'bhalil@gmail.com', 'Heran dech sama Mahasiswa yang Demo kemarin ,Karhutla itu kan bencana Alam tapi mereka malah salahin Bapak Prabowo,,,,', '2026-11-03 19:13:49'),
(2, 1, 'Ted', 'teddy@gmail.com', '@Bhalil Betul mas, aku juga heran mereka yang teriak bukan warga Kalimantan tpi mereka memanfaatkan buat nyerang pak presiden', '2026-11-03 19:18:45'),
(3, 1, 'Athena', 'athena@gmail.com', '@Bhalil @Ted cair cairrrrr', '2026-11-03 19:19:51');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `category_id`, `title`, `slug`, `content`, `image`, `author_id`, `published_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Sejauh mana pemerintah dapat digugat terkait pencegahan dan penanganan karhutla?', 'sejauh-mana-pemerintah-dapat-digugat-terkait-pencegahan-dan-penanganan-karhutla', 'Kebakaran hutan dan lahan (karhutla) terjadi di beberapa provinsi di Indonesia. Peristiwa ini bukan kali pertama serta berulang dari tahun ke tahun sehingga memunculkan pertanyaan: dapatkah pemerintah, selaku pemegang kebijakan, digugat atas penanganan karhutla? ==Jawabannya: bisa.==\r\n\r\nPer akhir Agustus 2026, karhutla berdampak terhadap **lebih dari 10 juta orang** di sejumlah provinsi, dari Sumatra sampai Kalimantan. Salah satu yang dirasakan masyarakat, mengutip Kementerian Kesehatan, ialah infeksi saluran pernapasan akut (ISPA).\r\n\r\nHitung-hitungan Center of Economic and Law Studies (CELIOS), organisasi ekonomi, memperkirakan ==kerugian yang dihasilkan karhutla menyentuh Rp29,45 triliun.== Angka ini diambil dari hilangnya aset fisik serta aktivitas perekonomian.\r\n\r\nBerangkat dari dua kondisi tersebut, pakar hukum tata negara Universitas Gadjah Mada, Yance Arizona, memandang gugatan kepada pemerintah bisa ditempuh.\r\n\r\n\"Jadi, kalau dilihat dari sisi prosedur hukum yang bisa dipakai dalam kondisi hari ini ada dua [metode],\" paparnya.\r\n\r\n\"Satu, apakah lewat Pengadilan Tata Usaha Negara [PTUN]. Atau, dua, lewat pengadilan perdata.\"\r\n\r\nGugatan ke pemerintah, terkhusus soal karhutla, bukan barang baru. Pada 2016, pemerintah digugat sehubungan karhutla di Kalimantan Tengah, disusul gugatan sejenis dengan dasar pertimbangan karhutla di Riau.\r\n\r\nTapi, dua gugatan yang ada tidak berujung menggembirakan. Upaya mencari keadilan sekaligus pertanggungjawaban pemerintah tak berbuah manis.\r\n\r\n\"Nah, dari pembelajaran itu sebenarnya kami melihat bahwa ada sedikit kompleksitas. Ada kemenangan, tapi kemudian bagaimana kemenangan itu dieksekusi,\" terang Ketua Tim Kampanye Hutan Greenpeace, Arie Rompas.\r\n\r\n\"Lalu soal pengetahuan hakim, soal dampak kerugian dari kerusakan lingkungan. Walaupun sudah kami sertakan dalam gugatan, hakim tetap menolaknya.\"\r\n\r\n## Objektif gugatan sudah terpenuhi\r\n\r\nKebakaran hutan dan lahan (karhutla) tahun ini memberi efek yang tidak sedikit untuk masyarakat.\r\n\r\nBerdasarkan data yang dihimpun Kementerian Kesehatan hingga akhir Agustus 2026, karhutla berdampak ke tujuh provinsi: Kalimantan Barat, Kalimantan Tengah, Kalimantan Selatan, Kalimantan Timur, Jambi, Riau, serta Sumatra Selatan.\r\n\r\nKalau dijumlah, penduduk yang kena imbas karhutla mencapai lebih dari 10 juta orang.\r\n\r\nAsap karhutla menyebabkan berbagai gangguan kesehatan, mulai dari batuk, sesak napas, radang tenggorokan, asma, penyakit paru-paru, iritasi kulit, hingga infeksi saluran pernapasan akut (ISPA).\r\n\r\nDari semuanya, ISPA paling masif ditemukan. Data Kementerian Kesehatan menunjukkan kasus ISPA menyentuh **hampir 14 ribu laporan**—dikumpulkan di 63 kabupaten atau kota di provinsi-provinsi terjadinya karhutla.\r\n\r\nKualitas udara tak luput dari masalah. Karhutla membikin udara yang dihirup hidung masuk kategori sangat tidak sehat sampai berbahaya. Di Pontianak, Kalimantan Barat, misalnya, nilai Indeks Standar Pencemar Udara (ISPU) sempat menyentuh ==328 atau tergolong berbahaya.==\r\n\r\nBegitu pula di Tebo (Jambi), Barito Selatan (Kalimantan Tengah), hingga Pali (Sumatra Selatan) yang masing-masing ISPU-nya berada di atas 250.\r\n\r\n![Langit di Kalimantan Tengah berwarna kuning akibat asap karhutla](4fda76b39b174154.jpg)\r\n\r\nRiset CELIOS menyatakan kerugian karhutla bisa dikonversikan secara finansial. Potensi kerugian ekonomi imbas karhutla ditaksir sebesar nyaris Rp30 triliun. CELIOS mengukurnya lewat aset yang terbakar serta kegiatan perekonomian—perdagangan, pertanian, sampai akomodasi makanan dan minuman—yang terhenti.\r\n\r\nSementara dari aspek kesehatan, angka kerugiannya tak kalah memprihatinkan. Perkiraan biaya kesehatan yang mesti dikeluarkan karena karhutla adalah **lebih dari Rp100 triliun**. Angka ini diproyeksikan bakal berbanding lurus dengan lonjakan klaim BPJS Kesehatan.\r\n\r\nMelihat kenyataan tersebut, pakar hukum tata negara dari Universitas Gadjah Mada, Yance Arizona, menegaskan publik dapat melayangkan gugatan ke pemerintah.\r\n\r\n\"Sudah banyak korban, penyakit yang terjadi, kerugian ekonomi juga, maka upaya hukum sangat terbuka,\" ucap Yance kala dihubungi BBC News Indonesia, Sabtu (05/09).\r\n\r\n> \"Kondisi objektifnya sudah terpenuhi.\"\r\n\r\nMenurut Yance, terdapat sepasang saluran hukum yang tersedia.\r\n\r\nPertama, melalui Pengadilan Tata Usaha Negara (PTUN). Gugatan via PTUN, tutur Yance, menyasar tindakan faktual yang dilangsungkan pemerintah, dalam hal ini kebijakan menyoal karhutla. Kebijakan yang dimaksud merentang dari pencegahan maupun penanggulangan.\r\n\r\nAndaikata opsi PTUN yang dipilih, permintaannya ialah mendesak pemerintah \"untuk melakukan sesuatu,\" tambah Yance.\r\n\r\n\"Ada dimensi ketidakmampuan [pemerintah] yang menjadi objek gugatan. Harapannya, dengan gugatan ini, pemerintah kemudian bisa melakukan tindakan-tindakan konkret dalam mengatasi karhutla,\" ungkap Yance.\r\n\r\nLalu strategi berikutnya, yang kedua, yaitu gugatan perdata atau terkenal dengan class action. Dasar pijakan dari class action, Yance menerangkan, adalah masyarakat sudah menjadi korban karhutla.\r\n\r\nBerbeda dengan jalur PTUN yang menginginkan perubahan kebijakan, class action ditujukan guna \"memperoleh ganti rugi termasuk dari sisi ekonomi,\" tandas Yance.\r\n\r\n\"Siapa yang menggugat, tentu orang atau kelompok yang betul-betul mengalami sakit atau ekonominya terganggu,\" tambahnya.\r\n\r\nSatu penelitian mengatakan pemerintah merupakan pihak yang kerap digugat dalam konteks perdata lingkungan, di bawah korporasi.\r\n\r\nDi antara keduanya, Yance menilai opsi yang memungkinkan dijajaki yaitu gugatan perdata—class action. Pasalnya, eksekusi putusan class action tidak sepelik jalur PTUN. Bicara tentang putusan PTUN, tidak ada mekanisme mengikat dalam realisasinya.\r\n\r\nArtinya, putusan dikembalikan ke pemerintah—mau menjalankan atau tidak.\r\n\r\n> \"Seringkali tidak dijalankan. Atau bisa jadi dimanipulasi,\" tukas Yance.\r\n\r\nYance mencontohkan kasus masyarakat Kendeng, Pati, Jawa Tengah, satu dekade silam tatkala mereka menggugat izin pembangunan pabrik semen. Pengadilan semula mengabulkan gugatan warga yang cemas proyek semen justru merusak lingkungan.\r\n\r\nAlih-alih mengevaluasi kebijakan tersebut, pemerintah provinsi justru mengeluarkan izin baru.\r\n\r\nSenada dengan Yance, riset yang disusun peneliti Yayasan Lembaga Bantuan Hukum Indonesia (YLBHI), Siti Rakhma Mery, menggambarkan bahwa mayoritas putusan PTUN tentang gugatan lingkungan berakhir gelap.\r\n\r\nSiti menelusuri putusan sepanjang 2018 sampai 2025. Hasilnya, ==dari 13 putusan hanya 2 yang menang.== Sisanya kalah serta ditetapkan NO (Niet Ontvankelijke Verklaard) atau gugatan mengandung cacat formil.\r\n\r\nTemuan tersebut memperlihatkan betapa hakim di PTUN tidak memahami konstruksi perlindungan lingkungan secara menyeluruh, jelas Siti. Selain itu, para hakim tak berupaya menggali pokok perkara dengan lebih lanjut—hanya berfokus pada persoalan formal.\r\n\r\n\"Dan tak boleh luput juga kalau hakim memakai dalil-dalil usang yang sudah tidak sejalan dengan perkembangan hukum lingkungan,\" terang Siti.\r\n\r\nSiti menganggap eksistensi PTUN, berdasarkan putusan-putusan ihwal gugatan lingkungan, bergeser jauh dari tujuan pembentukannya, bahwa pengadilan ini difungsikan \"membela hak-hak warga negara yang timpang dengan pemerintah.\"\r\n\r\n![Karhutla di Kalimantan Selatan.](c90cfd17befb86eb.webp)\r\n\r\n## Dibatalkan oleh Mahkamah Agung\r\n\r\nPada 2015, kebakaran hutan dan lahan (karhutla) menghantam delapan provinsi dengan luas area terdampak mencapai **2,6 juta hektare**. Ekspansi perkebunan sawit yang mengubah fungsi ruang—awalnya berbentuk lahan gambut—disinyalir sebagai faktor pendorong terbesar munculnya api berkepanjangan.\r\n\r\nKalkulasi Bank Dunia menyebut kerugian ekonomi yang dilahirkan dari karhutla 2015 ialah **Rp221 triliun**, setara 1,9% Pendapatan Domestik Bruto (PDB) Indonesia kala itu.\r\n\r\nTak berhenti di kerugian ekonomi, karhutla 2015 turut membikin masyarakat dirundung masalah kesehatan. Korban jiwa bahkan berjatuhan, sekira 24 orang di seluruh wilayah terdampak.\r\n\r\nBertolak dari realita tersebut, gugatan ke pemerintah dilayangkan.\r\n\r\nDi Kalimantan Tengah, yang luas area terbakar merepresentasikan 23% dari akumulatif, gugatan ditujukan kepada presiden, sebagian kecil menteri, gubernur, sampai Dewan Perwakilan Rakyat Daerah (DPRD).\r\n\r\nSubstansi gugatan terdiri atas permintaan kepada pemerintah untuk memperbaiki tata kelola ruang sehingga karhutla tak lagi menjadi musuh mematikan.\r\n\r\nApabila didetailkan lagi, penggugat mendesak peninjauan ulang terhadap izin-izin perusahaan, penegakan hukum yang terukur kepada korporasi, sampai penyusunan peta jalan (road map) dalam rangka pencegahan serta penanggulangan karhutla.\r\n\r\nGugatan ini lolos di tingkat pengadilan pertama hingga tinggi, sebelum akhirnya ==dibatalkan usai Mahkamah Agung (MA) menerima usaha peninjauan kembali (PK)== yang disodorkan pemerintah.\r\n\r\nKetua Tim Kampanye Hutan Greenpeace, Arie Rompas, yang terlibat dalam pengajuan gugatan, menerangkan putusan itu menunjukkan bahwa pengetahuan hakim terkait perlindungan lingkungan masih amat belum ideal.\r\n\r\nKendati tim penggugat sudah menyiapkan segala bukti tentang dampak karhutla, hakim tak menempatkannya menjadi pegangan.\r\n\r\nBagi Arie, gugatan kepada pemerintah—selain korporasi—bukan lagi \"sangat penting,\" melainkan \"harus dilakukan oleh warga negara.\"\r\n\r\n\"Termasuk korban-korban yang sudah terkena dampak,\" kata Arie dalam wawancara bersama BBC News Indonesia, Jum\'at (04/09).\r\n\r\nArie membaca gugatan terhadap pemerintah, terutama menyoal karhutla, dari dua bingkai sudut pandang.\r\n\r\nPertama, karhutla adalah bencana yang disebabkan aktivitas manusia (man-made disaster). Alurnya, tegas Arie, kurang lebih begini: pemerintah menyusun kebijakan yang mengubah ruang, korporasi yang mengeruk keuntungan.\r\n\r\nPengawasan pemerintah pun terkesan seadanya. Ujung-ujungnya, perusahaan longgar dalam membuka lahan, termasuk dengan cara membakar. Dari sini, karhutla senantiasa mengemuka dari tahun ke tahun.\r\n\r\nMenggugat pemerintah berarti meminta akuntabilitas penyelenggaraan kebijakan, menurut Arie.\r\n\r\n\"Terutama soal siapa yang bertanggung jawab dari karhutla ini, dan menebalkan anggapan bahwa karhutla bukan bencana alam,\" tuturnya.\r\n\r\nKedua, menggugat pemerintah dalam isu karhutla adalah tuntutan agar amanat konstitusi tetap dijaga. Arie menyatakan dengan adanya karhutla, pemenuhan hak atas lingkungan yang baik serta sehat \"telah diabaikan negara.\"\r\n\r\n> \"Pada intinya, menggugat pemerintah merupakan cara supaya preseden buruk soal karhutla tidak terus-menerus terjadi dan merugikan masyarakat maupun alam,\" tutup Arie.\r\n\r\n## Tak satu pun yang dipatuhi pemerintah\r\n\r\nGugatan serupa turut dilangsungkan di Riau, bersamaan gugatan mewakili Kalimantan Tengah pada karhutla 2015.\r\n\r\nDi Riau, gugatan menyasar setidaknya lima aspek, mulai dari menertibkan izin perusahaan yang bermasalah, mengalokasikan dana penanggulangan bencana, memperkuat pemantauan kualitas udara, menyediakan layanan bagi korban karhutla, serta membuat rumah sakit khusus paru-paru.\r\n\r\nPemerintah menyetujui materi gugatan yang diajukan terhadap mereka. Sayangnya, ==sampai sekarang tidak ada satu pun yang terlaksana==, papar Manajer Kampanye Hutan dan Kebun Wahana Lingkungan Hidup Indonesia (Walhi), Uli Arta Siagian, yang mengetahui riwayat gugatan.\r\n\r\nUli menjelaskan keberadaan gugatan karhutla kepada pemerintah memberi sinyal bahwa upaya-upaya preventif tidak pernah bergulir maksimal. Dalam perspektif Uli, peristiwa karhutla merupakan \"pertemuan dua persoalan besar.\"\r\n\r\n\"Satu, gagalnya negara memastikan perlindungan terhadap ekosistem penting kita, yakni gambut. Dua, gagalnya negara memastikan tata kelola perizinan yang baik dan yang dilaksanakan sebagaimana mestinya,\" ungkap Uli kepada BBC News Indonesia, Jum\'at (04/09).\r\n\r\nKetika negara tak benar-benar menunaikan tugasnya dengan komprehensif, maka gugatan tak ubahnya respons supaya mereka \"kembali ke jalan yang lurus,\" tambah Uli.\r\n\r\nNamun, mewujudkan gugatan ke dalam putusan yang berkeadilan bukan pekerjaan mudah, Uli menggarisbawahi. Sistem hukum di Indonesia belum seutuhnya memihak kepada korban karhutla. Mereka bergerak sesuai logika kekuasaan.\r\n\r\nSelama ini, untuk masalah karhutla, masyarakat seperti dibiarkan menanggung beban sendirian. Terlepas dari urusan gugatan, Uli mencontohkan realisasi atas hak pemulihan lingkungan.\r\n\r\nMengacu Undang-Undang Nomor 32 Tahun 2009 tentang Perlindungan dan Pengelolaan Lingkungan Hidup, tepatnya di pasal 78 serta 79, negara mempunyai legal standing (kedudukan hukum) dalam menganulir izin sekaligus menagih pertanggungjawaban pemulihan lingkungan bagi perusahaan yang terbukti bersalah.\r\n\r\nPermasalahannya, \"pemerintah tidak banyak melakukan hal tersebut,\" tegas Uli.\r\n\r\n\"Jadinya adalah pengulangan dan pengulangan. Tindakan yang diambil tidak membawa manfaat yang nyata. Kalau didiamkan, bukan tidak mungkin bencana manusia ini akan terus menghantui Indonesia,\" pungkasnya.\r\n\r\n## Pemerintah tegaskan upaya penegakan hukum\r\n\r\nPresiden Prabowo Subianto meminta penegakan hukum kepada pelaku pembakaran hutan dan lahan (karhutla) terus diperkuat, termasuk dengan mengusut dugaan kesengajaan pembakaran untuk perkebunan.\r\n\r\n\"Jadi, ini benar-benar kesengajaan, dan apakah ini rakyat atau ini korporasi? Tapi, tolong diselidiki terus dan saya minta segera nama-nama korporasi itu sampai ke saya,\" ujarnya di Istana Merdeka, Jakarta, Senin (07/09).\r\n\r\nKapolri Jenderal Polisi Listyo Sigit mengungkapkan hingga sekarang terdapat **200 laporan** yang diproses sehubungan pelaku karhutla. Dari jumlah itu, kepolisian mengklaim telah menetapkan ==138 tersangka.==\r\n\r\nDi luar individu, aparat turut mengusut keterlibatan korporasi: 8 perusahaan diproses, 18 didalami perannya, dan 42 sudah dijatuhi sanksi administrasi maupun pembekuan izin.\r\n\r\n![Seorang anak terkena dampak karhutla di Kalimantan.](2dac937573c880f5.webp)\r\n\r\nPenegakan hukum, menurut Kepala Subdirektorat III Dittipidter Bareskrim Polri, Kombes Pol Pipit Subiyanto, menjadi instrumen krusial guna memberikan efek jera. Pasalnya, berdasarkan data kepolisian, total area yang dibakar para pelaku menyentuh lebih dari 4 ribu hektare, imbuh Pipit.\r\n\r\n## Hukum yang melayani kepentingan penguasa\r\n\r\nAda dua skenario yang bakal keluar saat membahas gugatan kebakaran hutan dan lahan (karhutla) ke pemerintah, ucap pakar hukum tata negara di Universitas Gadjah Mada, Yance Arizona.\r\n\r\nPertama, gugatan tidak diterima. Kedua, gugatan dikabulkan tapi implementasi putusannya tumpul.\r\n\r\nEsensi institusi pengadilan, dalam perspektif Yance, diciptakan untuk menjalankan fungsi kontrol terhadap keputusan maupun tindakan pemerintah, di samping saluran bagi masyarakat menyampaikan keluhannya.\r\n\r\nManakala banyak kasus gugatan lingkungan tidak diteruskan secara baik oleh pengadilan, tandanya mereka \"gagal melakoni perannya,\" tukas Yance.\r\n\r\nApa yang terjadi pada serangkaian hasil gugatan lingkungan menggambarkan fenomena lebih luas di mana rezim berkuasa tidak \"berkomitmen penuh dalam penegakan hukum,\" menurut Yance.\r\n\r\nHukum, akhirnya, cuma dimanfaatkan oleh penguasa untuk melayani kepentingan mereka, tambah Yance. Yang ironis, saat menyinggung urusan masyarakat hukum seketika berpaling.\r\n\r\n> \"Kemudian yang dijumpai adalah hukum dipakai saja sebagai alat untuk politik, untuk kekuasaan. Entah itu proses legislasi, kebijakan, dan lain-lain,\" terang Yance.\r\n\r\n\"Kalau ada yang berpandangan hukumnya diubah, memang demikian. Apalagi kalau ada kepentingan tertentu, hukumnya dipakai untuk mencapai kepentingan itu.\"\r\n\r\n*Seluruh isi berita di atas merupakan hak cipta BBC News Indonesia. Sumber: https://www.bbc.com/indonesia/articles/c70lx97jwe1o*\r\n*Seluruh foto dokumentasi merupakan milik fotografer masing-masing dan diperoleh dari berbagai sumber di internet.*', 'ef6f270463038771.jpg', 1, '2026-11-03 18:58:00', 'published', '2026-11-03 19:08:45', '2026-11-03 19:40:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `role` enum('admin','editor') NOT NULL DEFAULT 'editor',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `fullname`, `role`, `created_at`) VALUES
(1, 'admin', '$2b$10$i..TIj.R7fy9teYdwOd4W.0rGqt2BQYh2ewDM9E4DhxJ36ZF7sIwq', 'Administrator', 'admin', '2026-11-03 17:52:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comment_news` (`news_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_news_cat` (`category_id`),
  ADD KEY `fk_news_author` (`author_id`),
  ADD KEY `idx_status_pub` (`status`,`published_at`);
ALTER TABLE `news` ADD FULLTEXT KEY `ft_search` (`title`,`content`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comment_news` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_news_cat` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
