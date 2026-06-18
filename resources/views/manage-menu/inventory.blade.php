@extends('layouts.app')

@section('style')
<style>
.inv-header {
    background: linear-gradient(135deg, #0f3039 0%, #184C55 60%, #1a3a5c 100%);
    border-radius: 16px;
    padding: 24px 28px;
    margin-bottom: 24px;
    color: #fff;
}
.inv-stat-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e8edf2;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
}
.inv-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.inv-table-wrap {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e8edf2;
    overflow: hidden;
}
.inv-table thead th {
    background: #f8fafc;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748b;
    border-bottom: 1px solid #e8edf2;
    padding: 12px 16px;
    white-space: nowrap;
}
.inv-table tbody td {
    padding: 12px 16px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    font-size: 0.85rem;
}
.inv-table tbody tr:last-child td { border-bottom: none; }
.inv-table tbody tr:hover td { background: #fafbfc; }

.sold-bar-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
}
.sold-bar {
    flex: 1;
    height: 6px;
    background: #e8edf2;
    border-radius: 3px;
    overflow: hidden;
    min-width: 60px;
}
.sold-bar-fill {
    height: 100%;
    border-radius: 3px;
    background: linear-gradient(90deg, #184C55, #0ea5e9);
    transition: width 0.4s ease;
}

.stock-input-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
}
.stock-input {
    width: 80px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    padding: 4px 8px;
    font-size: 0.82rem;
    color: #1e293b;
    text-align: center;
    outline: none;
    transition: border-color 0.15s;
}
.stock-input:focus { border-color: #184C55; }
.stock-save-btn {
    width: 28px;
    height: 28px;
    border: none;
    border-radius: 7px;
    background: #dcfce7;
    color: #16a34a;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.75rem;
    transition: background 0.15s;
}
.stock-save-btn:hover { background: #bbf7d0; }

.track-toggle {
    position: relative;
    display: inline-block;
    width: 38px;
    height: 22px;
    cursor: pointer;
}
.track-toggle input { display: none; }
.track-slider {
    position: absolute;
    inset: 0;
    background: #cbd5e1;
    border-radius: 11px;
    transition: background 0.2s;
}
.track-slider::before {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #fff;
    top: 3px;
    left: 3px;
    transition: transform 0.2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
}
.track-toggle input:checked + .track-slider { background: #184C55; }
.track-toggle input:checked + .track-slider::before { transform: translateX(16px); }

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}
.status-pill.available   { background: #dcfce7; color: #16a34a; }
.status-pill.unavailable { background: #fee2e2; color: #dc2626; }
.status-pill.unlimited   { background: #dbeafe; color: #2563eb; }
.status-pill.low-stock   { background: #fef9c3; color: #b45309; }

.item-thumb {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
}
.item-thumb-ph {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #94a3b8;
    font-size: 0.9rem;
}
.inv-filter-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}
.inv-filter-pill {
    padding: 5px 14px;
    border-radius: 20px;
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    font-size: 0.78rem;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    transition: all 0.15s;
}
.inv-filter-pill.active, .inv-filter-pill:hover {
    border-color: #184C55;
    background: #184C55;
    color: #fff;
}
.inv-search {
    flex: 1;
    min-width: 180px;
    max-width: 280px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 6px 12px 6px 34px;
    font-size: 0.82rem;
    outline: none;
    background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'/%3E%3C/svg%3E") no-repeat 10px center;
}
.inv-search:focus { border-color: #184C55; }
</style>
@endsection

@section('content')
<main class="content-wrapper">
<div class="main-content">

    {{-- Breadcrumb --}}
    <div class="breadcrumb-section mb-2 mb-xl-4">
        <ul class="breadcrumb-lists d-flex align-items-center flex-wrap">
            <li class="breadcrumb-item position-relative">
                <a href="{{ route('dashboard') }}" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1">Home</a>
            </li>
            <li class="breadcrumb-item position-relative">
                <a href="{{ route('manage-menu') }}" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1">Manage Menu</a>
            </li>
            <li class="breadcrumb-item position-relative">
                <span class="font-dmsans fw-medium xsmall text-primary-v1">Inventory</span>
            </li>
        </ul>
    </div>

    {{-- Header --}}
    <div class="inv-header d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="font-size:1.25rem;">Inventory Management</h4>
            <p class="mb-0 opacity-75" style="font-size:0.82rem;">Track stock levels, units sold, and availability for all menu items.</p>
        </div>
        <a href="{{ route('manage-menu') }}" class="btn btn-sm d-flex align-items-center gap-2"
           style="background:rgba(255,255,255,0.15);color:#fff;border:1.5px solid rgba(255,255,255,0.3);border-radius:10px;padding:8px 16px;font-size:0.8rem;font-weight:600;">
            <i class="fas fa-arrow-left" style="font-size:0.75rem;"></i> Back to Menu
        </a>
    </div>

    {{-- Stats --}}
    @php
        $totalItems   = $items->count();
        $totalSold    = $items->sum('total_sold');
        $lowStock     = $items->filter(fn($i) => $i->track_inventory && $i->stock_quantity !== null && $i->stock_quantity <= 5 && $i->stock_quantity > 0)->count();
        $outOfStock   = $items->filter(fn($i) => $i->track_inventory && $i->stock_quantity !== null && $i->stock_quantity == 0)->count();
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="inv-stat-card">
                <div class="inv-stat-icon" style="background:#ede9fe;">
                    <i class="fas fa-box" style="color:#7c3aed;"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:1.3rem;color:#1e293b;">{{ $totalItems }}</div>
                    <div style="font-size:0.72rem;color:#64748b;font-weight:600;">Total Items</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="inv-stat-card">
                <div class="inv-stat-icon" style="background:#dcfce7;">
                    <i class="fas fa-shopping-bag" style="color:#16a34a;"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:1.3rem;color:#1e293b;">{{ number_format($totalSold) }}</div>
                    <div style="font-size:0.72rem;color:#64748b;font-weight:600;">Units Sold</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="inv-stat-card">
                <div class="inv-stat-icon" style="background:#fef9c3;">
                    <i class="fas fa-exclamation-triangle" style="color:#b45309;"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:1.3rem;color:#1e293b;">{{ $lowStock }}</div>
                    <div style="font-size:0.72rem;color:#64748b;font-weight:600;">Low Stock (&le;5)</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="inv-stat-card">
                <div class="inv-stat-icon" style="background:#fee2e2;">
                    <i class="fas fa-times-circle" style="color:#dc2626;"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:1.3rem;color:#1e293b;">{{ $outOfStock }}</div>
                    <div style="font-size:0.72rem;color:#64748b;font-weight:600;">Out of Stock</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="inv-filter-bar">
        <button class="inv-filter-pill active" data-filter="all">All Items</button>
        <button class="inv-filter-pill" data-filter="tracked">Tracked</button>
        <button class="inv-filter-pill" data-filter="low">Low Stock</button>
        <button class="inv-filter-pill" data-filter="out">Out of Stock</button>
        <input type="text" class="inv-search" id="invSearch" placeholder="Search items...">
    </div>

    {{-- Table --}}
    <div class="inv-table-wrap">
        @if($items->isEmpty())
            <div class="text-center py-5">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                     style="width:64px;height:64px;background:#f1f5f9;">
                    <i class="fas fa-box-open fa-lg text-muted"></i>
                </div>
                <p class="text-muted fw-semibold mb-1">No menu items found</p>
                <p class="text-muted small">Add items to your menu first.</p>
            </div>
        @else
        <table class="table inv-table mb-0" id="invTable">
            <thead>
                <tr>
                    <th style="width:36px;">#</th>
                    <th>Item</th>
                    @if(auth()->user()->role === 'admin')
                    <th>Restaurant</th>
                    @endif
                    <th>Category</th>
                    <th>Price</th>
                    <th>Total Sold</th>
                    <th>Track Stock</th>
                    <th>Stock Qty</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $maxSold = $items->max('total_sold') ?: 1; @endphp
                @foreach($items as $i => $item)
                @php
                    $stockStatus = 'unlimited';
                    if ($item->track_inventory && $item->stock_quantity !== null) {
                        if ($item->stock_quantity == 0) $stockStatus = 'out';
                        elseif ($item->stock_quantity <= 5) $stockStatus = 'low';
                        else $stockStatus = 'ok';
                    }
                    $filterAttr = $item->track_inventory ? 'tracked' : 'untracked';
                    if ($stockStatus === 'low') $filterAttr .= ' low';
                    if ($stockStatus === 'out') $filterAttr .= ' out';
                @endphp
                <tr data-filter="{{ $filterAttr }}" data-name="{{ strtolower($item->name) }}">
                    <td class="text-muted" style="font-size:0.75rem;">{{ $i + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($item->image)
                                <img src="{{ $item->image }}" alt="{{ $item->name }}" class="item-thumb">
                            @else
                                <div class="item-thumb-ph"><i class="fas fa-utensils"></i></div>
                            @endif
                            <div>
                                <div class="fw-semibold text-dark" style="font-size:0.85rem;">{{ $item->name }}</div>
                                @if($item->description)
                                <div class="text-muted" style="font-size:0.72rem;">{{ Str::limit($item->description, 40) }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    @if(auth()->user()->role === 'admin')
                    <td>
                        <span class="badge rounded-pill" style="background:#f1f5f9;color:#475569;font-size:0.7rem;">
                            {{ optional(optional($item->menu)->restaurant)->name ?? '—' }}
                        </span>
                    </td>
                    @endif
                    <td>
                        @if($item->category)
                        <span class="badge rounded-pill" style="background:{{ $item->category === 'Food' ? '#fef9c3;color:#854d0e' : '#dbeafe;color:#1e40af' }};font-size:0.7rem;">
                            {{ $item->category }}
                        </span>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="fw-semibold" style="color:#0f3039;">RWF {{ number_format($item->price, 0) }}</td>
                    <td>
                        <div class="sold-bar-wrap">
                            <span class="fw-bold" style="font-size:0.85rem;min-width:28px;color:#184C55;">{{ number_format($item->total_sold) }}</span>
                            <div class="sold-bar">
                                <div class="sold-bar-fill" style="width:{{ $maxSold > 0 ? round(($item->total_sold / $maxSold) * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <label class="track-toggle" title="{{ $item->track_inventory ? 'Tracking enabled' : 'Click to enable tracking' }}">
                            <input type="checkbox" class="track-cb" data-id="{{ $item->id }}"
                                   {{ $item->track_inventory ? 'checked' : '' }}>
                            <span class="track-slider"></span>
                        </label>
                    </td>
                    <td>
                        <div class="stock-input-wrap track-row-{{ $item->id }}" style="{{ !$item->track_inventory ? 'opacity:0.35;pointer-events:none;' : '' }}">
                            <input type="number" min="0"
                                   class="stock-input"
                                   data-id="{{ $item->id }}"
                                   value="{{ $item->stock_quantity ?? '' }}"
                                   placeholder="{{ $item->stock_quantity === null ? '∞' : '' }}"
                                   title="Leave blank for unlimited">
                            <button class="stock-save-btn" data-id="{{ $item->id }}" title="Save">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </td>
                    <td>
                        @if($stockStatus === 'out')
                            <span class="status-pill unavailable"><i class="fas fa-circle" style="font-size:0.45rem;"></i> Out of stock</span>
                        @elseif($stockStatus === 'low')
                            <span class="status-pill low-stock"><i class="fas fa-circle" style="font-size:0.45rem;"></i> Low ({{ $item->stock_quantity }})</span>
                        @elseif($stockStatus === 'unlimited')
                            <span class="status-pill unlimited"><i class="fas fa-infinity" style="font-size:0.6rem;"></i> Unlimited</span>
                        @else
                            <span class="status-pill available"><i class="fas fa-circle" style="font-size:0.45rem;"></i> In stock ({{ $item->stock_quantity }})</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>
</main>
@endsection

@section('script')
<script>
(function() {
    var csrf = document.querySelector('meta[name="csrf-token"]').content;

    // ── Filter pills ──────────────────────────────────────────────
    document.querySelectorAll('.inv-filter-pill').forEach(function(pill) {
        pill.addEventListener('click', function() {
            document.querySelectorAll('.inv-filter-pill').forEach(function(p) { p.classList.remove('active'); });
            this.classList.add('active');
            filterTable();
        });
    });

    document.getElementById('invSearch').addEventListener('input', filterTable);

    function filterTable() {
        var filter = document.querySelector('.inv-filter-pill.active').dataset.filter;
        var search = document.getElementById('invSearch').value.toLowerCase().trim();
        document.querySelectorAll('#invTable tbody tr').forEach(function(row) {
            var attrs = (row.dataset.filter || '').split(' ');
            var name  = row.dataset.name || '';
            var matchFilter = filter === 'all'
                || (filter === 'tracked'  && attrs.includes('tracked'))
                || (filter === 'low'      && attrs.includes('low'))
                || (filter === 'out'      && attrs.includes('out'));
            var matchSearch = !search || name.includes(search);
            row.style.display = (matchFilter && matchSearch) ? '' : 'none';
        });
    }

    // ── Track inventory toggle ────────────────────────────────────
    document.querySelectorAll('.track-cb').forEach(function(cb) {
        cb.addEventListener('change', function() {
            var id = this.dataset.id;
            var enabled = this.checked;
            var stockRow = document.querySelector('.track-row-' + id);
            if (stockRow) {
                stockRow.style.opacity = enabled ? '1' : '0.35';
                stockRow.style.pointerEvents = enabled ? '' : 'none';
            }
            patchInventory(id, { track_inventory: enabled ? 1 : 0 });
        });
    });

    // ── Save stock quantity ───────────────────────────────────────
    document.querySelectorAll('.stock-save-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.dataset.id;
            var input = document.querySelector('.stock-input[data-id="' + id + '"]');
            var val = input.value.trim();
            patchInventory(id, { stock_quantity: val === '' ? null : parseInt(val) }, function(res) {
                // Update status pill inline
                updateStatusPill(id, res.item);
            });
        });
    });

    // Allow Enter key on stock input
    document.querySelectorAll('.stock-input').forEach(function(inp) {
        inp.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                document.querySelector('.stock-save-btn[data-id="' + this.dataset.id + '"]').click();
            }
        });
    });

    function patchInventory(id, data, callback) {
        fetch('/inventory/' + id, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify(data),
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.status === 200) {
                showToast('Saved', '#16a34a');
                if (callback) callback(res);
            }
        })
        .catch(function() { showToast('Save failed', '#dc2626'); });
    }

    function updateStatusPill(id, item) {
        var row = document.querySelector('.stock-input[data-id="' + id + '"]');
        if (!row) return;
        var tr  = row.closest('tr');
        if (!tr) return;
        var pill = tr.querySelector('.status-pill');
        if (!pill) return;

        var sq = item.stock_quantity;
        var tracked = item.track_inventory;

        if (!tracked || sq === null || sq === undefined) {
            pill.className = 'status-pill unlimited';
            pill.innerHTML = '<i class="fas fa-infinity" style="font-size:0.6rem;"></i> Unlimited';
        } else if (sq == 0) {
            pill.className = 'status-pill unavailable';
            pill.innerHTML = '<i class="fas fa-circle" style="font-size:0.45rem;"></i> Out of stock';
        } else if (sq <= 5) {
            pill.className = 'status-pill low-stock';
            pill.innerHTML = '<i class="fas fa-circle" style="font-size:0.45rem;"></i> Low (' + sq + ')';
        } else {
            pill.className = 'status-pill available';
            pill.innerHTML = '<i class="fas fa-circle" style="font-size:0.45rem;"></i> In stock (' + sq + ')';
        }

        // Update filter data attr
        tr.dataset.filter = tracked ? 'tracked' : 'untracked';
        if (tracked && sq !== null) {
            if (sq == 0) tr.dataset.filter += ' out';
            else if (sq <= 5) tr.dataset.filter += ' low';
        }
    }

    function showToast(msg, color) {
        var t = document.createElement('div');
        t.textContent = msg;
        t.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#fff;border-left:4px solid ' + color + ';'
            + 'padding:10px 18px;border-radius:8px;box-shadow:0 4px 16px rgba(0,0,0,.12);'
            + 'font-size:0.82rem;font-weight:600;color:#1e293b;z-index:9999;'
            + 'animation:slideInToast .25s ease;';
        document.body.appendChild(t);
        setTimeout(function() { t.remove(); }, 2200);
    }

    if (!document.getElementById('invToastAnim')) {
        var s = document.createElement('style');
        s.id = 'invToastAnim';
        s.textContent = '@keyframes slideInToast{from{transform:translateY(16px);opacity:0}to{transform:none;opacity:1}}';
        document.head.appendChild(s);
    }
})();
</script>
@endsection
