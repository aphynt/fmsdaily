@foreach($data as $loaderId => $assignments)
    <div class="col">
        <div class="card border-0">
            @if ($assignments->first()->IS_SETTING_LOADR == 0)
                <img src="{{ asset('oprAssignment/icon/belum-disetting.png') }}" width="15px">
            @endif
            @if ($assignments->first()->IS_LOGIN_LOADER == 0)
                <img src="{{ asset('oprAssignment/icon/belum-login.png') }}" width="15px">
            @endif
            @if ($assignments->first()->IS_LOGIN_LOADER > 0 && $assignments->first()->IS_SETTING_LOADR > 0)
                <img src="{{ asset('oprAssignment/icon/setting-sesuai.png') }}" width="15px">
            @endif
            @if ($assignments->first()->IS_LOGIN_LOADER > 0 && $assignments->first()->IS_SETTING_LOADR == 0)
                <img src="{{ asset('oprAssignment/icon/setting-berbeda.png') }}" width="15px">
            @endif
            <div class="text-center text-white"
                @if ($assignments->first()->NIK_FINGER_LOADER_ORI == null)
                    style="background-color:#6495ed;"
                @elseif ($assignments->first()->NAMA_FGR_LOADER != null)
                    style="background-color:#0000ff;"
                @endif
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                data-bs-html="true"
                data-bs-custom-class="custom-tooltip"
                data-bs-title="Status: {{ $assignments->first()->STATUSDESCLOADER }}">
                <h5 class="mb-0 text-white">{{ $loaderId }}</h5>
                <p class="mb-0">{{ Str::limit($assignments->first()->NAMA_FGR_LOADER, 13) ?: '______' }}</p>
                <p class="mb-0 anymore">{{ Str::limit($assignments->first()->NIK_FGR_LOADER, 13) ?: '______' }}</p>
            </div>
        </div>
        <div class="mt-2">
            @foreach($assignments as $assignment)
                <div class="card mb-3 border-0 shadow-sm text-white"
                    @if ($assignment->NIK_FGR_ORI == null)
                        style="background-color:#afeeee;"
                    @elseif ($assignment->NAMA_FGR != null)
                        style="background-color:#00ff00;"
                    @endif
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    data-bs-html="true"
                    data-bs-custom-class="custom-tooltip"
                    data-bs-title="Assignment: {{ date('d-m-Y H:i', strtotime($assignment->ASG_TIMESTAMP)) }}
                    <br>Material: {{ $assignment->ASG_MAT_ID }}
                    <br>Status: {{ $assignment->STATUSDESCTRUCK }}">
                    <div class="text-center">
                        @if ($assignment->IS_SETTING == 0)
                            <img src="{{ asset('oprAssignment/icon/belum-disetting.png') }}" width="15px">
                        @endif
                        @if ($assignment->IS_LOGIN == 0)
                            <img src="{{ asset('oprAssignment/icon/belum-login.png') }}" width="15px">
                        @endif
                        @if ($assignment->IS_LOGIN > 0 && $assignment->IS_SETTING > 0)
                            <img src="{{ asset('oprAssignment/icon/setting-sesuai.png') }}" width="15px">
                        @endif
                        @if ($assignment->IS_LOGIN > 0 && $assignment->IS_SETTING == 0)
                            <img src="{{ asset('oprAssignment/icon/setting-berbeda.png') }}" width="15px">
                        @endif
                        <p class="fw-bold text-black mb-1">{{ $assignment->VHC_ID }}</p>
                        <p class="mb-0 text-black">{{ Str::limit($assignment->NAMA_FGR, 13) ?: '______' }}</p>
                        <p class="mb-0 anymore text-black">{{ $assignment->NIK_FGR ?: '_____' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach
