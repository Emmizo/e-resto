@extends('layouts.client')
@section('title', 'My Reservations')

@section('style')
<style>
.res-page { background:#f7f7f5;min-height:calc(100vh - 56px);padding:24px; }
.res-page-header { display:flex;align-items:center;gap:14px;margin-bottom:24px; }
.res-page-header .back-circle {
    width:38px;height:38px;border-radius:50%;border:1.5px solid #e5e7eb;background:#fff;
    display:flex;align-items:center;justify-content:center;text-decoration:none;color:#374151;flex-shrink:0;
    transition:all .15s;
}
.res-page-header .back-circle:hover { background:#0f3d45;border-color:#0f3d45;color:#fff; }
.res-page-header h5 { font-size:1.25rem;font-weight:800;color:#111827;margin:0; }
.res-empty { text-align:center;padding:60px 20px; }
.res-empty .empty-icon { font-size:3.5rem;opacity:.18;margin-bottom:16px;display:block; }
.res-empty p { color:#9ca3af;font-size:.9rem;margin-bottom:20px; }
.res-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px; }

.res-card {
    background:#fff;border-radius:18px;border:1.5px solid #efefed;overflow:hidden;
    box-shadow:0 2px 12px rgba(0,0,0,.05);transition:box-shadow .18s,transform .18s;
}
.res-card:hover { box-shadow:0 6px 28px rgba(0,0,0,.1);transform:translateY(-1px); }
.res-card-head {
    background:linear-gradient(135deg,#0f3d45,#1a5c6a);
    padding:16px 18px;display:flex;align-items:flex-start;justify-content:space-between;
}
.res-card-rest { color:#fff;font-size:.9rem;font-weight:800;margin-bottom:2px; }
.res-card-id { color:rgba(255,255,255,.6);font-size:.68rem; }
.res-status-pill {
    display:inline-flex;align-items:center;
    padding:3px 11px;border-radius:99px;font-size:.68rem;font-weight:800;flex-shrink:0;
}
.rpill-pending   { background:rgba(245,158,11,.25);color:#fcd34d;border:1px solid rgba(245,158,11,.4); }
.rpill-confirmed { background:rgba(34,197,94,.25);color:#86efac;border:1px solid rgba(34,197,94,.4); }
.rpill-cancelled { background:rgba(239,68,68,.25);color:#fca5a5;border:1px solid rgba(239,68,68,.4); }
.rpill-completed { background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3); }
.res-card-body { padding:16px 18px; }
.res-info-row {
    display:flex;align-items:flex-start;gap:10px;
    font-size:.78rem;color:#374151;margin-bottom:8px;
}
.res-info-row:last-child { margin-bottom:0; }
.res-info-icon { width:16px;text-align:center;flex-shrink:0;color:#0f3d45;margin-top:1px;font-size:.78rem; }
.res-preorder {
    margin:0 18px 14px;background:#f0f9fa;border:1px solid #c5e6ea;
    border-radius:10px;padding:10px 12px;
}
.res-preorder-title { font-size:.68rem;font-weight:800;color:#0f3d45;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px; }
.res-preorder-item { font-size:.75rem;color:#374151;margin-bottom:3px; }
.res-preorder-item:last-child { margin-bottom:0; }
</style>
@endsection

@section('content')
<div class="res-page">

    <div class="res-page-header">
        <a href="{{ route('client.restaurants') }}" class="back-circle">
            <i class="fas fa-arrow-left" style="font-size:.8rem;"></i>
        </a>
        <div>
            <h5>My Reservations</h5>
            @if(!$reservations->isEmpty())
            <div style="font-size:.76rem;color:#9ca3af;margin-top:1px;">{{ $reservations->count() }} reservation{{ $reservations->count() !== 1 ? 's' : '' }}</div>
            @endif
        </div>
    </div>

    @if($reservations->isEmpty())
    <div class="res-empty">
        <span class="empty-icon">🗓️</span>
        <p>You haven't made any reservations yet.</p>
        <a href="{{ route('client.restaurants') }}" class="btn rounded-pill px-5 py-2"
            style="background:#0f3d45;color:#fff;font-weight:700;font-size:.85rem;box-shadow:0 4px 14px rgba(15,61,69,.3);">
            Find a Restaurant
        </a>
    </div>
    @else
    <div class="res-grid">
        @foreach($reservations as $r)
        @php
            $pillClass = [
                'pending'   => 'rpill-pending',
                'confirmed' => 'rpill-confirmed',
                'cancelled' => 'rpill-cancelled',
                'completed' => 'rpill-completed',
            ][$r->status] ?? 'rpill-pending';

            $pillIcon = [
                'pending'   => '⏳',
                'confirmed' => '✅',
                'cancelled' => '✕',
                'completed' => '🎉',
            ][$r->status] ?? '·';

            // Extract pre-order from special_requests if any
            $specialText = $r->special_requests ?? '';
            $preorderText = null;
            $userNotes = $specialText;
            if (str_contains($specialText, 'Pre-ordered food:')) {
                $parts = explode('Pre-ordered food:', $specialText, 2);
                $userNotes = trim($parts[0]);
                $preorderText = trim($parts[1] ?? '');
            }
        @endphp
        <div class="res-card">
            <div class="res-card-head">
                <div>
                    <div class="res-card-rest">{{ $r->restaurant->name ?? 'Restaurant' }}</div>
                    <div class="res-card-id">Reservation #{{ $r->id }}</div>
                </div>
                <span class="res-status-pill {{ $pillClass }}">{{ $pillIcon }} {{ ucfirst($r->status) }}</span>
            </div>
            <div class="res-card-body">
                <div class="res-info-row">
                    <i class="fas fa-calendar-day res-info-icon"></i>
                    <span>{{ \Carbon\Carbon::parse($r->reservation_time)->format('D, d M Y · H:i') }}</span>
                </div>
                <div class="res-info-row">
                    <i class="fas fa-users res-info-icon"></i>
                    <span>{{ $r->number_of_people }} guest{{ $r->number_of_people != 1 ? 's' : '' }}</span>
                </div>
                <div class="res-info-row">
                    <i class="fas fa-phone res-info-icon"></i>
                    <span>{{ $r->phone_number }}</span>
                </div>
                @if($userNotes)
                <div class="res-info-row">
                    <i class="fas fa-note-sticky res-info-icon"></i>
                    <span>{{ $userNotes }}</span>
                </div>
                @endif
            </div>
            @if($preorderText)
            <div class="res-preorder">
                <div class="res-preorder-title"><i class="fas fa-utensils me-1"></i>Pre-ordered food</div>
                @foreach(explode(',', $preorderText) as $poItem)
                <div class="res-preorder-item">
                    <i class="fas fa-circle-dot me-1" style="font-size:.5rem;color:#9ccdd2;vertical-align:middle;"></i>
                    {{ trim($poItem) }}
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
