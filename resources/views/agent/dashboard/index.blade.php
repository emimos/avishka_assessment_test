@extends('layouts.app')

@section('title', 'Agent Dashboard - Support System')

@section('content')
<div class="min-h-screen bg-white text-black">
<div class="max-w-7xl mx-auto p-3 sm:p-6 space-y-4 sm:space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-black">Agent Ticket Dashboard</h1>
            <p class="text-xs sm:text-sm text-gray-500">Manage and reply to customer support requests.</p>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
            <div class="p-2.5 sm:p-3 rounded-lg border border-gray-200 bg-white text-center">
                <span class="text-[10px] sm:text-xs text-gray-500 font-semibold uppercase block">Total</span>
                <span class="text-lg sm:text-xl font-bold text-black">{{ $stats['total'] }}</span>
            </div>
            <div class="p-2.5 sm:p-3 rounded-lg border border-blue-200 bg-blue-50 text-center">
                <span class="text-[10px] sm:text-xs text-blue-600 font-semibold uppercase block">New</span>
                <span class="text-lg sm:text-xl font-bold text-blue-700">{{ $stats['new_unopened'] }}</span>
            </div>
            <div class="p-2.5 sm:p-3 rounded-lg border border-gray-200 bg-gray-50 text-center">
                <span class="text-[10px] sm:text-xs text-gray-600 font-semibold uppercase block">Pending</span>
                <span class="text-lg sm:text-xl font-bold text-black">{{ $stats['pending'] }}</span>
            </div>
            <div class="p-2.5 sm:p-3 rounded-lg border border-blue-200 bg-blue-50 text-center">
                <span class="text-[10px] sm:text-xs text-blue-600 font-semibold uppercase block">Replied</span>
                <span class="text-lg sm:text-xl font-bold text-blue-700">{{ $stats['replied'] }}</span>
            </div>
        </div>
    </div>

    <!-- Filters + Search -->
    <div class="bg-white border border-gray-200 rounded-lg p-3 sm:p-4 flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
        <div class="flex items-center space-x-1 bg-gray-100 p-1 rounded-lg w-full md:w-auto overflow-x-auto whitespace-nowrap">
            <a href="{{ route('agent.dashboard', ['status' => 'all', 'search' => request('search')]) }}"
               class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-md text-xs font-semibold {{ request('status', 'all') == 'all' ? 'bg-blue-600 text-white' : 'text-black hover:bg-gray-200' }}">
                All
            </a>
            <a href="{{ route('agent.dashboard', ['status' => 'new', 'search' => request('search')]) }}"
               class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-md text-xs font-semibold {{ request('status') == 'new' ? 'bg-blue-600 text-white' : 'text-black hover:bg-gray-200' }}">
                New
            </a>
            <a href="{{ route('agent.dashboard', ['status' => 'pending', 'search' => request('search')]) }}"
               class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-md text-xs font-semibold {{ request('status') == 'pending' ? 'bg-blue-600 text-white' : 'text-black hover:bg-gray-200' }}">
                Pending
            </a>
            <a href="{{ route('agent.dashboard', ['status' => 'replied', 'search' => request('search')]) }}"
               class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-md text-xs font-semibold {{ request('status') == 'replied' ? 'bg-blue-600 text-white' : 'text-black hover:bg-gray-200' }}">
                Replied
            </a>
        </div>

        <form method="GET" action="{{ route('agent.dashboard') }}" class="w-full md:w-72 flex items-center">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" id="search-input" name="search" value="{{ request('search') }}" placeholder="Search name, email, ref..."
                   oninput="debounceSearch(this.form)"
                   class="w-full px-3 py-2 rounded-md border border-gray-300 text-xs sm:text-sm text-black focus:outline-none focus:border-blue-500">
        </form>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-gray-50 text-[11px] sm:text-xs uppercase font-semibold text-gray-500 border-b border-gray-200">
                    <tr>
                        <th class="py-2.5 sm:py-3 px-3 sm:px-4">Status</th>
                        <th class="py-2.5 sm:py-3 px-3 sm:px-4">Reference</th>
                        <th class="py-2.5 sm:py-3 px-3 sm:px-4">Customer</th>
                        <th class="py-2.5 sm:py-3 px-3 sm:px-4">Contact</th>
                        <th class="py-2.5 sm:py-3 px-3 sm:px-4">Date</th>
                        <th class="py-2.5 sm:py-3 px-3 sm:px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tickets as $ticket)
                        <tr id="ticket-row-{{ $ticket->id }}" class="hover:bg-gray-50 {{ !$ticket->is_opened ? 'bg-blue-50/50' : '' }}">
                            <td class="py-2.5 sm:py-3 px-3 sm:px-4 whitespace-nowrap">
                                @if(!$ticket->is_opened)
                                    <span class="px-2 py-0.5 sm:py-1 rounded text-[10px] font-bold uppercase bg-blue-600 text-white">New</span>
                                @elseif($ticket->status === 'replied')
                                    <span class="px-2 py-0.5 sm:py-1 rounded text-[10px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-200">Replied</span>
                                @else
                                    <span class="px-2 py-0.5 sm:py-1 rounded text-[10px] font-bold uppercase bg-gray-100 text-black border border-gray-300">Pending</span>
                                @endif
                            </td>
                            <td class="py-2.5 sm:py-3 px-3 sm:px-4 font-mono font-semibold text-blue-700 whitespace-nowrap text-xs sm:text-sm">{{ $ticket->reference_number }}</td>
                            <td class="py-2.5 sm:py-3 px-3 sm:px-4 font-medium text-black whitespace-nowrap">{{ $ticket->customer_name }}</td>
                            <td class="py-2.5 sm:py-3 px-3 sm:px-4 text-[11px] sm:text-xs text-gray-500 whitespace-nowrap">
                                <div>{{ $ticket->email }}</div>
                                <div>{{ $ticket->phone_number }}</div>
                            </td>
                            <td class="py-2.5 sm:py-3 px-3 sm:px-4 text-[11px] sm:text-xs text-gray-500 whitespace-nowrap">{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                            <td class="py-2.5 sm:py-3 px-3 sm:px-4 text-right whitespace-nowrap">
                                <button onclick="openTicketModal({{ $ticket->id }})"
                                        class="px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs">
                                    View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500 text-sm">No tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 sm:p-4 border-t border-gray-200">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
</div>

<!-- TICKET MODAL -->
<div id="ticket-modal" class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 bg-black/50 hidden">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[92vh] flex flex-col border border-gray-200 shadow-xl">


        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 id="modal-ref-header" class="text-lg font-bold font-mono text-blue-700"></h3>
                <p class="text-xs text-gray-500">Submitted by <span id="modal-customer-name" class="text-black font-semibold"></span></p>
            </div>
            <button onclick="closeTicketModal()" class="p-2 rounded-md text-gray-500 hover:bg-gray-100">✕</button>
        </div>

        <div class="p-3 sm:p-5 space-y-4 sm:space-y-5 overflow-y-auto flex-grow">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-3 rounded-lg bg-gray-50 border border-gray-200 text-xs">
                <div>
                    <span class="text-gray-500 uppercase font-semibold block">Email</span>
                    <span id="modal-email" class="text-black"></span>
                </div>
                <div>
                    <span class="text-gray-500 uppercase font-semibold block">Phone</span>
                    <span id="modal-phone" class="text-black"></span>
                </div>
                <div>
                    <span class="text-gray-500 uppercase font-semibold block">Status</span>
                    <span id="modal-status-badge" class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase"></span>
                </div>
            </div>

            <div class="space-y-2">
                <h4 class="text-xs font-bold text-gray-500 uppercase">Problem Description</h4>
                <div id="modal-problem-description" class="p-3 rounded-lg bg-gray-50 border border-gray-200 text-sm text-black whitespace-pre-wrap"></div>
            </div>

            <div class="space-y-3">
                <h4 class="text-xs font-bold text-gray-500 uppercase flex items-center gap-2">
                    Reply History
                    <span id="modal-reply-count" class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-xs">0</span>
                </h4>
                <div id="modal-replies-timeline" class="space-y-2"></div>
            </div>

            <div class="pt-3 border-t border-gray-200 space-y-2">
                <h4 class="text-xs font-bold text-blue-700 uppercase">Post Reply</h4>
                <form id="agent-reply-form" onsubmit="submitAgentReply(event)" class="space-y-2">
                    @csrf
                    <input type="hidden" id="current-ticket-id">
                    <textarea id="reply-message" name="message" rows="3" required placeholder="Type your response..."
                              class="w-full px-3 py-2 rounded-md border border-gray-300 text-sm text-black focus:outline-none focus:border-blue-500 resize-none"></textarea>
                    <div class="flex justify-end">
                        <button type="submit" id="btn-submit-reply" class="px-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs">
                            <span id="btn-reply-text">Send Reply</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let searchTimer = null;
    function debounceSearch(form) {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            form.submit();
        }, 400);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('search-input');
        const urlParams = new URLSearchParams(window.location.search);
        if (searchInput && urlParams.has('search')) {
            searchInput.focus();
            const len = searchInput.value.length;
            searchInput.setSelectionRange(len, len);
        }
    });

    let activeTicketId = null;


    async function openTicketModal(ticketId) {
        activeTicketId = ticketId;
        document.getElementById('current-ticket-id').value = ticketId;

        try {
            const response = await fetch(`/agent/tickets/${ticketId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            const data = await response.json();

            if (response.ok && data.success) {
                const t = data.ticket;
                document.getElementById('modal-ref-header').textContent = t.reference_number;
                document.getElementById('modal-customer-name').textContent = t.customer_name;
                document.getElementById('modal-email').textContent = t.email;
                document.getElementById('modal-phone').textContent = t.phone_number;
                document.getElementById('modal-problem-description').textContent = t.problem_description;

                const badge = document.getElementById('modal-status-badge');
                if (t.status === 'replied') {
                    badge.className = 'inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-200';
                    badge.textContent = 'Replied';
                } else {
                    badge.className = 'inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-gray-100 text-black border border-gray-300';
                    badge.textContent = 'Pending';
                }

                renderRepliesTimeline(t.replies);

                const row = document.getElementById(`ticket-row-${ticketId}`);
                if (row) {
                    row.classList.remove('bg-blue-50/50');
                    const badgeCol = row.querySelector('td:first-child');
                    if (badgeCol && t.status !== 'replied') {
                        badgeCol.innerHTML = `<span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-gray-100 text-black border border-gray-300">Pending</span>`;
                    }
                }

                document.getElementById('ticket-modal').classList.remove('hidden');
            } else {
                alert(data.message || 'Failed loading ticket details.');
            }
        } catch (error) {
            console.error(error);
            alert('Network error while opening ticket.');
        }
    }

    function renderRepliesTimeline(replies) {
        const timeline = document.getElementById('modal-replies-timeline');
        document.getElementById('modal-reply-count').textContent = replies.length;

        if (replies.length === 0) {
            timeline.innerHTML = `<div class="p-3 rounded-lg bg-gray-50 border border-gray-200 text-center text-gray-500 text-xs">No replies sent yet.</div>`;
        } else {
            timeline.innerHTML = replies.map(r => `
                <div class="p-3 rounded-lg bg-blue-50 border border-blue-200 space-y-1">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-blue-700">${r.agent_name}</span>
                        <span class="text-gray-500 text-[11px]">${r.created_at}</span>
                    </div>
                    <p class="text-xs text-black leading-relaxed">${r.message}</p>
                </div>
            `).join('');
        }
    }

    async function submitAgentReply(event) {
        event.preventDefault();
        const ticketId = document.getElementById('current-ticket-id').value;
        const messageInput = document.getElementById('reply-message');
        const message = messageInput.value.trim();
        if (!message) return;

        const btnText = document.getElementById('btn-reply-text');
        const btnSubmit = document.getElementById('btn-submit-reply');
        btnText.textContent = 'Sending...';
        btnSubmit.disabled = true;

        try {
            const response = await fetch(`/agent/tickets/${ticketId}/reply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: message })
            });
            const data = await response.json();

            if (response.ok && data.success) {
                messageInput.value = '';
                openTicketModal(ticketId);

                const row = document.getElementById(`ticket-row-${ticketId}`);
                if (row) {
                    const badgeCol = row.querySelector('td:first-child');
                    if (badgeCol) {
                        badgeCol.innerHTML = `<span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-200">Replied</span>`;
                    }
                }
            } else {
                alert(data.message || 'Failed submitting reply.');
            }
        } catch (error) {
            console.error(error);
            alert('Network error while posting reply.');
        } finally {
            btnText.textContent = 'Send Reply';
            btnSubmit.disabled = false;
        }
    }

    function closeTicketModal() {
        document.getElementById('ticket-modal').classList.add('hidden');
    }
</script>
@endpush