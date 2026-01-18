<div class="flex flex-col h-[600px] bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden" 
     x-data="chatSystem({
        bookingId: {{ $booking->id }},
        authId: {{ auth()->id() }},
        token: '{{ csrf_token() }}'
     })" 
     x-init="init()">
    
    {{-- Chat Header --}}
    <div class="p-6 border-b border-gray-100 bg-white flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </div>
            <div>
                {{-- Translation for: Live Chat --}}
                <h3 class="font-bold text-gray-800 text-sm">{{ __('messages.chat.title') }}</h3>
                {{-- Translation for: Online --}}
                <p class="text-[11px] text-green-500">{{ __('messages.chat.active_status') }}</p>
            </div>
        </div>
    </div>

    {{-- Messages Display Area --}}
    <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-[#f8f9fb]" x-ref="container">
        <template x-for="msg in messages" :key="msg.id">
            <div :class="msg.sender_id == authId ? 'flex justify-end' : 'flex justify-start'">
                <div :class="msg.sender_id == authId 
                    ? 'bg-indigo-600 text-white rounded-2xl rounded-tr-none shadow-sm' 
                    : 'bg-white text-gray-800 border border-gray-100 rounded-2xl rounded-tl-none shadow-sm'" 
                     class="max-w-[85%] px-4 py-2.5 relative">
                    <p class="text-sm" x-text="msg.message"></p>
                    <div class="text-[10px] mt-1 opacity-50" x-text="formatDate(msg.created_at)"></div>
                </div>
            </div>
        </template>
    </div>

    {{-- Message Input Area --}}
    <div class="p-4 bg-white border-t border-gray-100">
        <div class="relative flex items-center gap-2">
            {{-- Translation for: Placeholder --}}
            <input type="text" x-model="newMessage" @keyup.enter="sendMessage()" 
                   placeholder="{{ __('messages.chat.placeholder') }}"
                   class="flex-1 bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-indigo-500">
            
            <button @click="sendMessage()" :disabled="!newMessage.trim()" class="bg-indigo-600 text-white p-3 rounded-xl disabled:opacity-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9-2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
function chatSystem(config) {
    return {
        messages: [],
        newMessage: '',
        bookingId: config.bookingId,
        authId: config.authId,
        csrfToken: config.token,
        
        init() {
            this.fetchMessages();
            setInterval(() => this.fetchMessages(), 4000);
        },

        fetchMessages() {
            fetch(`/chat/bookings/${this.bookingId}/messages`, { 
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.ok ? res.json() : [])
            .then(data => {
                this.messages = data;
                this.$nextTick(() => this.scrollToBottom());
            })
            .catch(error => console.error('Error fetching messages:', error));
        },

        sendMessage() {
            if (!this.newMessage.trim()) return;

            const messageToSend = this.newMessage;

            fetch(`/chat/bookings/${this.bookingId}/messages`, { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ message: messageToSend })
            })
            .then(res => {
                if (!res.ok) throw new Error('Failed to send message');
                return res.json();
            })
            .then(res => {
                this.newMessage = ''; 
                this.fetchMessages();
            })
            .catch(err => {
                console.error("Transmission Error:", err);
                // Simple fallback if translation is not needed for specific JS alerts
                alert("Error: Message could not be sent.");
            });
        },

        scrollToBottom() {
            const container = this.$refs.container;
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        },

        formatDate(dateStr) {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
    }
}
</script>