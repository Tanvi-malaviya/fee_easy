@if(isset($isEditable) && $isEditable)
    <!-- Customizer Toolbar -->
    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[9999] bg-slate-950/90 border border-white/10 text-white px-6 py-4 rounded-3xl shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] flex items-center gap-6 backdrop-blur-xl select-none w-auto max-w-[90vw] justify-between transition-all duration-300 hover:border-white/20">
        <div class="flex flex-col gap-0.5">
            <span class="text-[9px] font-black text-[#ff6b00] uppercase tracking-[0.2em]">Live Customizer</span>
            <span class="text-[10px] text-slate-400 font-medium">Click any highlighted element to edit inline, then click save.</span>
        </div>
        <button id="save-customizer-btn" onclick="saveWebsiteCustomizer()" class="bg-[#ff6b00] hover:bg-[#e05f00] text-white px-5 py-2.5 rounded-2xl font-bold text-xs uppercase tracking-wider transition duration-300 hover:scale-105 active:scale-95 shadow-lg shadow-[#ff6b00]/20 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
            </svg>
            Save Changes
        </button>
    </div>

    <!-- Toast Notification Container -->
    <div id="customizer-toast-container" class="fixed top-6 right-6 z-[10000] flex flex-col gap-3 pointer-events-none"></div>

    <!-- Customizer Styles -->
    <style>
        .dynamic-editable {
            outline: none !important;
            transition: all 0.2s ease-in-out;
            position: relative;
        }
        .dynamic-editable:hover {
            background-color: rgba(255, 107, 0, 0.08) !important;
            box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.3) !important;
            border-radius: 6px;
            cursor: text;
        }
        .dynamic-editable:focus {
            background-color: rgba(255, 107, 0, 0.12) !important;
            box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.6) !important;
            border-radius: 6px;
        }
    </style>

    <!-- Customizer JavaScript -->
    <script>
        function showCustomizerToast(title, message, type = 'success') {
            const container = document.getElementById('customizer-toast-container');
            const toast = document.createElement('div');
            
            let borderColor = 'border-emerald-500/30';
            if (type === 'error') {
                borderColor = 'border-red-500/30';
            } else if (type === 'info') {
                borderColor = 'border-[#ff6b00]/30';
            }
            
            toast.className = `transform translate-x-12 opacity-0 transition-all duration-500 ease-out flex items-center gap-3.5 bg-slate-950/95 border ${borderColor} text-white px-5 py-4 rounded-2xl shadow-2xl backdrop-blur-xl pointer-events-auto min-w-[280px]`;
            
            let iconSvg = '';
            if (type === 'success') {
                iconSvg = `<div class="h-8 w-8 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                             </svg>
                           </div>`;
            } else if (type === 'error') {
                iconSvg = `<div class="h-8 w-8 rounded-xl bg-red-500/10 border border-red-500/30 flex items-center justify-center text-red-400">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                             </svg>
                           </div>`;
            } else if (type === 'info') {
                iconSvg = `<div class="h-8 w-8 rounded-xl bg-[#ff6b00]/10 border border-[#ff6b00]/30 flex items-center justify-center text-[#ff6b00]">
                             <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                               <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                               <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                             </svg>
                           </div>`;
            }
                   
            toast.innerHTML = `
                ${iconSvg}
                <div class="flex flex-col gap-0.5 flex-1">
                    <span class="text-xs font-black tracking-wide">${title}</span>
                    <span class="text-[10px] text-slate-400 font-medium">${message}</span>
                </div>
            `;
            
            container.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => {
                toast.classList.remove('translate-x-12', 'opacity-0');
            }, 50);
            
            // Remove after 3.5s
            let removeTimeout = setTimeout(() => {
                toast.classList.add('translate-x-12', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }, 3500);

            toast.dismiss = function() {
                clearTimeout(removeTimeout);
                toast.classList.add('translate-x-12', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 500);
            };

            return toast;
        }

        function saveWebsiteCustomizer() {
            const btn = document.getElementById('save-customizer-btn');
            btn.disabled = true;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = `
                <svg class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Saving...
            `;
            
            const settings = {};
            document.querySelectorAll('.dynamic-editable').forEach(el => {
                const key = el.getAttribute('data-key');
                if (key) {
                    settings[key] = el.innerHTML.trim();
                }
            });
            document.querySelectorAll('.dynamic-editable-img').forEach(el => {
                const key = el.getAttribute('data-key');
                if (key) {
                    settings[key] = el.value || el.getAttribute('src') || el.getAttribute('data-src') || '';
                }
            });
            
            fetch('{{ route("institute.profile.website-settings.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ settings: settings })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                if (data.status === 'success') {
                    showCustomizerToast('Changes Saved', 'Your website content has been successfully updated.', 'success');
                } else {
                    showCustomizerToast('Error Occurred', data.message || 'Could not save modifications.', 'error');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                console.error(err);
                showCustomizerToast('Network Failure', 'Failed to communicate with server.', 'error');
            })
        }

        let activeUploadToast = null;

        window.uploadCustomizerImage = function(event, callback) {
            const file = event.target.files[0];
            if (!file) return;
            
            const targetEl = event.target;
            const slideIndex = targetEl.getAttribute('data-slide-index');
            
            const formData = new FormData();
            formData.append('image', file);
            
            if (activeUploadToast) {
                activeUploadToast.dismiss();
            }
            activeUploadToast = showCustomizerToast('Uploading Image', 'Please wait while the image is being uploaded.', 'info');
            
            fetch('{{ route("institute.profile.website-settings.upload-image") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (activeUploadToast) {
                    activeUploadToast.dismiss();
                    activeUploadToast = null;
                }

                if (data.status === 'success') {
                    showCustomizerToast('Upload Complete', 'Image uploaded successfully.', 'success');
                    
                    // 1. Direct Alpine update using Alpine.$data to prevent scoping/closure errors
                    if (slideIndex !== null && typeof Alpine !== 'undefined') {
                        const alpineData = Alpine.$data(targetEl);
                        if (alpineData && alpineData.slides && alpineData.slides[slideIndex]) {
                            alpineData.slides[slideIndex].img = data.url;
                        }
                    }
                    
                    // 2. Fallback callback execution
                    if (callback && typeof callback === 'function') {
                        try {
                            callback(data.url);
                        } catch (e) {
                            console.warn("Callback error handled:", e);
                        }
                    }
                } else {
                    showCustomizerToast('Upload Failed', data.message || 'Could not upload image.', 'error');
                }
            })
            .catch(err => {
                if (activeUploadToast) {
                    activeUploadToast.dismiss();
                    activeUploadToast = null;
                }
                console.error(err);
                showCustomizerToast('Network Failure', 'Failed to communicate with upload server.', 'error');
            });
        };

        let activeGalleryUploadToast = null;

        window.uploadGalleryImage = function(event, itemIndex) {
            const file = event.target.files[0];
            if (!file) return;
            
            const targetEl = event.target;
            const formData = new FormData();
            formData.append('image', file);
            
            if (activeGalleryUploadToast) {
                activeGalleryUploadToast.dismiss();
            }
            activeGalleryUploadToast = showCustomizerToast('Uploading Image', 'Please wait while the image is being uploaded.', 'info');
            
            fetch('{{ route("institute.profile.website-settings.upload-image") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (activeGalleryUploadToast) {
                    activeGalleryUploadToast.dismiss();
                    activeGalleryUploadToast = null;
                }

                if (data.status === 'success') {
                    showCustomizerToast('Upload Complete', 'Image uploaded successfully.', 'success');
                    
                    if (itemIndex !== null && typeof Alpine !== 'undefined') {
                        const alpineData = Alpine.$data(targetEl);
                        if (alpineData && alpineData.items && alpineData.items[itemIndex]) {
                            alpineData.items[itemIndex].img = data.url;
                        }
                    }
                } else {
                    showCustomizerToast('Upload Failed', data.message || 'Could not upload image.', 'error');
                }
            })
            .catch(err => {
                if (activeGalleryUploadToast) {
                    activeGalleryUploadToast.dismiss();
                    activeGalleryUploadToast = null;
                }
                console.error(err);
                showCustomizerToast('Network Failure', 'Failed to communicate with upload server.', 'error');
            });
        };
    </script>
@endif
