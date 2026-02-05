@extends('layouts.dashboard')

@section('title', 'Add Chapter')
@section('page-title', 'Add New Chapter')

@push('styles')
<style>
    .dropzone-active {
        border-style: solid;
        --tw-border-opacity: 1;
        border-color: rgb(59 130 246 / var(--tw-border-opacity));
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        --tw-ring-color: rgb(59 130 246 / .5);
    }
</style>
@endpush

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">
            For Comic: <a href="{{ route('dashboard.chapters.index', $comic->comic_id) }}" class="text-sky-400 hover:text-sky-300 transition">{{ $comic->title }}</a>
        </h2>
        <a href="{{ route('dashboard.chapters.index', $comic->comic_id) }}" class="px-4 py-2 bg-neutral-700 hover:bg-neutral-600 text-white font-semibold rounded-lg shadow-md transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to Chapters
        </a>
    </div>

    <form action="{{ route('dashboard.chapters.store', $comic->comic_id) }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="p-6 bg-neutral-800/50 border border-neutral-700 rounded-xl">
                    <h3 class="text-xl font-bold text-white mb-6 border-b border-neutral-700 pb-4">1. Chapter Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="chapter_number" class="block text-sm font-medium text-neutral-300 mb-2">Chapter Number *</label>
                            <input type="number" id="chapter_number" name="chapter_number" value="{{ old('chapter_number', $nextChapterNumber) }}" required min="0" step="0.1"
                                   class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('chapter_number') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                            @error('chapter_number')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-neutral-300 mb-2">Chapter Title</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}"
                                   class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('title') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500"
                                   placeholder="e.g., The Adventure Begins">
                            @error('title')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="mt-6">
                        <label for="release_date" class="block text-sm font-medium text-neutral-300 mb-2">Release Date</label>
                        <input type="date" id="release_date" name="release_date" value="{{ old('release_date', date('Y-m-d')) }}"
                               class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('release_date') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500"
                               style="color-scheme: dark;">
                        @error('release_date')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="p-6 bg-neutral-800/50 border border-neutral-700 rounded-xl">
                    <h3 class="text-xl font-bold text-white mb-6 border-b border-neutral-700 pb-4">2. Upload Pages</h3>
                    <input type="hidden" name="upload_id" id="upload_id">
                    <input type="file" id="page-input" accept="image/jpeg,image/png,image/webp" multiple class="hidden">
                    
                    <!-- Dropzone -->
                    <label id="dropzone" for="page-input" class="w-full cursor-pointer p-8 border-2 border-dashed border-neutral-600 hover:border-sky-500 rounded-lg flex flex-col items-center justify-center transition">
                        <div id="dropzone-text">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-cloud-upload-alt fa-3x text-neutral-400"></i>
                                <span class="mt-4 text-lg font-semibold text-white">Drag & drop pages here</span>
                                <span class="text-neutral-400">or click to select files</span>
                                <span class="text-xs text-neutral-500 mt-2">Pages will be ordered by filename (e.g., 01.jpg, 02.jpg)</span>
                            </div>
                        </div>
                    </label>

                    <!-- Upload Progress -->
                    <div id="upload-progress-container" class="mt-4 hidden">
                        <div class="flex justify-between mb-1">
                            <span id="upload-status-text" class="text-base font-medium text-sky-400">Uploading...</span>
                            <span id="upload-progress-percentage" class="text-sm font-medium text-sky-400">0%</span>
                        </div>
                        <div class="w-full bg-neutral-600 rounded-full h-2.5">
                            <div id="upload-progress-bar" class="bg-sky-500 h-2.5 rounded-full" style="width: 0%"></div>
                        </div>
                        <div id="file-list" class="mt-4 text-sm text-neutral-300 space-y-1 max-h-48 overflow-y-auto"></div>
                    </div>
                    @error('upload_id')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                 <div class="p-6 bg-neutral-800/50 border border-neutral-700 rounded-xl">
                    <h3 class="text-xl font-bold text-white mb-4 border-b border-neutral-700 pb-3 flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> 3. Finish</h3>
                    <p class="text-neutral-300 text-sm mb-4">Once all pages have been successfully uploaded, you can create the chapter.</p>
                    <div class="flex flex-col gap-4">
                        <button type="submit" id="submit-button" disabled class="w-full px-6 py-3 bg-green-600 hover:bg-green-500 text-white font-bold rounded-lg shadow-lg transition flex items-center justify-center gap-2 disabled:bg-neutral-600 disabled:cursor-not-allowed">
                            <i class="fas fa-save"></i> Create Chapter
                        </button>
                        <a href="{{ route('dashboard.chapters.index', $comic->comic_id) }}" class="w-full text-center px-6 py-3 bg-neutral-600 hover:bg-neutral-500 text-white font-bold rounded-lg transition">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const CHUNK_SIZE = 5; // Upload 5 files at a time
    const form = document.querySelector('form');
    const dropzone = document.getElementById('dropzone');
    const pageInput = document.getElementById('page-input');
    const uploadIdInput = document.getElementById('upload_id');
    const progressContainer = document.getElementById('upload-progress-container');
    const progressBar = document.getElementById('upload-progress-bar');
    const statusText = document.getElementById('upload-status-text');
    const progressPercentage = document.getElementById('upload-progress-percentage');
    const fileListDisplay = document.getElementById('file-list');
    const submitButton = document.getElementById('submit-button');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const uploadUrl = "{{ route('dashboard.chapters.uploadChunk') }}";

    let fileQueue = [];
    let isUploading = false;

    // 1. Initialize
    function initializeUploader() {
        uploadIdInput.value = `upload-${Date.now()}-${Math.random().toString(36).substring(2, 9)}`;
    }

    // 2. Event Listeners
    dropzone.addEventListener('dragenter', (e) => {
        e.preventDefault();
        if (isUploading) return;
        dropzone.classList.add('dropzone-active');
    });

    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault(); // This is crucial to allow dropping
    });

    dropzone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dropzone-active');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        if (isUploading) return;
        dropzone.classList.remove('dropzone-active');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFiles(files);
        }
    });

    pageInput.addEventListener('change', (e) => {
        if (isUploading) return;
        const files = e.target.files;
        if (files.length > 0) {
            handleFiles(files);
        }
    });
    
    // Prevent form submission while uploading
    form.addEventListener('submit', (e) => {
        if(isUploading) {
            e.preventDefault();
            alert('Please wait for the file uploads to complete.');
        }
    });

    // 3. File Handling
    function handleFiles(files) {
        const fileArray = Array.from(files);
        fileArray.sort((a, b) => a.name.localeCompare(b.name, undefined, { numeric: true, sensitivity: 'base' }));
        fileQueue = [...fileQueue, ...fileArray];
        
        progressContainer.classList.remove('hidden');
        dropzone.classList.add('hidden');

        updateFileList();
        
        if (!isUploading) {
            processQueue();
        }
    }

    function updateFileList() {
        fileListDisplay.innerHTML = '';
        fileQueue.forEach((file, index) => {
            const fileEl = document.createElement('div');
            fileEl.className = 'flex justify-between items-center text-xs';
            fileEl.innerHTML = `
                <span>${file.name}</span>
                <span id="file-status-${index}">Queued</span>
            `;
            fileListDisplay.appendChild(fileEl);
        });
    }

    // 4. Upload Logic
    async function processQueue() {
        isUploading = true;
        submitButton.disabled = true;
        
        let totalUploaded = 0;
        const totalFiles = fileQueue.length;

        for (let i = 0; i < totalFiles; i += CHUNK_SIZE) {
            const chunk = fileQueue.slice(i, i + CHUNK_SIZE);
            const chunkNumber = (i / CHUNK_SIZE) + 1;
            const totalChunks = Math.ceil(totalFiles / CHUNK_SIZE);
            
            statusText.textContent = `Uploading chunk ${chunkNumber} of ${totalChunks}...`;

            const uploadPromises = chunk.map((file, index) => {
                const fileIndexInQueue = i + index;
                const fileStatusEl = document.getElementById(`file-status-${fileIndexInQueue}`);
                fileStatusEl.textContent = 'Uploading...';
                fileStatusEl.className = 'text-yellow-400';
                
                return uploadFile(file)
                    .then(() => {
                        totalUploaded++;
                        const overallProgress = Math.round((totalUploaded / totalFiles) * 100);
                        progressBar.style.width = `${overallProgress}%`;
                        progressPercentage.textContent = `${overallProgress}%`;
                        fileStatusEl.textContent = 'Done';
                        fileStatusEl.className = 'text-green-400';
                    })
                    .catch(err => {
                        fileStatusEl.textContent = 'Error';
                        fileStatusEl.className = 'text-red-400';
                        console.error('Upload failed for', file.name, err);
                        // Optionally, stop the queue on first error
                        throw new Error(`Upload failed for ${file.name}`);
                    });
            });

            try {
                await Promise.all(uploadPromises);
            } catch (error) {
                statusText.textContent = 'An error occurred. Some files failed to upload.';
                statusText.classList.remove('text-sky-400');
                statusText.classList.add('text-red-500');
                isUploading = false;
                // Don't enable submit button if some files failed
                return;
            }
        }
        
        statusText.textContent = 'All pages uploaded successfully!';
        statusText.classList.remove('text-sky-400');
        statusText.classList.add('text-green-500');
        isUploading = false;
        submitButton.disabled = false;
    }

    async function uploadFile(file) {
        const formData = new FormData();
        formData.append('page', file);
        formData.append('filename', file.name);
        formData.append('upload_id', uploadIdInput.value);

        return fetch(uploadUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        }).then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        });
    }

    // Initialize on load
    initializeUploader();
});
</script>
@endpush
