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

    // Initial gallery render
    renderGallery();
});