<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\TurnSession;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TurnController extends Controller
{
    // ── GET /admin/turn/status ────────────────────────────────────────────

    public function status()
    {
        $turn = TurnSession::anyOpen();

        return response()->json([
            'open'       => (bool) $turn,
            'turn'       => $turn ? [
                'id'          => $turn->id,
                'user'        => $turn->user->name ?? 'N/A',
                'opened_at'   => $turn->opened_at->toIso8601String(),
                'duration'    => $turn->duration(),
                'tx_count'    => $turn->transaction_count,
            ] : null,
        ]);
    }

    // ── POST /admin/turn/open ─────────────────────────────────────────────

    public function open(Request $request)
    {
        $existing = TurnSession::anyOpen();
        if ($existing) {
            return response()->json([
                'error'   => 'Ya hay un turno abierto.',
                'turn_id' => $existing->id,
            ], 409);
        }

        $request->validate([
            'opening_notes'     => 'nullable|string|max:500',
            'initial_balances'  => 'nullable|array',
        ]);

        $turn = TurnSession::create([
            'user_id'          => Auth::id(),
            'status'           => 'open',
            'opened_at'        => now(),
            'opening_notes'    => $request->input('opening_notes'),
            'initial_balances' => $request->input('initial_balances', $this->currentBalances()),
        ]);

        return response()->json([
            'message'  => 'Turno abierto correctamente.',
            'turn_id'  => $turn->id,
            'opened_at'=> $turn->opened_at->toIso8601String(),
        ], 201);
    }

    // ── POST /admin/turn/close ────────────────────────────────────────────

    public function close(Request $request)
    {
        $turn = TurnSession::anyOpen();
        if (!$turn) {
            return response()->json(['error' => 'No hay turno abierto.'], 404);
        }

        $request->validate([
            'closing_notes'    => 'nullable|string|max:500',
            'closing_balances' => 'nullable|array',
        ]);

        $txCount = Transaction::where('created_at', '>=', $turn->opened_at)->count();

        $turn->update([
            'status'            => 'closed',
            'closed_at'         => now(),
            'closing_notes'     => $request->input('closing_notes'),
            'closing_balances'  => $request->input('closing_balances', $this->currentBalances()),
            'transaction_count' => $txCount,
        ]);

        return response()->json([
            'message'        => 'Turno cerrado correctamente.',
            'turn_id'        => $turn->id,
            'transaction_count' => $txCount,
            'duration'       => $turn->duration(),
        ]);
    }

    // ── GET /admin/turn/history ───────────────────────────────────────────

    public function history()
    {
        $turns = TurnSession::with('user:id,name')
            ->latest('opened_at')
            ->limit(30)
            ->get()
            ->map(fn($t) => [
                'id'                => $t->id,
                'user'              => $t->user->name ?? 'N/A',
                'status'            => $t->status,
                'opened_at'         => $t->opened_at->format('Y-m-d H:i'),
                'closed_at'         => $t->closed_at?->format('Y-m-d H:i'),
                'transaction_count' => $t->transaction_count,
                'duration'          => $t->duration(),
            ]);

        return response()->json($turns);
    }

    // ── Private ───────────────────────────────────────────────────────────

    private function currentBalances(): array
    {
        return Cash::active()
            ->get(['name', 'buy', 'sell'])
            ->keyBy('name')
            ->map(fn($c) => ['buy' => $c->buy, 'sell' => $c->sell])
            ->toArray();
    }
}
