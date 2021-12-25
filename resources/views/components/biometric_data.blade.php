@if (count($biometrics) > 0)
        @foreach ($biometrics as $biometric)
                <button class="btn biometric-data">
                        <i class="fas fa-fingerprint"></i>
                        <p>Biometric {{ $loop->iteration }}</p>
                        <i class="fas fa-trash-alt biometic-delete-btn" data-id="{{ $biometric->id }}"></i>
                </button>
        @endforeach
@endif