<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Presensi 11 RPL B')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&family=Special+Elite&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('storage/logo/logo-beranda.png') }}" alt="Logo 11 RPL B" class="brand-logo-img">
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Menu Utama</div>

            <a href="{{ route('beranda') }}" class="nav-item {{ request()->routeIs('beranda') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Beranda
            </a>

            @if(auth()->user()?->canInputPresensi())
            <a href="{{ route('harian') }}" class="nav-item {{ request()->routeIs('harian*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Presensi Harian
            </a>
            @endif

            <a href="{{ route('riwayat') }}" class="nav-item {{ request()->routeIs('riwayat*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Riwayat
            </a>

            <a href="{{ route('rekap') }}" class="nav-item {{ request()->routeIs('rekap*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan
            </a>

            <a href="{{ route('kelas') }}" class="nav-item {{ request()->routeIs('kelas*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                Data Kelas
            </a>

            @if(auth()->user()?->isAdmin())
            <div class="nav-section-label" style="margin-top:8px">Admin</div>

            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                Kelola User
            </a>


            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                @php $isSiswaPin = session('siswa_pin_verified'); @endphp
                <div class="sidebar-avatar">
                    {{ auth()->user() ? strtoupper(substr(auth()->user()->name, 0, 1)) : ($isSiswaPin ? 'S' : 'GU') }}
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">
                        {{ auth()->user()->name ?? ($isSiswaPin ? 'Siswa' : 'Guru Pengajar') }}
                    </div>
                    <div class="sidebar-user-role">
                        @php $u = auth()->user(); @endphp
                        {{ $u?->isAdmin() ? 'Admin' : ($u?->isGuru() ? 'Wali Kelas 11 RPL B' : ($u?->isPengurusKelas() ? 'Pengurus Kelas' : ($isSiswaPin ? 'Siswa' : ''))) }}
                    </div>
                </div>
                @if($isSiswaPin)
                <form action="{{ route('pin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="icon-btn sidebar-logout-btn" title="Keluar">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
                @else
                <form action="{{ route('logout') }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin keluar?')">
                    @csrf
                    <button class="icon-btn sidebar-logout-btn" title="Keluar">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
                @endif
            </div>
        </div>
    </aside>

    {{-- MAIN WRAPPER --}}
    <div class="app-wrapper">
        {{-- TOPBAR (Desktop) --}}
        <header class="topbar hide-mobile">
            <div class="topbar-title">@yield('page-title', 'Presensi 11 RPL B')</div>
            <div class="topbar-actions">
                <a href="{{ route('riwayat') }}" class="icon-btn" title="Riwayat">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </a>
                @if(auth()->user()?->isAdmin())
                <button type="button" class="icon-btn" onclick="showPinModal()" title="Kelola PIN Siswa">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </button>
                @endif
                @if(session('siswa_pin_verified'))
                <form action="{{ route('pin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="icon-btn" title="Keluar">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
                @else
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="icon-btn" title="Keluar">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
                @endif
            </div>
        </header>

        {{-- MOBILE HEADER --}}
        <header class="mobile-header hide-desktop">
            <div class="flex items-center gap-3">
                <div>
                    <div class="mobile-header-title">@yield('mobile-title', 'Presensi 11 RPL B')</div>
                </div>
            </div>
            <div class="mobile-header-actions">
                <a href="{{ route('riwayat') }}" class="icon-btn" title="Riwayat">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </a>
                @if(auth()->user()?->isAdmin())
                <button type="button" class="icon-btn" onclick="showPinModal()" title="Kelola PIN Siswa">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </button>
                @endif
                @if(session('siswa_pin_verified'))
                <form action="{{ route('pin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="icon-btn" title="Keluar">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
                @else
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="icon-btn" title="Keluar">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
                @endif
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="page-content">
            @yield('content')
        </main>
    </div>

    {{-- BOTTOM NAV (Mobile) --}}
    <nav class="bottom-nav hide-desktop">
        <a href="{{ route('beranda') }}" class="bottom-nav-item {{ request()->routeIs('beranda') ? 'active' : '' }}">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Beranda</span>
        </a>
        @if(auth()->user()?->canInputPresensi())
        <a href="{{ route('harian') }}" class="bottom-nav-item {{ request()->routeIs('harian*') ? 'active' : '' }}">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Harian</span>
        </a>
        @endif
        <a href="{{ route('riwayat') }}" class="bottom-nav-item {{ request()->routeIs('riwayat*') ? 'active' : '' }}">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>Riwayat</span>
        </a>
        <a href="{{ route('rekap') }}" class="bottom-nav-item {{ request()->routeIs('rekap*') ? 'active' : '' }}">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Laporan</span>
        </a>
        <a href="{{ route('kelas') }}" class="bottom-nav-item {{ request()->routeIs('kelas*') ? 'active' : '' }}">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
            <span>Kelas</span>
        </a>
        @if(auth()->user()?->isAdmin())
        <a href="{{ route('admin.users.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
            <span>User</span>
        </a>
        @endif
    </nav>

    @yield('fab')

    @if(auth()->user()?->isAdmin())
    {{-- ===== MODAL GANTI PIN ===== --}}
    <div id="modal-pin" class="modal-overlay" onclick="if(event.target===this)hidePinModal()">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">Ganti PIN Siswa</div>
                <button type="button" class="modal-close" onclick="hidePinModal()">&times;</button>
            </div>

            @php
                $pinUpdatedAt = \App\Models\ClassPin::where('kode_kelas', '11-RPL-B')->value('pin_updated_at');
            @endphp

            <div style="margin-bottom:16px;padding:12px;background:#f5f0ff;border-radius:10px;border:1px solid #d8d0f0;text-align:center">
                <div style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:4px">PIN Saat Ini</div>
                <div style="font-size:24px;font-weight:700;font-family:'JetBrains Mono',monospace;color:#6d28d9;letter-spacing:0.25em">••••••</div>
                @if($pinUpdatedAt)
                <div style="font-size:10px;color:#999;margin-top:4px">Terakhir: {{ \Carbon\Carbon::parse($pinUpdatedAt)->translatedFormat('d M Y H:i') }}</div>
                @endif
            </div>

            <form id="form-pin" method="POST" action="{{ route('admin.pin.update') }}">
                @csrf
                @method('PUT')

                <div style="margin-bottom:14px">
                    <label for="modal-old_pin" style="display:block;font-size:12px;font-weight:500;color:#555;margin-bottom:4px">PIN Lama</label>
                    <input type="password" id="modal-old_pin" name="old_pin" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" placeholder="Masukkan PIN lama" required
                        style="width:100%;border:1.5px solid #c5d9f0;border-radius:10px;padding:11px 14px;font-size:15px;font-weight:500;font-family:'JetBrains Mono',monospace;color:#222;background:#f7faff;outline:none;letter-spacing:0.25em;transition:border 0.15s"
                        onfocus="this.style.borderColor='#6d28d9';this.style.background='#fff'"
                        onblur="this.style.borderColor='#c5d9f0';this.style.background='#f7faff'">
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px">
                    <div>
                        <label for="modal-pin" style="display:block;font-size:12px;font-weight:500;color:#555;margin-bottom:4px">PIN Baru (6 digit)</label>
                        <input type="password" id="modal-pin" name="pin" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" placeholder="PIN baru" required
                            style="width:100%;border:1.5px solid #c5d9f0;border-radius:10px;padding:11px 14px;font-size:15px;font-weight:500;font-family:'JetBrains Mono',monospace;color:#222;background:#f7faff;outline:none;letter-spacing:0.25em;transition:border 0.15s"
                            onfocus="this.style.borderColor='#6d28d9';this.style.background='#fff'"
                            onblur="this.style.borderColor='#c5d9f0';this.style.background='#f7faff'">
                    </div>
                    <div>
                        <label for="modal-pin_confirmation" style="display:block;font-size:12px;font-weight:500;color:#555;margin-bottom:4px">Konfirmasi PIN Baru</label>
                        <input type="password" id="modal-pin_confirmation" name="pin_confirmation" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" placeholder="Ulangi PIN" required
                            style="width:100%;border:1.5px solid #c5d9f0;border-radius:10px;padding:11px 14px;font-size:15px;font-weight:500;font-family:'JetBrains Mono',monospace;color:#222;background:#f7faff;outline:none;letter-spacing:0.25em;transition:border 0.15s"
                            onfocus="this.style.borderColor='#6d28d9';this.style.background='#fff'"
                            onblur="this.style.borderColor='#c5d9f0';this.style.background='#f7faff'">
                    </div>
                </div>

                @if($errors->any())
                <div style="background:#fef2f2;color:#b91c1c;padding:10px 12px;border-radius:8px;font-size:12px;margin-bottom:14px;border:1px solid #fecaca">
                    @foreach($errors->all() as $err)
                    <div>{{ $err }}</div>
                    @endforeach
                </div>
                @endif

                <button type="submit" style="width:100%;background:#6d28d9;color:#fff;border:none;border-radius:10px;padding:12px;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px">
                    <i class="ti ti-device-floppy"></i>
                    Simpan PIN Baru
                </button>
            </form>
        </div>
    </div>
    @endif

    @stack('scripts')

    @php $hasPinError = $errors->hasAny(['old_pin', 'pin', 'pin_confirmation']); @endphp

    <style>
        @keyframes toastIn { from { opacity:0; transform:translateX(30px); } to { opacity:1; transform:translateX(0); } }
    </style>
    <script>
    function showPinModal() {
        document.getElementById('modal-pin').classList.add('show');
    }
    function hidePinModal() {
        document.getElementById('modal-pin').classList.remove('show');
    }
    function showToast(msg, type) {
        var c = document.getElementById('toast-area');
        if (!c) { c = document.createElement('div'); c.id = 'toast-area';
            c.style.cssText = 'position:fixed;top:24px;right:24px;z-index:99999;display:flex;flex-direction:column;gap:12px;max-width:420px';
            document.body.appendChild(c); }
        var t = document.createElement('div');
        var isS = type === 'success';
        var ac = isS ? '#059669' : '#6d28d9';
        var bg = isS ? '#ecfdf5' : '#f5f0ff';
        var ic = isS ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="' + ac + '" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
            : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="' + ac + '" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>';
        t.style.cssText = 'display:flex;align-items:flex-start;gap:12px;background:#fff;border:1px solid #e2e8f0;border-left:4px solid ' + ac + ';border-radius:12px;padding:14px 16px;box-shadow:0 8px 24px rgba(0,0,0,0.08);font-family:\'Inter\',\'Segoe UI\',sans-serif;animation:toastIn 0.35s ease';
        t.innerHTML = '<div style="flex-shrink:0;width:36px;height:36px;border-radius:10px;background:' + bg + ';display:flex;align-items:center;justify-content:center">' + ic + '</div>'
            + '<div style="flex:1;min-width:0"><div style="font-size:14px;font-weight:600;color:#1e293b">' + (isS ? 'Berhasil' : 'Informasi') + '</div>'
            + '<div style="font-size:13px;color:#64748b;margin-top:2px;line-height:1.4">' + msg + '</div></div>'
            + '<button onclick="this.parentElement.remove()" style="flex-shrink:0;width:24px;height:24px;border:none;background:transparent;cursor:pointer;color:#94a3b8;font-size:20px;line-height:1;padding:0;display:flex;align-items:center;justify-content:center">&times;</button>';
        c.appendChild(t);
        setTimeout(function(){ t.style.transition = 'opacity 0.3s,transform 0.3s'; t.style.opacity = '0'; t.style.transform = 'translateX(30px)';
            setTimeout(function(){ if(t.parentNode) t.parentNode.removeChild(t); },300); }, isS ? 3000 : 5000);
    }
    @if($hasPinError)
    showPinModal();
    @endif
    @if(session('success'))
    showToast(@json(session('success')),'success');
    @endif
    </script>
</body>
</html>
