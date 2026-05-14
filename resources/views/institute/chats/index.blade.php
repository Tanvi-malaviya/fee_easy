@extends('layouts.institute')

@section('content')
<div class="max-w-[1600px] mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Messages</h1>
            <p class="text-sm text-slate-500 font-medium">Connect with students and parents</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button class="px-5 py-2.5 bg-primary text-white rounded-xl text-sm font-bold shadow-lg shadow-orange-900/10 hover:translate-y-[-1px] transition-all">
                New Broadcast
            </button>
        </div>
    </div>

    <!-- Main Chat Interface -->
    <div class="grid grid-cols-12 gap-6 h-[calc(100vh-12rem)] min-h-[600px]">
        
        <!-- Sidebar: Conversation List -->
        <div class="col-span-12 lg:col-span-4 xl:col-span-3 flex flex-col bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Search & New Chat -->
            <div class="p-4 border-b border-slate-50 space-y-3">
                <div class="relative">
                    <input type="text" id="chat-search" placeholder="Search conversations..." 
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-primary/20 transition-all outline-none text-slate-600">
                    <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button onclick="openNewChatModal()" class="w-full py-2 bg-orange-50 text-primary rounded-xl text-xs font-bold hover:bg-orange-100 transition-all flex items-center justify-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    New Chat
                </button>
            </div>

            <!-- List -->
            <div id="conversation-list" class="flex-1 overflow-y-auto custom-scrollbar">
                <div class="p-8 text-center text-slate-400 text-xs font-medium">Loading conversations...</div>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div id="chat-area" class="col-span-12 lg:col-span-8 xl:col-span-9 flex flex-col bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Empty State -->
            <div id="chat-empty-state" class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-2">Select a conversation</h3>
                <p class="text-xs text-slate-500 max-w-[280px] leading-relaxed">Choose a student or staff member from the sidebar to start messaging in real-time.</p>
            </div>

            <!-- Active Chat -->
            <div id="active-chat" class="hidden flex-1 flex flex-col overflow-hidden">
                <!-- Chat Header -->
                <div class="p-4 border-b border-slate-50 flex items-center justify-between bg-white/50 backdrop-blur-sm sticky top-0 z-10">
                    <div class="flex items-center gap-4">
                        <div id="active-user-avatar" class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center font-bold text-primary border border-primary/20">
                            ?
                        </div>
                        <div>
                            <h3 id="active-user-name" class="text-sm font-bold text-slate-800">User Name</h3>
                            <div class="flex items-center gap-1.5">
                                <div class="h-1.5 w-1.5 bg-emerald-500 rounded-full"></div>
                                <span id="active-user-status" class="text-[10px] font-bold text-emerald-500 uppercase tracking-wider">Online</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="h-9 w-9 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        </button>
                    </div>
                </div>

                <!-- Messages Container -->
                <div id="messages-container" class="flex-1 overflow-y-auto p-4 space-y-2.5 bg-slate-50/30 custom-scrollbar scroll-smooth">
                    <!-- Messages will be injected here -->
                </div>

                <!-- Input Area -->
                <div class="p-4 border-t border-slate-50 bg-white">
                    <form id="message-form" onsubmit="handleSendMessage(event)" class="flex items-center gap-3 bg-slate-50 rounded-2xl p-2 pr-3">
                        <button type="button" class="h-10 w-10 flex items-center justify-center text-slate-400 hover:text-primary transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                        </button>
                        <input type="text" id="message-input" placeholder="Type your message here..." 
                            class="flex-1 bg-transparent border-none focus:ring-0 text-sm py-2 text-slate-600 outline-none">
                        <button type="submit" id="send-btn" class="h-10 w-10 bg-primary text-white rounded-xl flex items-center justify-center shadow-lg shadow-orange-900/10 hover:scale-105 active:scale-95 transition-all">
                            <svg class="w-5 h-5 transform rotate-90" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Chat Modal -->
<div id="new-chat-modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[200] hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl scale-95 opacity-0 transition-all duration-300" id="new-chat-content">
        <div class="p-6 border-b border-slate-50 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Start New Chat</h3>
            <button onclick="closeNewChatModal()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-4 bg-slate-50">
            <div class="relative">
                <input type="text" id="contact-search" placeholder="Search students or staff..." 
                    class="w-full pl-10 pr-4 py-3 bg-white border border-slate-100 rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 transition-all outline-none">
                <svg class="w-4 h-4 absolute left-3.5 top-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
        <div id="contact-list" class="max-h-[400px] overflow-y-auto custom-scrollbar divide-y divide-slate-50">
            <!-- Contacts will be injected here -->
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>

<script>
    const INSTITUTE_ID = '{{ auth('institute')->id() }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    
    // Initialize Echo
    window.Pusher = Pusher;
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ config('broadcasting.connections.pusher.key') }}',
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster', 'mt1') }}',
        wsHost: window.location.hostname,
        wsPort: 6001,
        wssPort: 6001,
        forceTLS: false,
        encrypted: false,
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
    
    // Debug: Listen for all events on this channel
    window.Echo.private('chat.Institute.' + INSTITUTE_ID)
        .on('pusher:subscription_succeeded', () => console.log('📡 Subscribed to private chat channel'))
        .listen('.MessageSent', (data) => {
            console.log('📬 NEW MESSAGE RECEIVED:', data);
            
            // 1. If we are currently chatting with this person, show the message instantly
            if (activeConversation && 
                activeConversation.user_id == data.sender_id && 
                activeConversation.user_type == data.sender_type) {
                
                // Add to our messages array
                messages.push({
                    id: data.id,
                    sender_id: data.sender_id,
                    sender_type: data.sender_type,
                    message: data.message,
                    type: data.type,
                    created_at: data.created_at,
                    sender: data.sender
                });
                
                // Refresh the chat window
                renderMessages();
                scrollToBottom();
            }
            
            // 2. Always refresh the sidebar to show the latest message preview and unread count
            fetchConversations();
        });

    async function fetchConversations() {
        try {
            const response = await fetch('{{ route('institute.chats.list') }}', {
                headers: { 'Accept': 'application/json' }
            });
            const result = await response.json();
            if (result.status === 'success') {
                currentConversations = result.data;
                renderConversationList();
            }
        } catch (error) {
            console.error('Error fetching conversations:', error);
        }
    }

    function renderConversationList() {
        const list = document.getElementById('conversation-list');
        if (currentConversations.length === 0) {
            list.innerHTML = '<div class="p-8 text-center text-slate-400 text-xs font-medium">No conversations yet.</div>';
            return;
        }

        list.innerHTML = currentConversations.map(conv => `
            <div onclick="selectConversation('${conv.user_id}', '${conv.user_type}', '${conv.user_name}')" 
                class="p-4 flex items-center gap-4 cursor-pointer hover:bg-slate-50 transition-colors ${activeConversation && activeConversation.user_id == conv.user_id && activeConversation.user_type == conv.user_type ? 'bg-orange-50/50 border-r-4 border-primary' : ''}">
                <div class="h-12 w-12 rounded-full bg-slate-100 flex-shrink-0 flex items-center justify-center font-bold text-slate-500 border border-slate-200">
                    ${conv.user_name.substring(0, 1)}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-0.5">
                        <h4 class="text-sm font-bold text-slate-800 truncate">${conv.user_name}</h4>
                        <span class="text-[10px] font-medium text-slate-400">${formatTime(conv.created_at)}</span>
                    </div>
                    <p class="text-xs text-slate-500 truncate">${conv.latest_message}</p>
                </div>
                ${conv.unread_count > 0 ? `<div class="h-5 w-5 bg-primary text-white text-[10px] font-bold rounded-full flex items-center justify-center">${conv.unread_count}</div>` : ''}
            </div>
        `).join('');
    }

    async function selectConversation(userId, userType, userName) {
        activeConversation = { user_id: userId, user_type: userType, user_name: userName };
        
        document.getElementById('chat-empty-state').classList.add('hidden');
        document.getElementById('active-chat').classList.remove('hidden');
        document.getElementById('active-user-name').innerText = userName;
        document.getElementById('active-user-avatar').innerText = userName.substring(0, 1);
        
        renderConversationList(); // Update active class
        fetchMessages(userId, userType);
    }

    async function fetchMessages(userId, userType) {
        const container = document.getElementById('messages-container');
        container.innerHTML = '<div class="flex-1 flex items-center justify-center h-full"><div class="h-8 w-8 border-2 border-slate-100 border-t-primary rounded-full animate-spin"></div></div>';
        
        try {
            const response = await fetch(`/institute/chats/messages/${userId}?type=${userType}`, {
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

    function renderMessages() {
        const container = document.getElementById('messages-container');
        const currentUserId = {{ auth('institute')->id() }};
        container.innerHTML = messages.map(msg => {
            const isMe = msg.sender_type === 'Institute' && msg.sender_id == currentUserId;
            return `
                <div class="flex items-end gap-2 max-w-[85%] ${isMe ? 'ml-auto flex-row-reverse' : ''}">
                    <div class="h-7 w-7 rounded-full ${isMe ? 'bg-primary/10 text-primary border-primary/20' : 'bg-slate-100 text-slate-500 border-slate-200'} flex-shrink-0 flex items-center justify-center font-bold text-[9px] border shadow-sm">
                        ${isMe ? 'I' : msg.sender.name.substring(0, 1)}
                    </div>
                    <div class="space-y-0.5 ${isMe ? 'text-right' : ''}">
                        <div class="${isMe ? 'bg-primary text-white shadow-sm' : 'bg-white border border-slate-100 text-slate-700 shadow-sm'} px-3 py-2 rounded-2xl ${isMe ? 'rounded-br-none' : 'rounded-bl-none'}">
                            <p class="text-[13px] leading-snug">${msg.message}</p>
                        </div>
                        <div class="flex items-center ${isMe ? 'justify-end' : ''} gap-1 px-1">
                            <span class="text-[8px] font-medium text-slate-400 opacity-70">${formatTime(msg.created_at)}</span>
                            ${isMe ? `<svg class="w-2.5 h-2.5 text-primary opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>` : ''}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    async function handleSendMessage(e) {
        e.preventDefault();
        const input = document.getElementById('message-input');
        const text = input.value.trim();
        if (!text || !activeConversation) return;

        input.value = '';
        input.disabled = true;

        try {
            const response = await fetch('{{ route('institute.chats.send') }}', {
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
            if (result.status === 'success') {
                messages.push(result.data);
                renderMessages();
                scrollToBottom();
                setTimeout(() => fetchConversations(), 300); // Small delay to ensure DB is ready
            }
        } catch (error) {
            console.error('Error sending message:', error);
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
        const container = document.getElementById('messages-container');
        container.scrollTop = container.scrollHeight;
    }

    function formatTime(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
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
            const response = await fetch('{{ route('institute.chats.contacts') }}', {
                headers: { 'Accept': 'application/json' }
            });
            const contacts = await response.json();
            
            list.innerHTML = contacts.map(c => `
                <div onclick="startChat('${c.id}', '${c.type}', '${c.name}')" class="p-4 flex items-center justify-between cursor-pointer hover:bg-slate-50 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-400 border border-slate-100">
                            ${c.name.substring(0, 1)}
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800">${c.name}</h4>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">${c.type}</span>
                        </div>
                    </div>
                    <div class="h-8 w-8 bg-orange-50 text-primary rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </div>
                </div>
            `).join('');
        } catch (error) {
            console.error('Error fetching contacts:', error);
        }
    }

    function startChat(userId, type, name) {
        closeNewChatModal();
        selectConversation(userId, type, name);
    }

    // Initial Load
    fetchConversations();
</script>
@endpush
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { display: none; }
    .custom-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
