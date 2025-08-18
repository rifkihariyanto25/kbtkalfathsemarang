document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const loginScreen = document.getElementById('login-screen');
    const adminDashboard = document.getElementById('admin-dashboard');
    const loginForm = document.getElementById('login-form');
    const uploadForm = document.getElementById('upload-form');
    const dragArea = document.getElementById('drag-area');
    const fileInput = document.getElementById('file-input');
    const previewContainer = document.getElementById('preview-container');
    const imagePreviews = document.getElementById('image-previews');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const logoutBtn = document.getElementById('logout-btn');
    const mobileLogout = document.getElementById('mobile-logout');
    const editModal = document.getElementById('edit-modal');
    const closeModal = document.getElementById('close-modal');
    const cancelEdit = document.getElementById('cancel-edit');
    const deleteModal = document.getElementById('delete-modal');
    const cancelDelete = document.getElementById('cancel-delete');
    const confirmDelete = document.getElementById('confirm-delete');
    const filterCategory = document.getElementById('filter-category');
    const refreshGallery = document.getElementById('refresh-gallery');
    const galleryItems = document.getElementById('gallery-items');
    
    // Navigation elements
    const navLinks = document.querySelectorAll('.sidebar-link');
    const gallerySection = document.getElementById('gallery-section');
    const newsSection = document.getElementById('news-section');
    const profileSection = document.getElementById('profile-section');

    // Sample gallery data (in a real app, this would come from a database)
    let galleryData = [
        {
            id: 1,
            title: 'Belajar di Kelas',
            category: 'tk',
            date: '2025-08-04',
            image: 'https://images.pexels.com/photos/8471801/pexels-photo-8471801.jpeg?auto=compress&cs=tinysrgb&w=600'
        },
        {
            id: 2,
            title: 'Bermain Balok',
            category: 'kb',
            date: '2025-08-04',
            image: 'https://images.pexels.com/photos/3662823/pexels-photo-3662823.jpeg?auto=compress&cs=tinysrgb&w=600'
        },
        {
            id: 3,
            title: 'Perayaan Kemerdekaan',
            category: 'spesial',
            date: '2025-08-04',
            image: 'https://images.pexels.com/photos/7939106/pexels-photo-7939106.jpeg?auto=compress&cs=tinysrgb&w=600'
        }
    ];
    
    // Sample news data (in a real app, this would come from a database)
    const newsData = [
        {
            id: 1,
            thumbnail: 'https://images.pexels.com/photos/8471801/pexels-photo-8471801.jpeg?auto=compress&cs=tinysrgb&w=600',
            title: 'Pendaftaran TK Islam Alfath Tahun Dibuka !',
            content: 'Pendaftaran untuk tahun ajaran baru di TK Islam Alfath telah dibuka. Orangtua dapat mendaftarkan putra-putrinya mulai tanggal 1 Januari 2023. Kuota terbatas!',
            date: '2022-12-04',
            category: 'pengumuman'
        },
        {
            id: 2,
            thumbnail: 'https://images.pexels.com/photos/8613089/pexels-photo-8613089.jpeg?auto=compress&cs=tinysrgb&w=600',
            title: 'Peringatan Memperingati 17 Agustus di KB TK Islam Alfath !',
            content: 'Anak-anak KB TK Islam Alfath merayakan hari kemerdekaan dengan berbagai kegiatan menarik seperti lomba makan kerupuk, balap karung, dan upacara bendera.',
            date: '2022-08-17',
            category: 'kegiatan'
        },
        {
            id: 3,
            thumbnail: 'https://images.pexels.com/photos/8535214/pexels-photo-8535214.jpeg?auto=compress&cs=tinysrgb&w=600',
            title: 'Siswa TK Islam Alfath Juara Lomba Mewarnai Tingkat Kota',
            content: 'Selamat kepada Ananda Zahra yang telah memenangkan juara 1 lomba mewarnai tingkat kota. Prestasi ini membanggakan sekolah dan orangtua.',
            date: '2022-09-10',
            category: 'prestasi'
        }
    ];

    // Login functionality
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        // Simple authentication (in a real app, this would be server-side)
        if (username === 'admin' && password === 'admin123') {
            loginScreen.classList.add('hidden');
            adminDashboard.classList.remove('hidden');
            // Store login state in session storage
            sessionStorage.setItem('isLoggedIn', 'true');
        } else {
            alert('Username atau password salah!');
        }
    });

    // Check if user is already logged in
    if (sessionStorage.getItem('isLoggedIn') === 'true') {
        loginScreen.classList.add('hidden');
        adminDashboard.classList.remove('hidden');
    }

    // Logout functionality
    function logout() {
        sessionStorage.removeItem('isLoggedIn');
        adminDashboard.classList.add('hidden');
        loginScreen.classList.remove('hidden');
        // Clear form fields
        document.getElementById('username').value = '';
        document.getElementById('password').value = '';
    }

    logoutBtn.addEventListener('click', logout);
    mobileLogout.addEventListener('click', logout);

    // Mobile sidebar toggle
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('open');
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 768 && 
            !sidebar.contains(e.target) && 
            e.target !== sidebarToggle && 
            !sidebarToggle.contains(e.target) && 
            sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
        }
    });
    
    // Navigation functionality
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links
            navLinks.forEach(l => l.classList.remove('active'));
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Hide all sections
            if (gallerySection) gallerySection.classList.add('hidden');
            if (newsSection) newsSection.classList.add('hidden');
            if (profileSection) profileSection.classList.add('hidden');
            
            // Show selected section
            const section = this.querySelector('span').textContent.trim().toLowerCase();
            if (section === 'galeri' && gallerySection) {
                gallerySection.classList.remove('hidden');
            } else if (section === 'berita' && newsSection) {
                newsSection.classList.remove('hidden');
            } else if (section === 'profil' && profileSection) {
                profileSection.classList.remove('hidden');
            }
            
            // Close sidebar on mobile after navigation
            if (window.innerWidth < 768) {
                sidebar.classList.remove('open');
            }
        });
    });

    // Drag and drop functionality
    dragArea.addEventListener('click', () => fileInput.click());

    // Handle drag events
    ['dragover', 'dragleave', 'drop'].forEach(eventName => {
        dragArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Handle dragover event
    dragArea.addEventListener('dragover', function() {
        dragArea.classList.add('active');
    });

    // Handle dragleave event
    dragArea.addEventListener('dragleave', function() {
        dragArea.classList.remove('active');
    });

    // Handle drop event
    dragArea.addEventListener('drop', function(e) {
        dragArea.classList.remove('active');
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    // Handle file input change
    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    // Process selected files
    function handleFiles(files) {
        if (files.length === 0) return;

        // Clear previous previews
        imagePreviews.innerHTML = '';
        
        // Show preview container
        previewContainer.classList.remove('hidden');

        // Process each file
        Array.from(files).forEach(file => {
            if (!file.type.match('image.*')) {
                alert('Mohon upload file gambar saja!');
                return;
            }

            if (file.size > 5 * 1024 * 1024) { // 5MB limit
                alert('Ukuran file terlalu besar! Maksimal 5MB.');
                return;
            }

            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'relative';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'image-preview';
                img.alt = 'Preview';
                
                const removeBtn = document.createElement('button');
                removeBtn.className = 'absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-gray-100 transition duration-300 ease-in-out';
                removeBtn.innerHTML = '<i class="fas fa-times text-red-500"></i>';
                removeBtn.addEventListener('click', function() {
                    previewDiv.remove();
                    if (imagePreviews.children.length === 0) {
                        previewContainer.classList.add('hidden');
                    }
                });
                
                previewDiv.appendChild(img);
                previewDiv.appendChild(removeBtn);
                imagePreviews.appendChild(previewDiv);
            };
            
            reader.readAsDataURL(file);
        });
    }

    // Upload form submission
    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form values
        const title = document.getElementById('image-title').value;
        const category = document.getElementById('image-category').value;
        const date = document.getElementById('image-date').value;
        
        // Validate form
        if (!title || !category || !date || imagePreviews.children.length === 0) {
            alert('Mohon lengkapi semua field dan upload minimal 1 foto!');
            return;
        }
        
        // In a real app, you would upload the files to a server here
        // For this demo, we'll just add them to our local gallery data
        
        // Get all preview images
        const previewImages = imagePreviews.querySelectorAll('img');
        
        previewImages.forEach((img, index) => {
            const newId = galleryData.length > 0 ? Math.max(...galleryData.map(item => item.id)) + 1 : 1;
            
            galleryData.push({
                id: newId + index,
                title: index === 0 ? title : `${title} (${index + 1})`,
                category,
                date,
                image: img.src
            });
        });
        
        // Reset form
        uploadForm.reset();
        imagePreviews.innerHTML = '';
        previewContainer.classList.add('hidden');
        
        // Refresh gallery display
        renderGallery();
        
        alert('Foto berhasil diupload!');
    });

    // Render gallery items
    function renderGallery(filter = 'all') {
        galleryItems.innerHTML = '';
        
        const filteredData = filter === 'all' 
            ? galleryData 
            : galleryData.filter(item => item.category === filter);
        
        if (filteredData.length === 0) {
            galleryItems.innerHTML = `
                <div class="col-span-3 text-center py-8">
                    <i class="fas fa-image text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Tidak ada foto dalam kategori ini</p>
                </div>
            `;
            return;
        }
        
        filteredData.forEach(item => {
            const formattedDate = new Date(item.date).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            
            const categoryLabel = getCategoryLabel(item.category);
            const categoryColor = getCategoryColor(item.category);
            
            const itemElement = document.createElement('div');
            itemElement.className = 'gallery-admin-item border border-gray-200 rounded-lg overflow-hidden';
            itemElement.innerHTML = `
                <div class="relative">
                    <img src="${item.image}" alt="${item.title}" class="w-full h-48 object-cover">
                    <div class="absolute top-2 right-2 flex space-x-1">
                        <button class="edit-btn bg-white p-2 rounded-full shadow-md hover:bg-gray-100 transition duration-300 ease-in-out" data-id="${item.id}">
                            <i class="fas fa-edit text-blue-500"></i>
                        </button>
                        <button class="delete-btn bg-white p-2 rounded-full shadow-md hover:bg-gray-100 transition duration-300 ease-in-out" data-id="${item.id}">
                            <i class="fas fa-trash text-red-500"></i>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-medium">${item.title}</h3>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-sm text-gray-500">${formattedDate}</span>
                        <span class="text-xs ${categoryColor} px-2 py-1 rounded-full">${categoryLabel}</span>
                    </div>
                </div>
            `;
            
            galleryItems.appendChild(itemElement);
        });
        
        // Add event listeners to edit and delete buttons
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.getAttribute('data-id'));
                openEditModal(id);
            });
        });
        
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.getAttribute('data-id'));
                openDeleteModal(id);
            });
        });
    }
    
    // Render news items with filtering
    function renderNewsItems(filter = 'all') {
        const newsItems = document.getElementById('news-items');
        if (!newsItems) return;
        
        newsItems.innerHTML = '';
        
        const filteredData = filter === 'all' 
            ? newsData 
            : newsData.filter(item => item.category === filter);
        
        if (filteredData.length === 0) {
            newsItems.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-newspaper text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500">Tidak ada berita dalam kategori ini</p>
                </div>
            `;
            return;
        }
        
        filteredData.forEach(item => {
            const date = new Date(item.date).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            
            const categoryLabel = {
                'pengumuman': 'Pengumuman',
                'kegiatan': 'Kegiatan',
                'prestasi': 'Prestasi'
            }[item.category];
            
            const categoryColor = {
                'pengumuman': 'bg-green-100 text-green-800',
                'kegiatan': 'bg-blue-100 text-blue-800',
                'prestasi': 'bg-purple-100 text-purple-800'
            }[item.category];
            
            const newsItem = document.createElement('div');
            newsItem.className = 'news-admin-item border border-gray-200 rounded-lg overflow-hidden flex flex-col md:flex-row';
            newsItem.innerHTML = `
                <div class="md:w-1/4">
                    <img src="${item.thumbnail}" alt="${item.title}" class="w-full h-48 md:h-full object-cover">
                </div>
                <div class="p-4 md:w-3/4 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start">
                            <h3 class="font-medium text-lg">${item.title}</h3>
                            <div class="flex space-x-1">
                                <button class="edit-news-btn bg-gray-100 p-2 rounded-full hover:bg-gray-200 transition duration-300 ease-in-out" data-id="${item.id}">
                                    <i class="fas fa-edit text-blue-500"></i>
                                </button>
                                <button class="delete-news-btn bg-gray-100 p-2 rounded-full hover:bg-gray-200 transition duration-300 ease-in-out" data-id="${item.id}">
                                    <i class="fas fa-trash text-red-500"></i>
                                </button>
                            </div>
                        </div>
                        <p class="text-gray-600 mt-2 line-clamp-3">${item.content}</p>
                    </div>
                    <div class="flex justify-between items-center mt-4">
                        <span class="text-sm text-gray-500">${date}</span>
                        <span class="text-xs ${categoryColor} px-2 py-1 rounded-full">${categoryLabel}</span>
                    </div>
                </div>
            `;
            
            newsItems.appendChild(newsItem);
            
            // Add event listeners to edit and delete buttons
            const editBtn = newsItem.querySelector('.edit-news-btn');
            const deleteBtn = newsItem.querySelector('.delete-news-btn');
            
            editBtn.addEventListener('click', () => openEditNewsModal(item.id));
            deleteBtn.addEventListener('click', () => openDeleteNewsModal(item.id));
        });
    }

    // Helper functions for category display
    function getCategoryLabel(category) {
        switch(category) {
            case 'kb': return 'KB';
            case 'tk': return 'TK';
            case 'spesial': return 'Acara Spesial';
            default: return '';
        }
    }
    
    function getCategoryColor(category) {
        switch(category) {
            case 'kb': return 'bg-green-100 text-green-800';
            case 'tk': return 'bg-blue-100 text-blue-800';
            case 'spesial': return 'bg-purple-100 text-purple-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    // Filter gallery by category
    filterCategory.addEventListener('change', function() {
        renderGallery(this.value);
    });

    // Refresh gallery
    refreshGallery.addEventListener('click', function() {
        renderGallery(filterCategory.value);
    });

    // Edit functionality
    function openEditModal(id) {
        const item = galleryData.find(item => item.id === id);
        if (!item) return;
        
        // Populate form fields
        document.getElementById('edit-id').value = item.id;
        document.getElementById('edit-title').value = item.title;
        document.getElementById('edit-category').value = item.category;
        document.getElementById('edit-date').value = item.date;
        document.getElementById('edit-preview').src = item.image;
        
        // Show modal
        editModal.classList.remove('hidden');
    }
    
    function openEditNewsModal(id) {
        const newsItem = newsData.find(item => item.id === id);
        if (!newsItem) return;
        
        // Create a modal for editing news if it doesn't exist
        let editNewsModal = document.getElementById('edit-news-modal');
        
        if (!editNewsModal) {
            // Create modal HTML
            const modalHTML = `
            <div id="edit-news-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Edit Berita</h3>
                        <button id="close-news-modal" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <form id="edit-news-form" class="space-y-6">
                        <div class="flex items-center justify-center mb-4">
                            <div class="relative inline-block">
                                <img id="edit-news-thumbnail" class="h-40 object-cover rounded-lg" alt="Thumbnail preview">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="edit-news-title" class="block text-sm font-medium text-gray-700 mb-1">Judul Berita</label>
                                <input type="text" id="edit-news-title" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                            </div>
                            
                            <div>
                                <label for="edit-news-category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                <select id="edit-news-category" name="category" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="pengumuman">Pengumuman</option>
                                    <option value="kegiatan">Kegiatan</option>
                                    <option value="prestasi">Prestasi</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="edit-news-date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publikasi</label>
                                <input type="date" id="edit-news-date" name="date" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="edit-news-content" class="block text-sm font-medium text-gray-700 mb-1">Konten Berita</label>
                                <textarea id="edit-news-content" name="content" rows="8" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" required></textarea>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" id="cancel-news-edit" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-300 ease-in-out">
                                Batal
                            </button>
                            <button type="submit" class="bg-brand-orange hover-brand-orange text-white font-medium py-2 px-6 rounded-md transition duration-300 ease-in-out">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            `;
            
            // Append modal to body
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            editNewsModal = document.getElementById('edit-news-modal');
            
            // Add event listeners
            document.getElementById('close-news-modal').addEventListener('click', () => {
                editNewsModal.classList.add('hidden');
            });
            
            document.getElementById('cancel-news-edit').addEventListener('click', () => {
                editNewsModal.classList.add('hidden');
            });
            
            document.getElementById('edit-news-form').addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Get form values
                const title = document.getElementById('edit-news-title').value;
                const category = document.getElementById('edit-news-category').value;
                const date = document.getElementById('edit-news-date').value;
                const content = document.getElementById('edit-news-content').value;
                
                // Validate form
                if (!title || !category || !date || !content) {
                    alert('Mohon lengkapi semua field');
                    return;
                }
                
                // Update news data
                const index = newsData.findIndex(item => item.id === id);
                if (index !== -1) {
                    newsData[index].title = title;
                    newsData[index].category = category;
                    newsData[index].date = date;
                    newsData[index].content = content;
                    
                    // Refresh news display
                    renderNewsItems(filterNewsCategory ? filterNewsCategory.value : 'all');
                    
                    // Hide modal
                    editNewsModal.classList.add('hidden');
                    
                    alert('Berita berhasil diperbarui!');
                }
            });
        }
        
        // Populate modal with news data
        document.getElementById('edit-news-thumbnail').src = newsItem.thumbnail;
        document.getElementById('edit-news-title').value = newsItem.title;
        document.getElementById('edit-news-category').value = newsItem.category;
        document.getElementById('edit-news-date').value = newsItem.date;
        document.getElementById('edit-news-content').value = newsItem.content;
        
        // Show modal
        editNewsModal.classList.remove('hidden');
    }

    // Close edit modal
    function closeEditModal() {
        editModal.classList.add('hidden');
    }

    closeModal.addEventListener('click', closeEditModal);
    cancelEdit.addEventListener('click', closeEditModal);

    // Handle edit form submission
    document.getElementById('edit-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = parseInt(document.getElementById('edit-id').value);
        const title = document.getElementById('edit-title').value;
        const category = document.getElementById('edit-category').value;
        const date = document.getElementById('edit-date').value;
        
        // Update gallery data
        const itemIndex = galleryData.findIndex(item => item.id === id);
        if (itemIndex !== -1) {
            galleryData[itemIndex].title = title;
            galleryData[itemIndex].category = category;
            galleryData[itemIndex].date = date;
            
            // Close modal and refresh gallery
            closeEditModal();
            renderGallery(filterCategory.value);
            
            alert('Foto berhasil diperbarui!');
        }
    });

    // Delete functionality
    let deleteItemId = null;

    function openDeleteModal(id) {
        deleteItemId = id;
        deleteModal.classList.remove('hidden');
    }
    
    function openDeleteNewsModal(id) {
        currentDeleteNewsId = id;
        
        // Create a modal for deleting news if it doesn't exist
        let deleteNewsModal = document.getElementById('delete-news-modal');
        
        if (!deleteNewsModal) {
            // Create modal HTML
            const modalHTML = `
            <div id="delete-news-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                    <div class="text-center mb-6">
                        <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                        <h3 class="text-xl font-semibold">Konfirmasi Hapus</h3>
                        <p class="text-gray-600 mt-2">Apakah Anda yakin ingin menghapus berita ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    
                    <div class="flex justify-center space-x-4">
                        <button id="cancel-news-delete" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-300 ease-in-out">
                            Batal
                        </button>
                        <button id="confirm-news-delete" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md transition duration-300 ease-in-out">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
            `;
            
            // Append modal to body
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            deleteNewsModal = document.getElementById('delete-news-modal');
            
            // Add event listeners
            document.getElementById('cancel-news-delete').addEventListener('click', () => {
                deleteNewsModal.classList.add('hidden');
            });
            
            document.getElementById('confirm-news-delete').addEventListener('click', () => {
                // Delete news item
                const index = newsData.findIndex(item => item.id === currentDeleteNewsId);
                if (index !== -1) {
                    newsData.splice(index, 1);
                    
                    // Refresh news display
                    renderNewsItems(filterNewsCategory ? filterNewsCategory.value : 'all');
                    
                    // Hide modal
                    deleteNewsModal.classList.add('hidden');
                    
                    alert('Berita berhasil dihapus!');
                }
            });
        }
        
        // Show modal
        deleteNewsModal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
        deleteItemId = null;
    }

    cancelDelete.addEventListener('click', closeDeleteModal);

    confirmDelete.addEventListener('click', function() {
        if (deleteItemId === null) return;
        
        // Remove item from gallery data
        galleryData = galleryData.filter(item => item.id !== deleteItemId);
        
        // Close modal and refresh gallery
        closeDeleteModal();
        renderGallery(filterCategory.value);
        
        alert('Foto berhasil dihapus!');
    });
    
    function openDeleteNewsModal(id) {
        currentDeleteNewsId = id;
        
        // Create a modal for deleting news if it doesn't exist
        let deleteNewsModal = document.getElementById('delete-news-modal');
        
        if (!deleteNewsModal) {
            // Create modal HTML
            const modalHTML = `
            <div id="delete-news-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                    <div class="text-center mb-6">
                        <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                        <h3 class="text-xl font-semibold">Konfirmasi Hapus</h3>
                        <p class="text-gray-600 mt-2">Apakah Anda yakin ingin menghapus berita ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    
                    <div class="flex justify-center space-x-4">
                        <button id="cancel-news-delete" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-300 ease-in-out">
                            Batal
                        </button>
                        <button id="confirm-news-delete" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md transition duration-300 ease-in-out">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
            `;
            
            // Append modal to body
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            deleteNewsModal = document.getElementById('delete-news-modal');
            
            // Add event listeners
            document.getElementById('cancel-news-delete').addEventListener('click', () => {
                deleteNewsModal.classList.add('hidden');
            });
            
            document.getElementById('confirm-news-delete').addEventListener('click', () => {
                // Delete news item
                const index = newsData.findIndex(item => item.id === currentDeleteNewsId);
                if (index !== -1) {
                    newsData.splice(index, 1);
                    
                    // Refresh news display
                    renderNewsItems(filterNewsCategory ? filterNewsCategory.value : 'all');
                    
                    // Hide modal
                    deleteNewsModal.classList.add('hidden');
                    
                    alert('Berita berhasil dihapus!');
                }
            });
        }
        
        // Show modal
        deleteNewsModal.classList.remove('hidden');
    }

    // News thumbnail handling
    const newsThumbnailArea = document.getElementById('news-thumbnail-area');
    const newsThumbnailInput = document.getElementById('news-thumbnail-input');
    const newsThumbnailPreview = document.getElementById('news-thumbnail-preview');
    const newsThumbnailImg = document.getElementById('news-thumbnail-img');
    const removeThumbnail = document.getElementById('remove-thumbnail');
    const newsForm = document.getElementById('news-form');
    const filterNewsCategory = document.getElementById('filter-news-category');
    const refreshNews = document.getElementById('refresh-news');
    
    // News thumbnail drag and drop functionality
    if (newsThumbnailArea && newsThumbnailInput) {
        newsThumbnailArea.addEventListener('click', () => {
            newsThumbnailInput.click();
        });
        
        newsThumbnailArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            newsThumbnailArea.classList.add('active');
        });
        
        newsThumbnailArea.addEventListener('dragleave', () => {
            newsThumbnailArea.classList.remove('active');
        });
        
        newsThumbnailArea.addEventListener('drop', (e) => {
            e.preventDefault();
            newsThumbnailArea.classList.remove('active');
            
            const file = e.dataTransfer.files[0];
            if (file) {
                handleNewsThumbnail(file);
            }
        });
        
        newsThumbnailInput.addEventListener('change', () => {
            const file = newsThumbnailInput.files[0];
            if (file) {
                handleNewsThumbnail(file);
            }
        });
        
        if (removeThumbnail) {
            removeThumbnail.addEventListener('click', () => {
                newsThumbnailImg.src = '';
                newsThumbnailPreview.classList.add('hidden');
                newsThumbnailInput.value = '';
            });
        }
    }
    
    // Handle news thumbnail upload
    function handleNewsThumbnail(file) {
        // Check if file is an image
        if (!file.type.match('image.*')) {
            alert('Mohon upload file gambar (JPG, PNG, atau WEBP)');
            return;
        }
        
        // Check file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            return;
        }
        
        // Display thumbnail preview
        const reader = new FileReader();
        reader.onload = function(e) {
            newsThumbnailImg.src = e.target.result;
            newsThumbnailPreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
    
    // News form submission
    if (newsForm) {
        newsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const title = document.getElementById('news-title').value;
            const category = document.getElementById('news-category').value;
            const date = document.getElementById('news-date').value;
            const content = document.getElementById('news-content').value;
            
            // Validate form
            if (!title || !category || !date || !content) {
                alert('Mohon lengkapi semua field');
                return;
            }
            
            if (newsThumbnailImg.src === '') {
                alert('Mohon upload thumbnail berita');
                return;
            }
            
            // In a real app, you would send this data to a server
            // For now, we'll just add it to our local data and refresh the display
            const newNews = {
                id: newsData.length + 1,
                thumbnail: newsThumbnailImg.src,
                title: title,
                content: content,
                date: date,
                category: category
            };
            
            newsData.unshift(newNews);
            renderNewsItems(filterNewsCategory ? filterNewsCategory.value : 'all');
            
            // Reset form
            newsForm.reset();
            newsThumbnailImg.src = '';
            newsThumbnailPreview.classList.add('hidden');
            
            alert('Berita berhasil dipublikasikan!');
        });
    }
    
    // Initialize the page
    function init() {
        // Render gallery items
        renderGallery();
        
        // Render news items
        renderNewsItems();
        
        // Add event listener for news filter
        if (filterNewsCategory) {
            filterNewsCategory.addEventListener('change', function() {
                renderNewsItems(this.value);
            });
        }
        
        // Add event listener for news refresh button
        if (refreshNews) {
            refreshNews.addEventListener('click', function() {
                if (filterNewsCategory) {
                    renderNewsItems(filterNewsCategory.value);
                } else {
                    renderNewsItems();
                }
            });
        }
    }
    
    // Call init function when DOM is loaded
    init();
});