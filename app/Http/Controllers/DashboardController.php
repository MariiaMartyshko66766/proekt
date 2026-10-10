<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $isStaff = $user->isAdmin() || $user->isAgent();

        // zwykły użytkownik widzi tylko własne dane
        $base = Ticket::query();
        if (! $isStaff) {
            $base->where('user_id', $user->id);
        }

        $byStatus = (clone $base)->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')->pluck('total', 'status');
        $byPriority = (clone $base)->selectRaw('priority, COUNT(*) as total')
            ->groupBy('priority')->pluck('total', 'priority');

        $statusCounts = collect(Ticket::statuses())
            ->mapWithKeys(fn ($s) => [$s => (int) ($byStatus[$s] ?? 0)]);
        $priorityCounts = collect(Ticket::priorities())
            ->mapWithKeys(fn ($p) => [$p => (int) ($byPriority[$p] ?? 0)]);

        $openStatuses = [Ticket::STATUS_NEW, Ticket::STATUS_IN_PROGRESS, Ticket::STATUS_WAITING];

        $agentLoad = collect();
        $unassigned = 0;

        if ($isStaff) {
            $agentLoad = User::where('role', User::ROLE_AGENT)
                ->withCount(['assignedTickets as open_tickets' => fn ($q) => $q->whereIn('status', $openStatuses)])
                ->orderBy('name')
                ->get();

            $unassigned = Ticket::whereNull('agent_id')
                ->where('status', Ticket::STATUS_NEW)
                ->count();
        }

        return view('dashboard', [
            'isStaff' => $isStaff,
            'total' => $statusCounts->sum(),
            'statusCounts' => $statusCounts,
            'priorityCounts' => $priorityCounts,
            'agentLoad' => $agentLoad,
            'unassigned' => $unassigned,
        ]);
    }
}