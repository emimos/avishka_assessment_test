<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentService;
use App\Services\TicketService;
use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Mail\TicketRepliedMail;


class AgentDashboardController extends Controller
{
    protected $agentService;
    protected $ticketService;

    public function __construct(AgentService $agentService, TicketService $ticketService) {
        $this->agentService = $agentService;
        $this->ticketService = $ticketService;
    }


    public function index(Request $request) {
        $tickets = $this->agentService->filterTickets($request);
        return view('agent.dashboard.index', $tickets);
    }

    public function show(Ticket $ticket, Request $request)
    {
        // Mark as opened if viewing for the first time
        if (!$ticket->is_opened) {
            $ticket->update([
                'is_opened' => true,
                'opened_at' => now(),
            ]);
        }

        $ticket->load(['replies.user']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'ticket' => [
                    'id' => $ticket->id,
                    'reference_number' => $ticket->reference_number,
                    'customer_name' => $ticket->customer_name,
                    'email' => $ticket->email,
                    'phone_number' => $ticket->phone_number,
                    'problem_description' => $ticket->problem_description,
                    'status' => $ticket->status,
                    'is_opened' => $ticket->is_opened,
                    'created_at' => $ticket->created_at->format('M d, Y H:i'),
                    'replies' => $ticket->replies->map(function ($reply) {
                        return [
                            'id' => $reply->id,
                            'agent_name' => $reply->user ? $reply->user->name : 'Customer (Guest)',
                            'is_customer' => $reply->user_id === null,
                            'message' => $reply->message,
                            'created_at' => $reply->created_at->format('M d, Y H:i'),
                        ];
                    }),

                ],
            ]);
        }

        return view('agent.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string|min:3',
        ]);

        $reply = $this->agentService->replyTicket($ticket, $validated);

        // Send email to customer
        try {
            Mail::to($ticket->email)->send(new TicketRepliedMail($ticket, $reply));
        } catch (\Exception $e) {
            Log::error("Failed sending ticket reply email: " . $e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Reply posted and emailed to customer successfully!',
                'reply' => [
                    'id' => $reply->id,
                    'agent_name' => Auth::user()->name,
                    'message' => $reply->message,
                    'created_at' => $reply->created_at->format('M d, Y H:i'),
                ],
                'status' => 'Replied',
            ]);
        }

        return redirect()->back()->with('success', 'Reply sent successfully!');
    }
    
}
