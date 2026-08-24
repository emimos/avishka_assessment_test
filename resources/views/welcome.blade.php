@extends('layouts.app')

@section('title', 'Customer Support Hub - Open & Track Tickets')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <!-- Hero Header -->
    <div class="text-center space-y-3">
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight bg-gradient-to-r from-blue-400 via-indigo-300 to-purple-400 bg-clip-text text-transparent">
            How can we help you today?
        </h1>
        <p class="text-slate-400 text-sm sm:text-base max-w-xl mx-auto">
            Submit a new support ticket or check the real-time status of your existing ticket using your reference number.
        </p>
    </div>

    <!-- Main Navigation Tabs -->
    <div class="flex justify-center pb-1">
        <div class="inline-flex p-1 bg-slate-900/90 rounded-xl border border-slate-800 space-x-1">
            <button id="tab-btn-open" onclick="switchTab('open')" class="flex items-center space-x-2 px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 text-white bg-blue-600 shadow-lg shadow-blue-600/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Open New Ticket</span>
            </button>
            <button id="tab-btn-status" onclick="switchTab('status')" class="flex items-center space-x-2 px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 text-slate-400 hover:text-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span>Check Ticket Status</span>
            </button>
        </div>
    </div>

    <!-- TAB 1: OPEN A SUPPORT TICKET -->
    <div id="tab-content-open" class="glass-card rounded-2xl p-6 sm:p-8 space-y-6">
        <div class="border-b border-slate-800/80 pb-4">
            <h2 class="text-xl font-bold text-blue-500">Submit Support Ticket</h2>
            <p class="text-xs text-blue-400 mt-1">Fill in the details below. A unique reference code will be issued instantly.</p>
        </div>

        <form id="create-ticket-form" onsubmit="handleTicketSubmit(event)" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Customer Name -->
                <div>
                    <label for="customer_name" class="block text-xs font-semibold text-blue-400 uppercase tracking-wider mb-2">
                        Customer Name <span class="text-blue-400">*</span>
                    </label>
                    <input type="text" id="customer_name" name="customer_name" required placeholder="e.g. John Doe" 
                           class="w-full px-4 py-3 rounded-xl glass-input text-sm focus:outline-none">
                    <p id="err-customer_name" class="text-xs text-rose-400 mt-1 hidden"></p>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-blue-400 uppercase tracking-wider mb-2">
                        Email Address <span class="text-blue-400">*</span>
                    </label>
                    <input type="email" id="email" name="email" required placeholder="john@example.com" 
                           class="w-full px-4 py-3 rounded-xl glass-input text-sm focus:outline-none">
                    <p id="err-email" class="text-xs text-rose-400 mt-1 hidden"></p>
                </div>
            </div>

            <!-- Phone Number -->
            <div>
                <label for="phone_number" class="block text-xs font-semibold text-blue-400 uppercase tracking-wider mb-2">
                    Phone Number <span class="text-blue-400">*</span>
                </label>
                <input type="text" id="phone_number" name="phone_number" required placeholder="+1 (555) 000-1234" 
                       class="w-full px-4 py-3 rounded-xl glass-input text-sm focus:outline-none">
                <p id="err-phone_number" class="text-xs text-rose-400 mt-1 hidden"></p>
            </div>

            <!-- Problem Description -->
            <div>
                <label for="problem_description" class="block text-xs font-semibold text-blue-400 uppercase tracking-wider mb-2">
                    Problem Description <span class="text-blue-400">*</span>
                </label>
                <textarea id="problem_description" name="problem_description" rows="5" required placeholder="Please describe the issue or query in detail..." 
                          class="w-full px-4 py-3 rounded-xl glass-input text-sm focus:outline-none resize-none"></textarea>
                <p id="err-problem_description" class="text-xs text-rose-400 mt-1 hidden"></p>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" id="btn-submit-ticket" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-semibold text-sm shadow-xl shadow-blue-600/30 transition-all flex items-center justify-center space-x-2">
                    <span id="btn-submit-text">Submit Support Ticket</span>
                    <svg id="btn-submit-spinner" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 2: CHECK TICKET STATUS -->
    <div id="tab-content-status" class="glass-card rounded-2xl p-6 sm:p-8 space-y-6 hidden">
        <div class="border-b border-slate-800/80 pb-4">
            <h2 class="text-xl font-bold text-blue-500">Check Ticket Status</h2>
            <p class="text-xs text-blue-400 mt-1">Enter your ticket reference number to view updates and replies from support agents.</p>
        </div>

        <form id="check-status-form" onsubmit="handleStatusCheck(event)" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <div class="flex-grow relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <input type="text" id="reference_number" name="reference_number" required placeholder="Enter Reference Code (e.g. TK-8F92-A37B)" 
                       class="w-full pl-12 pr-4 py-3.5 rounded-xl glass-input text-sm focus:outline-none uppercase font-mono tracking-wider">
            </div>
            <button type="submit" id="btn-check-status" class="px-8 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center space-x-2 shrink-0">
                <span>Check Status</span>
            </button>
        </form>

        <!-- Status Result Container -->
        <div id="status-result-box" class="hidden space-y-6 pt-4 border-t border-slate-800/80">
            <!-- Ticket Info Card Header -->
            <div class="p-5 rounded-xl bg-blue-100 border border-blue-300 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <div class="flex items-center space-x-3">
                        <span id="res-ref" class="text-xl font-extrabold font-mono text-blue-400"></span>
                        <span id="res-status-badge" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"></span>
                    </div>
                    <p class="text-xs text-slate-800 mt-1">
                        Submitted by <span id="res-name" class="text-slate-600 font-semibold"> </span> ( <span id="res-email" class="text-slate-600"></span>) on <span id="res-date" class="text-slate-800"></span>
                    </p>
                </div>
            </div>

            <!-- Ticket Problem Description -->
            <div class="space-y-2">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Problem Description</h4>
                <div id="res-description" class="p-4 rounded-xl bg-red-50 border text-sm text-slate-800 whitespace-pre-wrap leading-relaxed"></div>
            </div>

            <!-- Agent Replies Timeline -->
            <div class="space-y-4">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-2">
                    <span>Replies & Updates</span>
                    <span id="reply-count-chip" class="px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-400 text-xs">0</span>
                </h4>

                <div id="res-replies-list" class="space-y-3">
                    <!-- Dynamic Replies Inserted Here -->
                </div>
            </div>

            <!-- Customer Follow-up Reply Form -->
            <div class="pt-4 border-t border-slate-800/80 space-y-3">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Post a Follow-up Reply</h4>
                <form id="customer-reply-form" onsubmit="handleCustomerReply(event)" class="space-y-3">
                    @csrf
                    <textarea id="customer_reply_message" name="message" rows="3" required placeholder="Type your reply or additional details here..." 
                              class="w-full px-4 py-3 rounded-xl glass-input text-sm focus:outline-none resize-none"></textarea>
                    <button type="submit" id="btn-customer-reply" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm shadow-md transition-all flex items-center justify-center space-x-2">
                        <span>Send Reply</span>
                    </button>
                </form>
            </div>
        </div>
    </div>


</div>

<!-- SUCCESS REFERENCE MODAL -->
<div id="success-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm hidden">
    <div class="glass-card rounded-2xl max-w-lg w-full p-6 sm:p-8 space-y-6 text-center shadow-2xl border border-blue-500/30 transform scale-95 transition-all duration-300" id="success-modal-box">
        <div class="w-16 h-16 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/20">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <div class="space-y-2">
            <h3 class="text-2xl font-extrabold text-slate-100">Support Ticket Created!</h3>
            <p class="text-xs text-slate-400">An acknowledgement email has been dispatched. Please save your reference number below to track updates.</p>
        </div>

        <div class="p-4 bg-slate-950/80 rounded-xl border border-blue-500/40 space-y-2">
            <span class="text-xs text-slate-400 uppercase font-semibold tracking-wider">Your Reference Code</span>
            <div class="flex items-center justify-center space-x-3">
                <span id="modal-ref-code" class="text-2xl sm:text-3xl font-extrabold font-mono text-blue-400 tracking-wider"></span>
                <button onclick="copyReferenceCode()" class="p-2 rounded-lg bg-blue-600/30 hover:bg-blue-600/50 text-blue-300 transition-all" title="Copy Code">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <button onclick="trackNowFromModal()" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm shadow-lg shadow-blue-600/30 transition-all">
                Track Ticket Status Now
            </button>
            <button onclick="closeSuccessModal()" class="w-full py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-sm transition-all">
                Close
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentReferenceCode = '';

    function switchTab(tab) {
        const btnOpen = document.getElementById('tab-btn-open');
        const btnStatus = document.getElementById('tab-btn-status');
        const contentOpen = document.getElementById('tab-content-open');
        const contentStatus = document.getElementById('tab-content-status');

        if (tab === 'open') {
            btnOpen.className = 'flex items-center space-x-2 px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 text-white bg-blue-600 shadow-lg shadow-blue-600/30';
            btnStatus.className = 'flex items-center space-x-2 px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 text-slate-400 hover:text-slate-200';
            contentOpen.classList.remove('hidden');
            contentStatus.classList.add('hidden');
        } else {
            btnStatus.className = 'flex items-center space-x-2 px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 text-white bg-blue-600 shadow-lg shadow-blue-600/30';
            btnOpen.className = 'flex items-center space-x-2 px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 text-slate-400 hover:text-slate-200';
            contentStatus.classList.remove('hidden');
            contentOpen.classList.add('hidden');
        }
    }

    async function handleTicketSubmit(event) {
        event.preventDefault();
        
        // Reset errors
        document.querySelectorAll('[id^="err-"]').forEach(el => el.classList.add('hidden'));
        
        const form = event.target;
        const formData = new FormData(form);

        const btnText = document.getElementById('btn-submit-text');
        const btnSpinner = document.getElementById('btn-submit-spinner');
        const btnSubmit = document.getElementById('btn-submit-ticket');

        btnText.textContent = 'Submitting...';
        btnSpinner.classList.remove('hidden');
        btnSubmit.disabled = true;

        try {
            const response = await fetch("{{ route('ticket.create') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                currentReferenceCode = data.reference_number;
                document.getElementById('modal-ref-code').textContent = data.reference_number;
                
                // Reset form
                form.reset();
                
                // Show modal
                document.getElementById('success-modal').classList.remove('hidden');
                showToast('Ticket created successfully!', 'success');
            } else {
                if (data.errors) {
                    for (const [field, messages] of Object.entries(data.errors)) {
                        const errEl = document.getElementById(`err-${field}`);
                        if (errEl) {
                            errEl.textContent = messages[0];
                            errEl.classList.remove('hidden');
                        }
                    }
                } else {
                    showToast(data.message || 'An error occurred while creating ticket.', 'error');
                }
            }
        } catch (error) {
            console.error(error);
            showToast('Network error. Please try again.', 'error');
        } finally {
            btnText.textContent = 'Submit Support Ticket';
            btnSpinner.classList.add('hidden');
            btnSubmit.disabled = false;
        }
    }

    async function handleStatusCheck(event) {
        event.preventDefault();
        const refInput = document.getElementById('reference_number');
        const refCode = refInput.value.trim();

        if (!refCode) return;

        try {
            const response = await fetch("{{ route('ticket.status') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reference_number: refCode })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                renderTicketStatus(data.ticket);
                showToast('Ticket status loaded.', 'success');
            } else {
                document.getElementById('status-result-box').classList.add('hidden');
                showToast(data.message || 'Ticket not found.', 'error');
            }
        } catch (error) {
            console.error(error);
            showToast('Network error while fetching ticket status.', 'error');
        }
    }

    function renderTicketStatus(ticket) {
        document.getElementById('res-ref').textContent = ticket.reference_number;
        document.getElementById('res-name').textContent = ticket.customer_name;
        document.getElementById('res-email').textContent = ticket.email;
        document.getElementById('res-date').textContent = ticket.created_at;
        document.getElementById('res-description').textContent = ticket.problem_description;

        // Status Badge
        const badge = document.getElementById('res-status-badge');
        if (ticket.status.toLowerCase() === 'replied') {
            badge.className = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500 text-white border border-emerald-500/30';
        } else {
            badge.className = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-500 text-white border border-amber-500/30';
        }
        badge.textContent = ticket.status;

        // Replies list
        const repliesList = document.getElementById('res-replies-list');
        document.getElementById('reply-count-chip').textContent = ticket.replies.length;
        
        if (ticket.replies.length === 0) {
            repliesList.innerHTML = `
                <div class="p-4 rounded-xl bg-blue-100 border border-slate-800/80 text-center text-slate-800 text-xs">
                    No replies yet. Our team will respond shortly.
                </div>
            `;
        } else {
            repliesList.innerHTML = ticket.replies.map(reply => {
                const isCust = reply.is_customer;
                const badgeColor = isCust ? 'text-white' : 'text-white';
                const borderClass = isCust ? 'bg-blue-300' : 'bg-blue-700';
                return `
                    <div class="p-4 rounded-xl ${borderClass} border space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold ${badgeColor} flex items-center space-x-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>${reply.agent_name}</span>
                            </span>
                            <span class="text-[11px] text-white">${reply.created_at}</span>
                        </div>
                        <p class="text-sm text-white leading-relaxed">${reply.message}</p>
                    </div>
                `;
            }).join('');
        }

        document.getElementById('status-result-box').classList.remove('hidden');
    }

    async function handleCustomerReply(event) {
        event.preventDefault();
        const messageInput = document.getElementById('customer_reply_message');
        const message = messageInput.value.trim();
        const refCode = document.getElementById('reference_number').value.trim();

        if (!message || !refCode) return;

        const btn = document.getElementById('btn-customer-reply');
        btn.disabled = true;

        try {
            const response = await fetch("{{ route('ticket.customer-reply') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reference_number: refCode, message: message })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                messageInput.value = '';
                showToast('Reply posted successfully!', 'success');
                // Refresh ticket status to show new reply
                handleStatusCheck(new Event('submit'));
            } else {
                showToast(data.message || 'Failed to post reply.', 'error');
            }
        } catch (error) {
            console.error(error);
            showToast('Network error while posting reply.', 'error');
        } finally {
            btn.disabled = false;
        }
    }


    function copyReferenceCode() {
        if (!currentReferenceCode) return;
        navigator.clipboard.writeText(currentReferenceCode);
        showToast('Reference code copied to clipboard!', 'success');
    }

    function closeSuccessModal() {
        document.getElementById('success-modal').classList.add('hidden');
    }

    function trackNowFromModal() {
        closeSuccessModal();
        switchTab('status');
        document.getElementById('reference_number').value = currentReferenceCode;
        handleStatusCheck(new Event('submit'));
    }
</script>
@endpush
