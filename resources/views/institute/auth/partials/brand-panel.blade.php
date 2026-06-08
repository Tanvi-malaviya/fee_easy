{{--
    Left brand panel for institute auth pages.
    Optional variables:
      $brandHeadline  string  – big headline (default tagline)
      $brandSubtext   string  – supporting line(s)
      $brandSteps     array   – when set, renders vertical steps instead of the modules grid.
                                each item: ['title' => ..., 'desc' => ...]
      $activeStep     int     – which step is currently active (1-based). Steps before it
                                render as done, after it as pending. Default 1.
--}}
@php
    $modules = [
        ['fa-user-graduate', 'Student Management'],
        ['fa-indian-rupee-sign', 'Fee Collection'],
        ['fa-receipt', 'Payments & Receipts'],
        ['fa-users', 'Staff & Attendance'],
        ['fa-layer-group', 'Batches & Homework'],
        ['fa-bell', 'Alerts & WhatsApp'],
    ];
    $brandHeadline = $brandHeadline ?? 'A bridge of knowledge for all';
    $brandSubtext = $brandSubtext ?? 'Tuoora brings your entire institute together — students, fees, staff and communication — in one simple, powerful platform.';
@endphp

<aside class="auth-brand">
    <div class="brand-logo">
        <img src="{{ asset('images/2-remove.png') }}" alt="Tuoora">
    </div>

    <div class="brand-headline">
        <h2>{{ $brandHeadline }}</h2>
        <p>{{ $brandSubtext }}</p>
    </div>

    @isset($brandSteps)
        @php $activeStep = $activeStep ?? 1; @endphp
        <div class="vsteps">
            @foreach ($brandSteps as $i => $s)
                @php
                    $num = $i + 1;
                    $state = $num < $activeStep ? 'is-done' : ($num === $activeStep ? 'is-active' : 'is-pending');
                @endphp
                <div class="vstep {{ $state }}" id="vstep{{ $num }}">
                    <div class="vstep-dot">{!! $num < $activeStep ? '<i class="fas fa-check"></i>' : $num !!}</div>
                    <div class="vstep-text">
                        <h4>{{ $s['title'] }}</h4>
                        <p>{{ $s['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="module-grid">
            @foreach ($modules as $m)
                <div class="module-item">
                    <i class="fas {{ $m[0] }}"></i>
                    <span>{{ $m[1] }}</span>
                </div>
            @endforeach
        </div>
    @endisset

    <div class="brand-footer">© {{ date('Y') }} Tuoora · Empowering Institutes</div>
</aside>
