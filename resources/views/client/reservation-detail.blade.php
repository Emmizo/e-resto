@extends('layouts.client')
@section('title', 'Reservation #' . $reservation->id)

@section('style')
<style>
.rd-page { background:#f7f7f5;min-height:calc(100vh - 56px);padding:24px; }
.rd-header { display:flex;align-items:center;gap:14px;margin-bottom:24px; }
.rd-back {
    width:38px;height:38px;border-radius:50%;border:1.5px solid #e5e7eb;background:#fff;
    display:flex;align-items:center;justify-content:center;text-decoration:none;color:#374151;flex-shrink:0;
    transition:all .15s;
}
.rd-back:hover { background:#0f3d45;border-color:#0f3d45;color:#fff; }
.rd-card { background:#fff;border-radius:18px;border:1.5px solid #efefed;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.06);margin-bottom:14px; }
.rd-card-head {
    padding:20px;
    background:linear-gradient(135deg,#0f3d45 0%,#1a6272 100%);
    color:#fff;
}
.rd-section-title { font-size:.7rem;font-weight:800;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em;margin-bottom:12px; }
.rd-body { padding:18px 20px; }
.rd-pill { display:inline-flex;align-items:center;gap:5px;padding:4px 13px;border-radius:99px;font-size:.73rem;font-weight:800; }
.rpill-pending   { background:rgba(245,158,11,.18);color:#fbbf24;border:1.5px solid rgba(245,158,11,.35); }
.rpill-confirmed { background:rgba(52,211,153,.18);color:#6ee7b7;border:1.5px solid rgba(52,211,153,.35); }
.rpill-cancelled { background:rgba(248,113,113,.18);color:#fca5a5;border:1.5px solid rgba(248,113,113,.35); }
.rpill-completed { background:rgba(96,165,250,.18);color:#93c5fd;border:1.5px solid rgba(96,165,250,.35); }
.rd-row { display:flex;align-items:flex-start;gap:8px;font-size:.82rem;color:#374151;margin-bottom:8px; }
.rd-row:last-child { margin-bottom:0; }
.rd-row-icon { width:16px;text-align:center;flex-shrink:0;margin-top:1px;color:#0f3d45; }
.rd-preorder { background:linear-gradient(135deg,#f0f9fa,#e6f3f4);border:1px solid #c5e6ea;border-radius:12px;padding:12px 14px;margin-top:10px; }
.rd-preorder-title { font-size:.7rem;font-weight:800;color:#0f3d45;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px; }
.rd-preorder-item { font-size:.82rem;color:#0f3d45;padding:2px 0; }
</style>
@endsection

@section('content')
<div class="rd-page">

    <div class="rd-header">
        <a href="{{ route('client.my-reservations') }}" class="rd-back">
            <i class="fas fa-arrow-left" style="font-size:.8rem;"></i>
        </a>
        <div>
            <div style="font-size:1.1rem;font-weight:800;color:#111827;">Reservation #{{ $reservation->id }}</div>
            <div style="font-size:.72rem;color:#9ca3af;margin-top:1px;">Booked {{ $reservation->created_at->format('D, d M Y') }}</div>
        </div>
    </div>

    {{-- Status + restaurant header card --}}
    <div class="rd-card">
        <div class="rd-card-head">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                <div>
                    <div style="font-size:.68rem;opacity:.7;text-transform:uppercase;letter-spacing:.07em;margin-bottom:3px;">Restaurant</div>
                    <div style="font-size:1rem;font-weight:800;">{{ $reservation->restaurant->name ?? '—' }}</div>
                    @if($reservation->restaurant->address ?? null)
                    <div style="font-size:.75rem;opacity:.75;margin-top:2px;">
                        <i class="fas fa-location-dot me-1"></i>{{ $reservation->restaurant->address }}
                    </div>
                    @endif
                </div>
                @php
                    $pillClass = ['pending'=>'rpill-pending','confirmed'=>'rpill-confirmed','cancelled'=>'rpill-cancelled','completed'=>'rpill-completed'][$reservation->status] ?? 'rpill-pending';
                    $pillLabel = ['pending'=>'⏳ Pending','confirmed'=>'✓ Confirmed','cancelled'=>'✕ Cancelled','completed'=>'✅ Completed'][$reservation->status] ?? ucfirst($reservation->status);
                @endphp
                <span class="rd-pill {{ $pillClass }}">{{ $pillLabel }}</span>
            </div>
        </div>
        <div class="rd-body">
            <div class="rd-section-title"><i class="fas fa-calendar-check me-1"></i>Booking details</div>
            <div class="rd-row">
                <i class="fas fa-calendar-day rd-row-icon"></i>
                <span>{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('D, d M Y') }}</span>
            </div>
            <div class="rd-row">
                <i class="fas fa-clock rd-row-icon"></i>
                <span>{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}</span>
            </div>
            <div class="rd-row">
                <i class="fas fa-users rd-row-icon"></i>
                <span>{{ $reservation->party_size }} {{ Str::plural('guest', $reservation->party_size) }}</span>
            </div>
        </div>
    </div>

    {{-- Special requests + pre-order --}}
    @php
        $rawNotes    = $reservation->special_requests ?? '';
        $preorderText = null;
        $userNotes   = $rawNotes;
        if (str_contains($rawNotes, 'Pre-ordered food:')) {
            $parts     = explode('Pre-ordered food:', $rawNotes, 2);
            $userNotes = trim($parts[0]);
            $preorderText = trim($parts[1]);
        }
    @endphp

    @if($userNotes || $preorderText)
    <div class="rd-card">
        <div class="rd-body">
            @if($userNotes)
            <div class="rd-section-title"><i class="fas fa-note-sticky me-1"></i>Notes</div>
            <div class="rd-row">
                <i class="fas fa-comment rd-row-icon" style="color:#9ca3af;"></i>
                <span style="color:#6b7280;">{{ $userNotes }}</span>
            </div>
            @endif

            @if($preorderText)
            <div class="rd-preorder {{ $userNotes ? 'mt-3' : '' }}">
                <div class="rd-preorder-title"><i class="fas fa-utensils me-1"></i>Pre-ordered food</div>
                @foreach(explode(',', $preorderText) as $line)
                    @php $line = trim($line); @endphp
                    @if($line)
                    <div class="rd-preorder-item">• {{ $line }}</div>
                    @endif
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endif

</div>
@endsection
