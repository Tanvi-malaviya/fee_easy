@extends('layouts.institute')

@section('content')
    <div class="max-w-[1600px] mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-3">
            <div>
                <h1 class="text-xl font-semibold text-slate-800 tracking-tight">Messages</h1>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">Connect with students and parents</p>
            </div>

        </div>

        <!-- Main Chat Interface -->
        <div class="grid grid-cols-12 gap-2 h-[calc(100vh-13.5rem)] min-h-[441px]">

            <!-- Sidebar: Conversation List -->
            <div
                class="col-span-12 lg:col-span-4 xl:col-span-3 flex flex-col bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <!-- Search & New Chat -->
                <div class="p-4 border-b border-slate-50 space-y-3">
                    <div class="relative">
                        <input type="text" id="chat-search" placeholder="Search conversations..."
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-primary/20 transition-all outline-none text-slate-600">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-slate-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button onclick="openNewChatModal()"
                        class="w-full py-2 bg-orange-50 text-primary rounded-xl text-xs font-bold hover:bg-orange-100 transition-all flex items-center justify-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        New Chat
                    </button>
                </div>

                <!-- List -->
                <div id="conversation-list" class="flex-1 overflow-y-auto custom-scrollbar">
                    <div class="p-8 text-center text-slate-400 text-xs font-medium">Loading conversations...</div>
                </div>
            </div>

            <!-- Main Chat Area -->
            <div id="chat-area"
                class="col-span-12 lg:col-span-8 xl:col-span-9 flex flex-col bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <!-- Empty State -->
                <div id="chat-empty-state" class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                    <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Select a conversation</h3>
                    <p class="text-xs text-slate-500 max-w-[280px] leading-relaxed">Choose a student or staff member from
                        the sidebar to start messaging in real-time.</p>
                </div>

                <!-- Active Chat -->
                <div id="active-chat" class="hidden flex-1 flex flex-col overflow-hidden">
                    <!-- Chat Header -->
                    <div
                        class="p-4 border-b border-slate-50 flex items-center justify-between bg-white/50 backdrop-blur-sm sticky top-0 z-10">
                        <div class="flex items-center gap-4">
                            <div id="active-user-avatar" class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center font-bold text-primary border border-primary/20 overflow-hidden flex-shrink-0">
                                ?
                            </div>
                            <div>
                                <h3 id="active-user-name" class="text-sm font-bold text-slate-800">User Name</h3>

                            </div>
                        </div>
                        <div class="flex items-center gap-2">

                            <button onclick="clearCurrentConversation()" title="Delete entire conversation"
                                class="h-9 w-9 flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Messages Container -->
                    <div id="messages-container"
                        class="flex-1 overflow-y-auto p-4 space-y-2.5 bg-slate-50/30 custom-scrollbar scroll-smooth">
                        <!-- Messages will be injected here -->
                    </div>

                    <!-- Input Area -->
                    <div class="p-4 border-t border-slate-50 bg-white relative">
                        <!-- Attachment Dropdown Menu -->
                        <div id="attachment-menu"
                            class="hidden absolute bottom-20 left-4 bg-white border border-slate-100 rounded-3xl p-3 shadow-2xl flex flex-col gap-2 z-50 w-52 transform scale-95 opacity-0 transition-all duration-200 origin-bottom-left">
                            <button type="button" onclick="triggerFileInput('image-input-file')"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-2xl hover:bg-orange-50 text-slate-700 hover:text-primary transition-all text-xs font-bold">
                                <span class="p-1.5 bg-orange-100/50 rounded-xl text-primary"><svg class="w-4 h-4"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg></span>
                                Share Image
                            </button>
                            <button type="button" onclick="triggerFileInput('video-input-file')"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-2xl hover:bg-orange-50 text-slate-700 hover:text-primary transition-all text-xs font-bold">
                                <span class="p-1.5 bg-orange-100/50 rounded-xl text-primary"><svg class="w-4 h-4"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg></span>
                                Share Video
                            </button>
                            <button type="button" onclick="triggerFileInput('doc-input-file')"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-2xl hover:bg-orange-50 text-slate-700 hover:text-primary transition-all text-xs font-bold">
                                <span class="p-1.5 bg-orange-100/50 rounded-xl text-primary"><svg class="w-4 h-4"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg></span>
                                Share Document
                            </button>
                            <button type="button" onclick="triggerFileInput('audio-input-file')"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-2xl hover:bg-orange-50 text-slate-700 hover:text-primary transition-all text-xs font-bold">
                                <span class="p-1.5 bg-orange-100/50 rounded-xl text-primary"><svg class="w-4 h-4"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                    </svg></span>
                                Share Audio
                            </button>
                        </div>

                        <!-- Hidden Inputs for Files -->
                        <input type="file" id="image-input-file" accept="image/*" class="hidden"
                            onchange="handleFileUpload(this, 'image')">
                        <input type="file" id="video-input-file" accept="video/*" class="hidden"
                            onchange="handleFileUpload(this, 'video')">
                        <input type="file" id="doc-input-file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt"
                            class="hidden" onchange="handleFileUpload(this, 'document')">
                        <input type="file" id="audio-input-file" accept="audio/*" class="hidden"
                            onchange="handleFileUpload(this, 'audio')">

                        <form id="message-form" onsubmit="handleSendMessage(event)"
                            class="flex items-center gap-3 bg-slate-50 rounded-2xl p-2 pr-3">
                            <button type="button" onclick="toggleAttachmentMenu(event)"
                                class="h-10 w-10 flex items-center justify-center text-slate-400 hover:text-primary transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </button>
                            <input type="text" id="message-input" placeholder="Type your message here..."
                                class="flex-1 bg-transparent border-none focus:ring-0 text-sm py-2 text-slate-600 outline-none">
                            <button type="submit" id="send-btn"
                                class="h-10 w-10 bg-primary text-white rounded-xl flex items-center justify-center shadow-lg shadow-orange-900/10 hover:scale-105 active:scale-95 transition-all">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Chat Modal -->
    <div id="new-chat-modal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[200] hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl scale-95 opacity-0 transition-all duration-300"
            id="new-chat-content">
            <div class="p-4 py-3 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">Start New Chat</h3>
                <button onclick="closeNewChatModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-3 bg-slate-50">
                <div class="relative">
                    <input type="text" id="contact-search" placeholder="Search students or staff..."
                        class="w-full pl-9 pr-4 py-2 bg-white border border-slate-100 rounded-xl text-xs focus:ring-2 focus:ring-primary/20 transition-all outline-none">
                    <svg class="w-3.5 h-3.5 absolute left-3 top-3 text-slate-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <div id="contact-list" class="max-h-[300px] overflow-y-auto custom-scrollbar divide-y divide-slate-50">
                <!-- Contacts will be injected here -->
            </div>
        </div>
    </div>
    <!-- Share Location Modal -->
    <div id="location-modal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[200] hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl scale-95 opacity-0 transition-all duration-300"
            id="location-modal-content">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800">Share Location</h3>
                <button type="button" onclick="closeLocationModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form onsubmit="handleShareLocation(event)" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Location Label /
                        Address</label>
                    <input type="text" id="loc-label" placeholder="e.g. Science Lab, Main Campus" required
                        class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 outline-none text-slate-600">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Latitude</label>
                        <input type="number" step="any" id="loc-lat" placeholder="21.1702" required
                            class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 outline-none text-slate-600">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Longitude</label>
                        <input type="number" step="any" id="loc-lng" placeholder="72.8311" required
                            class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 outline-none text-slate-600">
                    </div>
                </div>
                <button type="submit"
                    class="w-full py-3 bg-primary text-white rounded-2xl text-sm font-bold shadow-lg shadow-orange-900/10 hover:translate-y-[-1px] transition-all">
                    Share Location
                </button>
            </form>
        </div>
    </div>

    <!-- Share Contact Modal -->
    <div id="contact-modal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[200] hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl scale-95 opacity-0 transition-all duration-300"
            id="contact-modal-content">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800">Share Contact</h3>
                <button type="button" onclick="closeContactModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form onsubmit="handleShareContact(event)" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Contact Name</label>
                    <input type="text" id="con-name" placeholder="e.g. John Doe" required
                        class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 outline-none text-slate-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Phone Number</label>
                    <input type="tel" id="con-phone" placeholder="e.g. +91 9876543210" required
                        class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 outline-none text-slate-600">
                </div>
                <button type="submit"
                    class="w-full py-3 bg-primary text-white rounded-2xl text-sm font-bold shadow-lg shadow-orange-900/10 hover:translate-y-[-1px] transition-all">
                    Share Contact
                </button>
            </form>
        </div>
    </div>

    <!-- Delete Conversation Confirmation Modal -->
    <div id="delete-confirm-modal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[200] hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl scale-95 opacity-0 transition-all duration-300 border-t-4 border-primary"
            id="delete-confirm-modal-content">
            <div class="p-4 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800">Delete Conversation</h3>
                <button type="button" onclick="closeDeleteConfirmModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4 space-y-4">
                <p class="text-xs text-slate-600 leading-relaxed">
                    Are you sure you want to delete your entire conversation with <span id="delete-confirm-user-name"
                        class="font-bold text-slate-800"></span>? This action cannot be undone.
                </p>
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" onclick="closeDeleteConfirmModal()"
                        class="flex-1 py-2 bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 rounded-xl text-xs font-bold transition-all">
                        Cancel
                    </button>
                    <button type="button" onclick="confirmClearConversation()"
                        class="flex-1 py-2 bg-primary text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-orange-500/10">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>

        <script>
            const INSTITUTE_ID = '{{ auth('institute')->id() }}';
            const CSRF_TOKEN = '{{ csrf_token() }}';
            @php
                $instLogo = auth('institute')->user()?->logo;
                $instLogoUrl = $instLogo ? url('storage/' . $instLogo) : null;
                $instName = auth('institute')->user()?->institute_name ?? auth('institute')->user()?->name ?? 'Institute';
            @endphp
            const INSTITUTE_LOGO = '{{ $instLogoUrl }}';
            const INSTITUTE_NAME = '{{ $instName }}';

            // Initialize Echo
            window.Pusher = Pusher;

            const isLocal = {{ config('app.env') === 'local' ? 'true' : 'false' }};

            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: '{{ config('broadcasting.connections.pusher.key') }}',
                cluster: '{{ config('broadcasting.connections.pusher.options.cluster', 'mt1') }}',
                wsHost: window.location.hostname,
                wsPort: isLocal ? {{ config('broadcasting.connections.pusher.options.port', 6001) }} : (window.location.protocol === 'https:' ? 443 : 80),
                wssPort: isLocal ? {{ config('broadcasting.connections.pusher.options.port', 6001) }} : (window.location.protocol === 'https:' ? 443 : 80),
                wsPath: isLocal ? '' : '/app',
                forceTLS: isLocal ? {{ config('broadcasting.connections.pusher.options.scheme', 'http') === 'https' ? 'true' : 'false' }} : (window.location.protocol === 'https:'),
                encrypted: isLocal ? {{ config('broadcasting.connections.pusher.options.scheme', 'http') === 'https' ? 'true' : 'false' }} : (window.location.protocol === 'https:'),
                disableStats: true,
                enabledTransports: ['ws', 'wss'],
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                }
            });

            // Logging connection status for debugging
            window.Echo.connector.pusher.connection.bind('connected', () => console.log('✅ Real-time chat connected!'));
            window.Echo.connector.pusher.connection.bind('error', (err) => console.error('❌ Real-time connection error:', err));

            let currentConversations = [];
            let activeConversation = null;
            let messages = [];
            const currentUserId = {{ auth('institute')->id() }};

            // Listen for search input on conversation list
            document.getElementById('chat-search').addEventListener('input', function (e) {
                const searchTerm = e.target.value.toLowerCase().trim();
                renderConversationList(searchTerm);
            });

            let allContacts = [];
            // Listen for search input on contact list inside new chat modal
            document.getElementById('contact-search').addEventListener('input', function (e) {
                const searchTerm = e.target.value.toLowerCase().trim();
                if (!searchTerm) {
                    renderContacts(allContacts);
                    return;
                }
                const filtered = allContacts.filter(c =>
                    c.name.toLowerCase().includes(searchTerm) ||
                    c.type.toLowerCase().includes(searchTerm)
                );
                renderContacts(filtered);
            });

            // Request Notification Permission on load
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }

            function playNotificationSound() {
                try {
                    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();
                    
                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);
                    
                    oscillator.type = 'sine';
                    oscillator.frequency.setValueAtTime(587.33, audioContext.currentTime); // D5 note
                    gainNode.gain.setValueAtTime(0.08, audioContext.currentTime);
                    
                    oscillator.start();
                    oscillator.stop(audioContext.currentTime + 0.12);
                } catch (e) {
                    console.log('Audio feedback failed:', e);
                }
            }

            function showDesktopNotification(title, body, senderName) {
                playNotificationSound();
                
                // Show in-app Toast notification (always visible inside the browser window)
                if (typeof showToast === 'function') {
                    showToast(`New message from ${senderName}: ${body}`, 'success');
                }

                // Show browser desktop notification if permitted (great if user is in another tab/minimized)
                if ('Notification' in window && Notification.permission === 'granted') {
                    const notification = new Notification(title, {
                        body: body,
                        icon: '/favicon.ico'
                    });
                    notification.onclick = function() {
                        window.focus();
                    };
                }
            }

            // Debug: Listen for all events on this channel
            window.Echo.private('chat.Institute.' + INSTITUTE_ID)
                .on('pusher:subscription_succeeded', () => console.log('📡 Subscribed to private chat channel'))
                .listen('.MessageSent', (data) => {
                    console.log('📬 NEW MESSAGE RECEIVED:', data);
                    console.log('👤 Current conversation:', activeConversation);
                    
                    const senderID = parseInt(data.sender_id);
                    const activeUserID = activeConversation ? parseInt(activeConversation.user_id) : null;
                    const isMatchingConv = activeConversation && 
                                           activeUserID === senderID && 
                                           activeConversation.user_type === data.sender_type;
                    
                    console.log('📊 Checking match:', {
                        senderID: senderID,
                        senderType: data.sender_type,
                        activeUserID: activeUserID,
                        activeUserType: activeConversation?.user_type,
                        isMatch: isMatchingConv
                    });

                    const isMe = data.sender_type === 'Institute' && currentUserId == senderID;

                    // 1. If we are currently chatting with this person, show the message instantly and mark as READ
                    if (isMatchingConv) {
                        
                        console.log('✅ Message is for active conversation, adding to UI');
                        messages.push({
                            id: data.id,
                            sender_id: data.sender_id,
                            sender_type: data.sender_type,
                            message: data.message,
                            type: data.type,
                            attachment: data.attachment,
                            created_at: data.created_at,
                            sender: data.sender
                        });

                        console.log('🎨 Calling renderMessages, total messages:', messages.length);
                        renderMessages();
                        scrollToBottom();

                        // Automatically inform sender that I have read their message
                        markMessageAsRead(data.id);
                    } else {
                        console.log('⏭️ Not active conversation, marking as received');
                        // Not actively chatting, but device received it! Mark as RECEIVED (Delivered)
                        markMessageAsReceived(data.id);
                    }

                    // Show notification/toast and play sound for all incoming messages from others
                    if (!isMe) {
                        const senderName = data.sender ? data.sender.name : 'Someone';
                        let previewText = data.message || 'Sent an attachment';
                        if (data.type === 'image') previewText = '📷 Sent an image';
                        else if (data.type === 'video') previewText = '🎥 Sent a video';
                        else if (data.type === 'document') previewText = '📄 Sent a document';
                        else if (data.type === 'audio') previewText = '🎵 Sent an audio message';
                        else if (data.type === 'location') previewText = '📍 Shared a location';
                        else if (data.type === 'contact') previewText = '👤 Shared a contact';
                        
                        showDesktopNotification(`New message from ${senderName}`, previewText, senderName);
                    }

                    // 2. Always refresh the sidebar to show the latest message preview and unread count
                    fetchConversations();
                })
                .listen('.MessageReceived', (data) => {
                    console.log('📥 MESSAGE DELIVERED RECEIPT RECEIVED:', data);
                    messages.forEach(msg => {
                        if (msg.id == data.message_id) {
                            msg.received_at = data.received_at;
                        }
                    });
                    renderMessages();
                })
                .listen('.MessageRead', (data) => {
                    console.log('👁️ MESSAGE READ RECEIPT RECEIVED:', data);
                    messages.forEach(msg => {
                        if (msg.id == data.message_id) {
                            msg.read_at = data.read_at;
                        }
                    });
                    renderMessages();
                })
                .listen('.ChatDeleted', (data) => {
                    console.log('🗑️ CHAT DELETED RECEIPT RECEIVED:', data);
                    if (activeConversation && activeConversation.user_id == data.deleted_by_user_id && activeConversation.user_type == data.deleted_by_user_type) {
                        activeConversation = null;
                        messages = [];
                        renderMessages();
                        document.getElementById('active-chat').classList.add('hidden');
                        document.getElementById('chat-empty-state').classList.remove('hidden');
                    }
                    fetchConversations();
                });

            function clearCurrentConversation() {
                if (!activeConversation) return;
                document.getElementById('delete-confirm-user-name').innerText = activeConversation.user_name;
                openDeleteConfirmModal();
            }

            async function confirmClearConversation() {
                if (!activeConversation) return;
                closeDeleteConfirmModal();

                try {
                    const response = await fetch('/api/v1/chat/conversation', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({
                            user_id: activeConversation.user_id,
                            user_type: activeConversation.user_type
                        })
                    });
                    const result = await response.json();
                    if (result.status === 'success') {
                        activeConversation = null;
                        messages = [];
                        renderMessages();
                        document.getElementById('active-chat').classList.add('hidden');
                        document.getElementById('chat-empty-state').classList.remove('hidden');
                        fetchConversations();
                    } else {
                        alert(result.message || 'Failed to delete conversation.');
                    }
                } catch (error) {
                    console.error('Error deleting conversation:', error);
                }
            }

            async function markMessageAsRead(messageId) {
                try {
                    await fetch('/api/v1/chat/mark-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({ message_id: messageId })
                    });
                } catch (error) {
                    console.error('Error marking message as read:', error);
                }
            }

            async function markMessageAsReceived(messageId) {
                try {
                    await fetch('/api/v1/chat/mark-received', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({ message_id: messageId })
                    });
                } catch (error) {
                    console.error('Error marking message as received:', error);
                }
            }

            async function fetchConversations() {
                try {
                    const response = await fetch('/api/v1/chat/list', {
                        headers: { 'Accept': 'application/json' }
                    });
                    const result = await response.json();
                    if (result.status === 'success') {
                        currentConversations = result.data;
                        const searchTerm = document.getElementById('chat-search').value.toLowerCase().trim();
                        renderConversationList(searchTerm);
                    }
                } catch (error) {
                    console.error('Error fetching conversations:', error);
                }
            }

            function getLatestMessagePreview(conv) {
                if (conv.type === 'text') {
                    return conv.latest_message || '';
                }
                switch (conv.type) {
                    case 'image': return '📷 Photo';
                    case 'video': return '🎥 Video';
                    case 'document': return '📄 Document';
                    case 'audio': return '🎵 Audio';
                    case 'location': return '📍 Location';
                    case 'contact': return '👤 Contact';
                    default: return conv.latest_message || '';
                }
            }

            function renderConversationList(searchTerm = '') {
                const list = document.getElementById('conversation-list');

                // Filter conversations based on search term
                const filteredConversations = currentConversations.filter(conv => {
                    return conv.user_name.toLowerCase().includes(searchTerm);
                });

                if (filteredConversations.length === 0) {
                    if (currentConversations.length === 0) {
                        list.innerHTML = '<div class="p-8 text-center text-slate-400 text-xs font-medium">No conversations yet.</div>';
                    } else {
                        list.innerHTML = '<div class="p-8 text-center text-slate-400 text-xs font-medium">No conversations found.</div>';
                    }
                    return;
                }

                list.innerHTML = filteredConversations.map(conv => {
                        // Resolve logo to full URL if relative
                        let logoUrl = conv.user_logo ?? null;
                        if (logoUrl && !logoUrl.startsWith('http')) {
                            logoUrl = `${window.location.origin}/storage/${logoUrl.replace(/^\//, '')}`;
                        }
                        const initial = conv.user_name.substring(0, 1).toUpperCase();

                        const avatarHtml = logoUrl
                            ? `<div class="relative h-12 w-12 flex-shrink-0">
                                    <img src="${logoUrl}" class="h-12 w-12 rounded-full object-cover border border-slate-200 shadow-sm" alt="${initial}"
                                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                    <div class="h-12 w-12 rounded-full bg-slate-100 flex-shrink-0 items-center justify-center font-bold text-slate-500 border border-slate-200 absolute inset-0" style="display:none">${initial}</div>
                               </div>`
                            : `<div class="h-12 w-12 rounded-full bg-slate-100 flex-shrink-0 flex items-center justify-center font-bold text-slate-500 border border-slate-200">${initial}</div>`;

                        return `
                            <div onclick="selectConversation('${conv.user_id}', '${conv.user_type}', '${conv.user_name}', '${logoUrl ?? ''}')"
                                class="p-4 flex items-center gap-4 cursor-pointer hover:bg-slate-50 transition-colors ${activeConversation && activeConversation.user_id == conv.user_id && activeConversation.user_type == conv.user_type ? 'bg-orange-50/50 border-r-4 border-primary' : ''}">
                                ${avatarHtml}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-0.5">
                                        <h4 class="text-sm font-bold text-slate-800 truncate">${conv.user_name}</h4>
                                        <span class="text-[10px] font-medium text-slate-400">${formatTime(conv.created_at)}</span>
                                    </div>
                                    <p class="text-xs text-slate-500 truncate flex items-center gap-1">${getLatestMessagePreview(conv)}</p>
                                </div>
                                ${conv.unread_count > 0 ? `<div class="h-5 w-5 bg-primary text-white text-[10px] font-bold rounded-full flex items-center justify-center">${conv.unread_count}</div>` : ''}
                            </div>
                        `;
                    }).join('');
            }

            async function selectConversation(userId, userType, userName, logoUrl = '') {
                console.log('🔄 Selecting conversation with:', { userId, userType, userName });
                activeConversation = { user_id: parseInt(userId), user_type: userType, user_name: userName, user_logo: logoUrl || null };
                console.log('✅ Active conversation now set to:', activeConversation);

                document.getElementById('chat-empty-state').classList.add('hidden');
                document.getElementById('active-chat').classList.remove('hidden');
                document.getElementById('active-user-name').innerText = userName;

                // Update header avatar
                const avatarEl = document.getElementById('active-user-avatar');
                const initial = userName.substring(0, 1).toUpperCase();
                if (logoUrl) {
                    avatarEl.innerHTML = `<img src="${logoUrl}" class="h-full w-full object-cover" alt="${initial}" onerror="this.style.display='none';this.parentElement.innerText='${initial}'">`;
                    avatarEl.classList.remove('text-primary');
                } else {
                    avatarEl.innerHTML = initial;
                    avatarEl.classList.add('text-primary');
                }

                const searchTerm = document.getElementById('chat-search').value.toLowerCase().trim();
                renderConversationList(searchTerm); // Update active class
                await fetchMessages(userId, userType);
                console.log('📨 Messages fetched, total:', messages.length);
            }

            async function fetchMessages(userId, userType) {
                const container = document.getElementById('messages-container');
                container.innerHTML = '<div class="flex-1 flex items-center justify-center h-full"><div class="h-8 w-8 border-2 border-slate-100 border-t-primary rounded-full animate-spin"></div></div>';

                try {
                    const response = await fetch(`/api/v1/chat/messages/${userId}?type=${userType}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const result = await response.json();
                    if (result.status === 'success') {
                        messages = result.data;
                        renderMessages();
                        scrollToBottom();
                    }
                } catch (error) {
                    console.error('Error fetching messages:', error);
                }
            }

            function renderMessageContent(msg, isMe) {
                try {
                    const bubbleBg = isMe ? 'bg-white/10 text-white' : 'bg-slate-50 text-slate-700';
                    const textClass = isMe ? 'text-white' : 'text-slate-700';
                    const iconBg = isMe ? 'bg-white/20 text-white' : 'bg-orange-100/50 text-primary';

                    if (!msg.type || msg.type === 'text') {
                        const escapedMsg = (msg.message || '').replace(/[<>]/g, c => c === '<' ? '&lt;' : '&gt;');
                        return `<p class="text-[13px] leading-snug break-all">${escapedMsg}</p>`;
                    }

                    if (msg.type === 'image' && msg.attachment) {
                        const escapedMsg = (msg.message || '').replace(/[<>]/g, c => c === '<' ? '&lt;' : '&gt;');
                        return `
                                    <div class="space-y-1">
                                        <a href="${msg.attachment}" target="_blank" class="block overflow-hidden rounded-xl border ${isMe ? 'border-white/10' : 'border-slate-100'} max-w-[240px] hover:scale-[1.01] active:scale-95 transition-all">
                                            <img src="${msg.attachment}" class="w-full h-auto object-cover max-h-[160px]" alt="Image Attachment">
                                        </a>
                                        ${msg.message ? `<p class="text-[13px] leading-snug break-all mt-1 ${textClass}">${escapedMsg}</p>` : ''}
                                    </div>
                                `;
                    }

                    if (msg.type === 'video' && msg.attachment) {
                        const escapedMsg = (msg.message || '').replace(/[<>]/g, c => c === '<' ? '&lt;' : '&gt;');
                        return `
                                    <div class="space-y-1">
                                        <video controls class="max-w-[240px] rounded-xl border ${isMe ? 'border-white/10' : 'border-slate-100'} shadow-sm" src="${msg.attachment}"></video>
                                        ${msg.message ? `<p class="text-[13px] leading-snug break-all mt-1 ${textClass}">${escapedMsg}</p>` : ''}
                                    </div>
                                `;
                    }

                    if (msg.type === 'audio' && msg.attachment) {
                        const escapedMsg = (msg.message || '').replace(/[<>]/g, c => c === '<' ? '&lt;' : '&gt;');
                        return `
                                    <div class="space-y-1">
                                        <audio controls class="max-w-[240px] scale-90 origin-left" src="${msg.attachment}"></audio>
                                        ${msg.message ? `<p class="text-[13px] leading-snug break-all mt-1 ${textClass}">${escapedMsg}</p>` : ''}
                                    </div>
                                `;
                    }

                    if (msg.type === 'document' && msg.attachment) {
                        const filename = msg.attachment.split('/').pop() || 'Document';
                        const escapedMsg = (msg.message || '').replace(/[<>]/g, c => c === '<' ? '&lt;' : '&gt;');
                        return `
                                    <div class="space-y-1">
                                        <a href="${msg.attachment}" target="_blank" class="flex items-center gap-2.5 ${bubbleBg} hover:opacity-90 rounded-xl p-2.5 transition-all max-w-[240px]">
                                            <span class="p-1.5 ${iconBg} rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-[11px] font-bold truncate">${filename}</p>
                                                <p class="text-[9px] ${isMe ? 'text-white/70' : 'text-slate-400'}">Click to view/download</p>
                                            </div>
                                        </a>
                                        ${msg.message ? `<p class="text-[13px] leading-snug break-all mt-1 ${textClass}">${escapedMsg}</p>` : ''}
                                    </div>
                                `;
                    }

                    // Fallback for any message type
                    const escapedMsg = (msg.message || '').replace(/[<>]/g, c => c === '<' ? '&lt;' : '&gt;');
                    return `<p class="text-[13px] leading-snug break-all ${textClass}">${escapedMsg}</p>`;
                } catch (e) {
                    console.error('Error rendering message content:', e, msg);
                    return `<p class="text-[13px] leading-snug break-all">Message</p>`;
                }
            }

            function renderMessages() {
                const container = document.getElementById('messages-container');
                try {
                    if (!container) {
                        console.error('❌ Messages container not found');
                        return;
                    }
                    if (!messages || messages.length === 0) {
                        container.innerHTML = '<div class="flex items-center justify-center h-full text-slate-400">No messages yet</div>';
                        return;
                    }
                    console.log('🎨 Rendering', messages.length, 'messages');
                    container.innerHTML = messages.map((msg, idx) => {
                        const isMe = msg.sender_type === 'Institute' && msg.sender_id == currentUserId;
                        const senderName = msg.sender?.name || msg.sender?.full_name || 'U';
                        const senderInitial = senderName.substring(0, 1).toUpperCase() || '?';

                        // Ensure senderLogo is a full URL (relative paths get storage prefix)
                        // Fallback to activeConversation.user_logo if msg.sender has no image
                        let rawLogo = msg.sender?.logo ?? msg.sender?.profile_image ?? activeConversation?.user_logo ?? null;
                        if (rawLogo && !rawLogo.startsWith('http')) {
                            rawLogo = `${window.location.origin}/storage/${rawLogo.replace(/^\//, '')}`;
                        }
                        const senderLogo = rawLogo;

                        // Avatar HTML
                        const myAvatarHtml = INSTITUTE_LOGO
                            ? `<img src="${INSTITUTE_LOGO}" class="h-7 w-7 rounded-full object-cover border border-primary/20 shadow-sm flex-shrink-0 mt-0.5" alt="Me" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">`
                              + `<div class="h-7 w-7 rounded-full bg-primary/10 text-primary border border-primary/20 flex-shrink-0 items-center justify-center font-bold text-[9px] shadow-sm mt-0.5" style="display:none">${INSTITUTE_NAME.substring(0,1)}</div>`
                            : `<div class="h-7 w-7 rounded-full bg-primary/10 text-primary border border-primary/20 flex-shrink-0 flex items-center justify-center font-bold text-[9px] shadow-sm mt-0.5">${INSTITUTE_NAME.substring(0,1)}</div>`;

                        const otherAvatarHtml = senderLogo
                            ? `<img src="${senderLogo}" class="h-7 w-7 rounded-full object-cover border border-slate-200 shadow-sm flex-shrink-0 mt-0.5" alt="${senderInitial}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">`
                              + `<div class="h-7 w-7 rounded-full bg-slate-100 text-slate-500 border border-slate-200 flex-shrink-0 items-center justify-center font-bold text-[9px] shadow-sm mt-0.5" style="display:none">${senderInitial}</div>`
                            : `<div class="h-7 w-7 rounded-full bg-slate-100 text-slate-500 border border-slate-200 flex-shrink-0 flex items-center justify-center font-bold text-[9px] shadow-sm mt-0.5">${senderInitial}</div>`;

                        // Tick SVG for sent messages
                        const tickSvg = msg.read_at
                            ? `<svg class="w-3 h-3 text-sky-500 inline-block" viewBox="0 0 24 24" fill="none"><path d="M2 12L7 17L17 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 12L12 16L22 6" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>`
                            : msg.received_at
                            ? `<svg class="w-3 h-3 text-slate-400 inline-block" viewBox="0 0 24 24" fill="none"><path d="M2 12L7 17L17 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 12L12 16L22 6" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>`
                            : `<svg class="w-3 h-3 text-slate-400 inline-block" viewBox="0 0 24 24" fill="none"><path d="M4 12L9 17L20 6" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>`;

                        if (isMe) {
                            // Sent message: NO avatar, tick OUTSIDE bubble bottom-right
                            return `
                                <div class="flex flex-col items-end max-w-[75%] ml-auto">
                                    <div class="bg-primary text-white shadow-sm px-3 py-2 rounded-2xl rounded-br-none">
                                        ${renderMessageContent(msg, true)}
                                    </div>
                                    <div class="flex items-center gap-1 px-1 mt-0.5">
                                        <span class="text-[8px] font-medium text-slate-400/80">${formatTime(msg.created_at)}</span>
                                        ${tickSvg}
                                    </div>
                                </div>
                            `;
                        } else {
                            // Received message: avatar on left, time below bubble
                            return `
                                <div class="flex items-start gap-2 max-w-[75%]">
                                    ${otherAvatarHtml}
                                    <div class="space-y-0.5">
                                        <div class="bg-white border border-slate-100 text-slate-700 shadow-sm px-3 py-2 rounded-2xl rounded-bl-none">
                                            ${renderMessageContent(msg, false)}
                                        </div>
                                        <div class="flex items-center gap-1 px-1">
                                            <span class="text-[8px] font-medium text-slate-400/80">${formatTime(msg.created_at)}</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }
                    }).join('');
                    console.log('✅ Rendered successfully');
                } catch (e) {
                    console.error('❌ Error rendering messages:', e);
                    container.innerHTML = '<div class="p-4 text-red-500 text-xs">Error rendering messages. Please refresh.</div>';
                }
            }

            async function handleSendMessage(e) {
                e.preventDefault();
                const input = document.getElementById('message-input');
                const text = input.value.trim();
                if (!text || !activeConversation) return;

                input.value = '';
                input.disabled = true;

                try {
                    const response = await fetch('/api/v1/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({
                            receiver_id: activeConversation.user_id,
                            receiver_type: activeConversation.user_type === 'Staff' ? 'App\\Models\\Staff' :
                                (activeConversation.user_type === 'Student' ? 'App\\Models\\Student' :
                                    (activeConversation.user_type === 'Institute' ? 'App\\Models\\Institute' : 'App\\Models\\StudentParent')),
                            message: text,
                            type: 'text'
                        })
                    });
                    const result = await response.json();
                    console.log('📤 Message send response:', result);
                    if (result.status === 'success' && result.data) {
                        const msgData = result.data;
                        // Ensure sender object exists with fallback
                        if (!msgData.sender) {
                            msgData.sender = {
                                id: currentUserId,
                                name: '{{ auth("institute")->user()->name ?? auth("institute")->user()->institute_name ?? "You" }}',
                                type: 'Institute'
                            };
                        }
                        console.log('✅ Adding message to UI:', msgData);
                        messages.push(msgData);
                        renderMessages();
                        scrollToBottom();
                        setTimeout(() => fetchConversations(), 300);
                    } else {
                        console.error('❌ Message send failed:', result.message);
                    }
                } catch (error) {
                    console.error('❌ Error sending message:', error);
                } finally {
                    input.disabled = false;
                    input.focus();
                }
            }

            function appendMessage(msg) {
                messages.push(msg);
                renderMessages();
            }

            function scrollToBottom() {
                try {
                    const container = document.getElementById('messages-container');
                    if (container) {
                        setTimeout(() => {
                            container.scrollTop = container.scrollHeight;
                        }, 50);
                    }
                } catch (e) {
                    console.error('Error scrolling:', e);
                }
            }

            function formatTime(dateStr) {
                if (!dateStr) return '';
                try {
                    const date = new Date(dateStr);
                    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                } catch (e) {
                    return '';
                }
            }

            // Modal Logic
            function openNewChatModal() {
                const modal = document.getElementById('new-chat-modal');
                const content = document.getElementById('new-chat-content');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
                fetchContacts();
            }

            function closeNewChatModal() {
                const modal = document.getElementById('new-chat-modal');
                const content = document.getElementById('new-chat-content');
                document.getElementById('contact-search').value = '';
                content.classList.add('scale-95', 'opacity-0');
                content.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300);
            }

            async function fetchContacts() {
                const list = document.getElementById('contact-list');
                list.innerHTML = '<div class="p-8 text-center text-slate-400 text-xs font-medium">Searching contacts...</div>';

                try {
                    const response = await fetch('/api/v1/chat/contacts', {
                        headers: { 'Accept': 'application/json' }
                    });
                    allContacts = await response.json();
                    renderContacts(allContacts);
                } catch (error) {
                    console.error('Error fetching contacts:', error);
                    list.innerHTML = '<div class="p-8 text-center text-rose-500 text-xs font-medium">Failed to load contacts.</div>';
                }
            }

            function renderContacts(contacts) {
                const list = document.getElementById('contact-list');
                if (contacts.length === 0) {
                    list.innerHTML = '<div class="p-8 text-center text-slate-400 text-xs font-medium">No contacts found</div>';
                    return;
                }
                list.innerHTML = contacts.map(c => `
                            <div onclick="startChat('${c.id}', '${c.type}', '${c.name}')" class="p-2.5 flex items-center justify-between cursor-pointer hover:bg-slate-50 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-400 border border-slate-100 text-xs">
                                        ${c.name.substring(0, 1)}
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-800 leading-tight">${c.name}</h4>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">${c.type}</span>
                                    </div>
                                </div>
                                <div class="h-6 w-6 bg-orange-50 text-primary rounded-lg flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                </div>
                            </div>
                        `).join('');
            }

            function startChat(userId, type, name) {
                closeNewChatModal();
                selectConversation(userId, type, name);
            }

            // Attachment Dropdown & Modals toggle
            function toggleAttachmentMenu(e) {
                e.stopPropagation();
                const menu = document.getElementById('attachment-menu');
                if (menu.classList.contains('hidden')) {
                    menu.classList.remove('hidden');
                    setTimeout(() => {
                        menu.classList.remove('scale-95', 'opacity-0');
                        menu.classList.add('scale-100', 'opacity-100');
                    }, 10);
                } else {
                    hideAttachmentMenu();
                }
            }

            function hideAttachmentMenu() {
                const menu = document.getElementById('attachment-menu');
                menu.classList.add('scale-95', 'opacity-0');
                menu.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 200);
            }

            // Close attachment menu if user clicks anywhere else
            document.addEventListener('click', () => {
                const menu = document.getElementById('attachment-menu');
                if (menu && !menu.classList.contains('hidden')) {
                    hideAttachmentMenu();
                }
            });

            function triggerFileInput(id) {
                document.getElementById(id).click();
            }

            // Location Modals
            function openLocationModal() {
                const modal = document.getElementById('location-modal');
                const content = document.getElementById('location-modal-content');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function closeLocationModal() {
                const modal = document.getElementById('location-modal');
                const content = document.getElementById('location-modal-content');
                content.classList.add('scale-95', 'opacity-0');
                content.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300);
            }

            // Contact Modals
            function openContactModal() {
                const modal = document.getElementById('contact-modal');
                const content = document.getElementById('contact-modal-content');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function closeContactModal() {
                const modal = document.getElementById('contact-modal');
                const content = document.getElementById('contact-modal-content');
                content.classList.add('scale-95', 'opacity-0');
                content.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300);
            }

            // Delete Confirm Modals
            function openDeleteConfirmModal() {
                const modal = document.getElementById('delete-confirm-modal');
                const content = document.getElementById('delete-confirm-modal-content');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function closeDeleteConfirmModal() {
                const modal = document.getElementById('delete-confirm-modal');
                const content = document.getElementById('delete-confirm-modal-content');
                content.classList.add('scale-95', 'opacity-0');
                content.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300);
            }

            // Multipart / File upload implementation
            async function handleFileUpload(input, type) {
                if (!input.files || input.files.length === 0 || !activeConversation) return;
                const file = input.files[0];

                // Clear input value so same file can be uploaded again
                input.value = '';

                const formData = new FormData();
                formData.append('receiver_id', activeConversation.user_id);
                formData.append('receiver_type', activeConversation.user_type === 'Staff' ? 'App\\Models\\Staff' :
                    (activeConversation.user_type === 'Student' ? 'App\\Models\\Student' :
                        (activeConversation.user_type === 'Institute' ? 'App\\Models\\Institute' : 'App\\Models\\StudentParent')));
                formData.append('type', type);
                formData.append('attachment', file);
                formData.append('message', ''); // optional caption can be empty

                // Show uploading state in button or input
                const sendBtn = document.getElementById('send-btn');
                const origContent = sendBtn.innerHTML;
                sendBtn.innerHTML = '<div class="h-4 w-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>';
                sendBtn.disabled = true;

                try {
                    const response = await fetch('/api/v1/chat/send', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: formData
                    });
                    const result = await response.json();
                    if (result.status === 'success') {
                        messages.push(result.data);
                        renderMessages();
                        scrollToBottom();
                        setTimeout(() => fetchConversations(), 300);
                    }
                } catch (error) {
                    console.error('Error uploading chat media:', error);
                } finally {
                    sendBtn.innerHTML = origContent;
                    sendBtn.disabled = false;
                }
            }

            // Share Location implementation
            async function handleShareLocation(e) {
                e.preventDefault();
                if (!activeConversation) return;

                const label = document.getElementById('loc-label').value;
                const lat = document.getElementById('loc-lat').value;
                const lng = document.getElementById('loc-lng').value;

                // Reset and close
                document.getElementById('loc-label').value = '';
                document.getElementById('loc-lat').value = '';
                document.getElementById('loc-lng').value = '';
                closeLocationModal();

                const locationData = JSON.stringify({ label, lat, lng });

                try {
                    const response = await fetch('/api/v1/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({
                            receiver_id: activeConversation.user_id,
                            receiver_type: activeConversation.user_type === 'Staff' ? 'App\\Models\\Staff' :
                                (activeConversation.user_type === 'Student' ? 'App\\Models\\Student' :
                                    (activeConversation.user_type === 'Institute' ? 'App\\Models\\Institute' : 'App\\Models\\StudentParent')),
                            message: locationData,
                            type: 'location'
                        })
                    });
                    const result = await response.json();
                    if (result.status === 'success') {
                        messages.push(result.data);
                        renderMessages();
                        scrollToBottom();
                        setTimeout(() => fetchConversations(), 300);
                    }
                } catch (error) {
                    console.error('Error sharing location:', error);
                }
            }

            // Share Contact implementation
            async function handleShareContact(e) {
                e.preventDefault();
                if (!activeConversation) return;

                const name = document.getElementById('con-name').value;
                const phone = document.getElementById('con-phone').value;

                // Reset and close
                document.getElementById('con-name').value = '';
                document.getElementById('con-phone').value = '';
                closeContactModal();

                const contactData = JSON.stringify({ name, phone });

                try {
                    const response = await fetch('/api/v1/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({
                            receiver_id: activeConversation.user_id,
                            receiver_type: activeConversation.user_type === 'Staff' ? 'App\\Models\\Staff' :
                                (activeConversation.user_type === 'Student' ? 'App\\Models\\Student' :
                                    (activeConversation.user_type === 'Institute' ? 'App\\Models\\Institute' : 'App\\Models\\StudentParent')),
                            message: contactData,
                            type: 'contact'
                        })
                    });
                    const result = await response.json();
                    if (result.status === 'success') {
                        messages.push(result.data);
                        renderMessages();
                        scrollToBottom();
                        setTimeout(() => fetchConversations(), 300);
                    }
                } catch (error) {
                    console.error('Error sharing contact:', error);
                }
            }

            // Initial Load
            fetchConversations();
        </script>
    @endpush
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .custom-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection