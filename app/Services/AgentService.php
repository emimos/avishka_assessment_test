<?php
namespace App\Services;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class AgentService
{

    function filterTickets(Request $request){

        $query = Ticket::query();
        // Search by customer name, email, phone number, or reference number
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }


        // Filter by status 
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $status = $request->input('status');
            if ($status === 'new') {
                $query->where('is_opened', false);
            } else {
                $query->where('status', $status);
            }
        }

        $tickets = $query->orderBy('is_opened', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
        $stats = [
            'total' => Ticket::count(),
            'pending' => Ticket::where('status', 'pending')->count(),
            'new_unopened' => Ticket::where('is_opened', false)->count(),
            'replied' => Ticket::where('status', 'replied')->count(),
        ];

        return[
            'tickets' => $tickets,
            'stats' => $stats
        ];

    }

    public function replyTicket($ticket, $validatedData)
    {
        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validatedData['message'],
        ]);

        $ticket->update([
            'status' => 'replied',
            'is_opened' => true,
        ]);

        return $reply;
    }

}

?>