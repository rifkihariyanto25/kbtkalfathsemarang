document.addEventListener('DOMContentLoaded', function() {
    // Sample news data (in a real app, this would come from a database)
    const newsData = [
        {
            id: 1,
            thumbnail: 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
            title: 'Pendaftaran KB ISLAM AL FATH Tahun Dibuka !',
            content: `<p>KB-TK ISLAM AL FATH dengan bangga mengumumkan pembukaan pendaftaran untuk tahun ajaran baru 2023/2024. Kami mengundang para orangtua untuk mendaftarkan putra-putri mereka di lembaga pendidikan yang mengutamakan nilai-nilai Islam dan pendidikan karakter.</p>

<p>Pendaftaran dibuka mulai tanggal 15 Januari 2023 dan akan berlangsung hingga kuota terpenuhi. Tahun ini, kami menyediakan kuota terbatas untuk memastikan kualitas pendidikan yang optimal bagi setiap anak.</p>

<p>KB-TK ISLAM AL FATH menawarkan:</p>
<ul class="list-disc pl-6 my-4">
    <li>Kurikulum terintegrasi yang memadukan pendidikan nasional dan nilai-nilai Islam</li>
    <li>Tenaga pengajar yang profesional dan berpengalaman</li>
    <li>Fasilitas belajar yang nyaman dan modern</li>
    <li>Program pengembangan karakter dan keterampilan sosial</li>
    <li>Kegiatan ekstrakurikuler yang beragam</li>
</ul>

<p>Untuk informasi lebih lanjut tentang prosedur pendaftaran, biaya, dan persyaratan, orangtua dapat menghubungi kantor sekolah di nomor (024) 1234567 atau mengunjungi sekolah pada jam kerja.</p>

<p>Jadilah bagian dari keluarga besar KB-TK ISLAM AL FATH dan berikan anak Anda pendidikan terbaik untuk masa depan yang cerah!</p>`,
            date: '2023-01-15',
            category: 'pengumuman'
        },
        {
            id: 2,
            thumbnail: 'https://images.unsplash.com/photo-1544717297-fa95b6ee9643?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
            title: 'Pendaftaran TK ISLAM AL FATH Tahun Dibuka !',
            content: `<p>TK ISLAM AL FATH dengan bangga mengumumkan pembukaan pendaftaran untuk tahun ajaran baru 2023/2024. Kami mengundang para orangtua untuk mendaftarkan putra-putri mereka di lembaga pendidikan yang mengutamakan nilai-nilai Islam dan pendidikan karakter.</p>

<p>Pendaftaran dibuka mulai tanggal 15 Januari 2023 dan akan berlangsung hingga kuota terpenuhi. Tahun ini, kami menyediakan kuota terbatas untuk memastikan kualitas pendidikan yang optimal bagi setiap anak.</p>

<p>TK ISLAM AL FATH menawarkan:</p>
<ul class="list-disc pl-6 my-4">
    <li>Kurikulum terintegrasi yang memadukan pendidikan nasional dan nilai-nilai Islam</li>
    <li>Tenaga pengajar yang profesional dan berpengalaman</li>
    <li>Fasilitas belajar yang nyaman dan modern</li>
    <li>Program pengembangan karakter dan keterampilan sosial</li>
    <li>Kegiatan ekstrakurikuler yang beragam</li>
</ul>

<p>Untuk informasi lebih lanjut tentang prosedur pendaftaran, biaya, dan persyaratan, orangtua dapat menghubungi kantor sekolah di nomor (024) 1234567 atau mengunjungi sekolah pada jam kerja.</p>

<p>Jadilah bagian dari keluarga besar TK ISLAM AL FATH dan berikan anak Anda pendidikan terbaik untuk masa depan yang cerah!</p>`,
            date: '2023-01-15',
            category: 'pengumuman'
        },
        {
            id: 3,
            thumbnail: 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
            title: 'Peringatan Memperingati 17 Agustus di KB TK ISLAM AL FATH !',
            content: `<p>Dalam rangka memperingati hari kemerdekaan Republik Indonesia yang ke-78, KB-TK ISLAM AL FATH menyelenggarakan berbagai kegiatan menarik yang melibatkan seluruh siswa, guru, dan staf sekolah.</p>

<p>Perayaan dimulai dengan upacara bendera yang khidmat, di mana anak-anak berpartisipasi dengan penuh semangat. Mereka mengenakan seragam merah putih dan dengan bangga menyanyikan lagu Indonesia Raya.</p>

<p>Setelah upacara, berbagai lomba tradisional diselenggarakan, seperti:</p>
<ul class="list-disc pl-6 my-4">
    <li>Lomba makan kerupuk</li>
    <li>Balap karung mini untuk anak-anak</li>
    <li>Lomba memasukkan pensil ke dalam botol</li>
    <li>Estafet bendera</li>
    <li>Lomba mewarnai dengan tema kemerdekaan</li>
</ul>

<p>Anak-anak sangat antusias mengikuti semua kegiatan. Mereka belajar tentang nilai-nilai perjuangan, kerja sama, dan semangat pantang menyerah melalui permainan yang menyenangkan.</p>

<p>Para guru juga menyampaikan cerita-cerita inspiratif tentang perjuangan pahlawan kemerdekaan dengan cara yang mudah dipahami oleh anak-anak. Hal ini bertujuan untuk menanamkan rasa cinta tanah air dan menghargai jasa para pahlawan sejak dini.</p>

<p>Perayaan ditutup dengan pembagian hadiah untuk para pemenang lomba dan foto bersama. Kegiatan ini tidak hanya memberikan kegembiraan bagi anak-anak tetapi juga menanamkan nilai-nilai patriotisme dan kebangsaan dalam diri mereka.</p>`,
            date: '2023-08-17',
            category: 'kegiatan'
        },
        {
            id: 4,
            thumbnail: 'https://images.unsplash.com/photo-1535572290543-960a8046f5af?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
            title: 'Siswa TK ISLAM AL FATH Juara Lomba Mewarnai Tingkat Kota',
            content: `<p>Dengan bangga kami mengumumkan bahwa Ananda Zahra, siswa TK B ISLAM AL FATH, telah berhasil meraih juara 1 dalam Lomba Mewarnai Tingkat Kota yang diselenggarakan oleh Dinas Pendidikan Kota Semarang pada tanggal 8 September 2023.</p>

<p>Lomba ini diikuti oleh lebih dari 200 peserta dari berbagai TK di seluruh kota. Ananda Zahra berhasil mengungguli peserta lainnya dengan karya mewarnai yang kreatif, rapi, dan penuh makna. Tema lomba kali ini adalah "Lingkungan Hidup" dan Zahra mampu menuangkan idenya dengan sangat baik.</p>

<p>Keberhasilan ini tidak lepas dari bimbingan intensif dari guru seni, Ibu Fatimah, serta dukungan penuh dari orangtua. Di TK ISLAM AL FATH, kami selalu mendorong anak-anak untuk mengembangkan bakat dan minat mereka di berbagai bidang, termasuk seni.</p>

<p>Prestasi Ananda Zahra ini menambah daftar pencapaian siswa-siswi TK ISLAM AL FATH dalam berbagai kompetisi. Hal ini membuktikan bahwa pendekatan pendidikan yang kami terapkan, yang menyeimbangkan aspek akademik, karakter, dan pengembangan bakat, memberikan hasil yang membanggakan.</p>

<p>Kami mengucapkan selamat kepada Ananda Zahra dan berharap prestasi ini dapat menginspirasi teman-teman lainnya untuk terus berkarya dan berprestasi di bidang yang mereka minati.</p>`,
            date: '2023-09-10',
            category: 'prestasi'
        }
    ];

    // Get news ID from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const newsId = parseInt(urlParams.get('id'));

    // Find the news item
    const newsItem = newsData.find(item => item.id === newsId);

    // If news item not found, redirect to news page
    if (!newsItem) {
        window.location.href = 'berita.html';
        return;
    }

    // Update page title
    document.title = `${newsItem.title} - KB - TK ISLAM AL FATH`;

    // Update news content
    document.getElementById('news-title').textContent = newsItem.title;
    document.getElementById('breadcrumb-title').textContent = newsItem.title;
    document.getElementById('content-title').textContent = newsItem.title;
    document.getElementById('news-image').src = newsItem.thumbnail;
    document.getElementById('news-image').alt = newsItem.title;
    document.getElementById('news-content').innerHTML = newsItem.content;

    // Format date
    const formattedDate = new Date(newsItem.date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    document.getElementById('news-date').textContent = formattedDate;
    document.getElementById('content-date').textContent = formattedDate;

    // Set category
    const categoryLabel = {
        'pengumuman': 'Pengumuman',
        'kegiatan': 'Kegiatan',
        'prestasi': 'Prestasi'
    }[newsItem.category];

    const categoryColor = {
        'pengumuman': 'bg-green-100 text-green-800',
        'kegiatan': 'bg-blue-100 text-blue-800',
        'prestasi': 'bg-purple-100 text-purple-800'
    }[newsItem.category];

    document.getElementById('news-category').textContent = categoryLabel;
    
    const categoryBadge = document.getElementById('category-badge');
    categoryBadge.textContent = categoryLabel;
    categoryBadge.className = `px-3 py-1 text-sm rounded-full ${categoryColor}`;

    // Populate related news
    const relatedNewsContainer = document.getElementById('related-news');
    
    // Get related news (same category, excluding current news)
    const relatedNews = newsData
        .filter(item => item.category === newsItem.category && item.id !== newsItem.id)
        .slice(0, 3); // Get up to 3 related news

    // If not enough related news in same category, add some from other categories
    if (relatedNews.length < 3) {
        const additionalNews = newsData
            .filter(item => item.category !== newsItem.category && item.id !== newsItem.id)
            .slice(0, 3 - relatedNews.length);
        
        relatedNews.push(...additionalNews);
    }

    // Create related news items
    relatedNews.forEach(item => {
        const formattedItemDate = new Date(item.date).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });

        const itemCategoryLabel = {
            'pengumuman': 'Pengumuman',
            'kegiatan': 'Kegiatan',
            'prestasi': 'Prestasi'
        }[item.category];

        const truncatedContent = item.content
            .replace(/<[^>]*>/g, '') // Remove HTML tags
            .substring(0, 100) + '...';

        const newsItem = document.createElement('article');
        newsItem.className = 'bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300';
        newsItem.innerHTML = `
            <div class="h-48 overflow-hidden">
                <img src="${item.thumbnail}" alt="${item.title}"
                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
            </div>
            <div class="p-4">
                <header>
                    <h3 class="font-bold text-gray-800 mb-2 line-clamp-2">
                        ${item.title}
                    </h3>
                </header>
                <p class="text-sm text-gray-600 mb-3">
                    ${truncatedContent}
                </p>
                <div class="text-xs text-orange-500 flex items-center">
                    <i class="far fa-calendar-alt mr-1"></i>
                    <time datetime="${item.date}">${formattedItemDate}</time>
                </div>
                <div class="mt-2">
                    <a href="berita-detail.html?id=${item.id}" class="text-xs text-white bg-red-500 hover:bg-red-600 font-medium px-3 py-1 rounded-md transition duration-300 ease-in-out flex items-center inline-flex">
                        Baca Selengkapnya
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        `;

        relatedNewsContainer.appendChild(newsItem);
    });

    // Back to top button functionality
    const backToTopButton = document.getElementById('back-to-top');
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.remove('opacity-0');
            backToTopButton.classList.add('opacity-100');
        } else {
            backToTopButton.classList.remove('opacity-100');
            backToTopButton.classList.add('opacity-0');
        }
    });

    backToTopButton.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});