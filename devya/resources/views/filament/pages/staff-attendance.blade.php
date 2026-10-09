<x-filament-panels::page>

    <div
        x-data="staffAttendance()"
        x-init="init()"
        x-cloak
        class="w-full space-y-5"
    >

        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="w-full rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Staff Attendance
                    </h1>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Scan Staff QR Code or use NIC / Passport number
                    </p>
                </div>

                <div
                    class="inline-flex w-fit shrink-0 items-center gap-2 rounded-full px-3 py-1.5 text-sm font-medium"
                    :class="{
                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400':
                            attendanceSuccess,

                        'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400':
                            !attendanceSuccess && statusText === 'Pending Approval',

                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400':
                            !attendanceSuccess && statusText === 'Action Required',

                        'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300':
                            !attendanceSuccess &&
                            statusText !== 'Pending Approval' &&
                            statusText !== 'Action Required'
                    }"
                >
                    <span
                        class="h-2 w-2 shrink-0 rounded-full"
                        :class="{
                            'bg-green-500':
                                attendanceSuccess,

                            'bg-amber-500':
                                !attendanceSuccess &&
                                statusText === 'Pending Approval',

                            'bg-red-500':
                                !attendanceSuccess &&
                                statusText === 'Action Required',

                            'bg-gray-400':
                                !attendanceSuccess &&
                                statusText !== 'Pending Approval' &&
                                statusText !== 'Action Required'
                        }"
                    ></span>

                    <span x-text="statusText"></span>
                </div>

            </div>

        </div>


        {{-- =========================================================
             MAIN CARDS - FULL WIDTH
        ========================================================== --}}
        <div class="grid w-full grid-cols-1 items-start gap-5 lg:grid-cols-2">

            {{-- =====================================================
                 QR SCANNER
            ====================================================== --}}
            <div class="w-full rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">

                <div class="mb-4 flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">

                        <svg
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 7h4V3H3v4zm14-4v4h4V3h-4zM3 21h4v-4H3v4zm10-18h2v2h-2V3zM9 9h6v6H9V9z"
                            />
                        </svg>

                    </div>

                    <div class="min-w-0">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            QR Code Scanner
                        </h2>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Automatic IN / OUT
                        </p>
                    </div>

                </div>


                {{-- QR Scanner --}}
                <div class="flex justify-center">

                    <div
                        id="qr-reader"
                        class="h-[200px] w-[200px] overflow-hidden rounded-xl bg-gray-50 dark:bg-gray-800"
                    ></div>

                </div>


                {{-- Scanner Status --}}
                <div class="mt-3 text-center">

                    <p
                        class="text-xs text-gray-500 dark:text-gray-400"
                        x-text="scannerMessage"
                    ></p>

                </div>


                {{-- Scanner Controls --}}
                <div class="mt-3 flex justify-center gap-2">

                    <button
                        type="button"
                        @click="resetScanner()"
                        class="rounded-lg bg-gray-100 px-3 py-2 text-xs font-medium text-gray-700 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                        Restart
                    </button>

                    <button
                        type="button"
                        @click="stopScanner()"
                        class="rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800"
                    >
                        Stop
                    </button>

                </div>

            </div>


            {{-- =====================================================
                 MANUAL ENTRY
            ====================================================== --}}
            <div class="w-full rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">

                <div class="mb-4 flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">

                        <svg
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15A2.25 2.25 0 0021.75 17V7A2.25 2.25 0 0019.5 4.75h-15A2.25 2.25 0 002.25 7v10a2.25 2.25 0 002.25 2.25z"
                            />
                        </svg>

                    </div>

                    <div class="min-w-0">

                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Manual Entry
                        </h2>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            NIC / Passport if QR is unavailable
                        </p>

                    </div>

                </div>


                {{-- Manual Form --}}
                <form
                    @submit.prevent="checkManualAttendance()"
                    class="space-y-3"
                >

                    <div>

                        <label
                            for="manual_identifier"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            NIC / Passport Number
                        </label>

                        <input
                            id="manual_identifier"
                            type="text"
                            x-model="manualIdentifier"
                            autocomplete="off"
                            placeholder="Enter NIC or Passport number"
                            @keydown.enter.prevent="checkManualAttendance()"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500"
                        >

                    </div>


                    <button
                        type="submit"
                        :disabled="manualProcessing || !manualIdentifier.trim()"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-amber-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-amber-600 disabled:cursor-not-allowed disabled:opacity-60"
                    >

                        <svg
                            x-show="manualProcessing"
                            x-cloak
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            class="animate-spin"
                        >
                            <circle
                                opacity="0.25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>

                            <path
                                opacity="0.75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                            ></path>
                        </svg>

                        <span
                            x-text="
                                manualProcessing
                                    ? 'Submitting...'
                                    : 'Submit Attendance Request'
                            "
                        ></span>

                    </button>

                </form>


                {{-- Information --}}
                <div class="mt-3 flex items-start gap-2 rounded-lg border border-blue-100 bg-blue-50 px-3 py-2 dark:border-blue-900/40 dark:bg-blue-900/20">

                    <svg
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="#3b82f6"
                        stroke-width="2"
                        class="mt-0.5 shrink-0"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v2m0 4h.01M12 3.75a8.25 8.25 0 100 16.5 8.25 8.25 0 000-16.5z"
                        />
                    </svg>

                    <p class="min-w-0 flex-1 text-[11px] leading-4 text-blue-700 dark:text-blue-300">
                        Manual attendance requires Admin approval.
                    </p>

                </div>

            </div>

        </div>


        {{-- =========================================================
             RESULT
        ========================================================== --}}
        <div
            x-show="showResult"
            x-cloak
            x-transition
            class="w-full overflow-hidden rounded-2xl border shadow-sm"
            :class="{
                'border-green-200 bg-green-50 dark:border-green-900/50 dark:bg-green-900/20':
                    resultType === 'success',

                'border-amber-200 bg-amber-50 dark:border-amber-900/50 dark:bg-amber-900/20':
                    resultType === 'pending',

                'border-red-200 bg-red-50 dark:border-red-900/50 dark:bg-red-900/20':
                    resultType === 'error'
            }"
        >

            <div class="p-4">

                <div class="flex items-start gap-3">

                    {{-- Success --}}
                    <div
                        x-show="resultType === 'success'"
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900/40 dark:text-green-400"
                    >
                        <svg
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>
                    </div>


                    {{-- Pending --}}
                    <div
                        x-show="resultType === 'pending'"
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400"
                    >
                        <svg
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                                fill="none"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 7v5l3 2"
                            />
                        </svg>
                    </div>


                    {{-- Error --}}
                    <div
                        x-show="resultType === 'error'"
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400"
                    >
                        <svg
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </div>


                    <div class="min-w-0 flex-1">

                        <h3
                            class="text-base font-semibold"
                            :class="{
                                'text-green-800 dark:text-green-300':
                                    resultType === 'success',

                                'text-amber-800 dark:text-amber-300':
                                    resultType === 'pending',

                                'text-red-800 dark:text-red-300':
                                    resultType === 'error'
                            }"
                            x-text="resultTitle"
                        ></h3>

                        <p
                            class="mt-0.5 text-sm"
                            :class="{
                                'text-green-700 dark:text-green-400':
                                    resultType === 'success',

                                'text-amber-700 dark:text-amber-400':
                                    resultType === 'pending',

                                'text-red-700 dark:text-red-400':
                                    resultType === 'error'
                            }"
                            x-text="resultMessage"
                        ></p>


                        {{-- Staff Details --}}
                        <div
                            x-show="staffName"
                            class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-3"
                        >

                            <div class="min-w-0">

                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    Staff
                                </span>

                                <p
                                    class="truncate text-sm font-semibold text-gray-900 dark:text-white"
                                    x-text="staffName"
                                ></p>

                            </div>


                            <div class="min-w-0">

                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    Staff Code
                                </span>

                                <p
                                    class="truncate text-sm font-semibold text-gray-900 dark:text-white"
                                    x-text="staffCode"
                                ></p>

                            </div>


                            <div class="min-w-0">

                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    Type / Time
                                </span>

                                <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">

                                    <span x-text="attendanceType"></span>

                                    <span
                                        x-show="attendanceTime"
                                        x-text="' • ' + attendanceTime"
                                    ></span>

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             HOW IT WORKS
        ========================================================== --}}
        <div class="w-full rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">

            <div class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-3">

                <div class="flex items-center gap-3">

                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-100 text-sm font-semibold text-green-600 dark:bg-green-900/30 dark:text-green-400">
                        1
                    </span>

                    <div class="min-w-0">

                        <p class="font-medium text-gray-900 dark:text-white">
                            First QR Scan
                        </p>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Automatic IN
                        </p>

                    </div>

                </div>


                <div class="flex items-center gap-3">

                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                        2
                    </span>

                    <div class="min-w-0">

                        <p class="font-medium text-gray-900 dark:text-white">
                            Next QR Scan
                        </p>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Automatic OUT
                        </p>

                    </div>

                </div>


                <div class="flex items-center gap-3">

                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-100 text-sm font-semibold text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
                        3
                    </span>

                    <div class="min-w-0">

                        <p class="font-medium text-gray-900 dark:text-white">
                            Manual Entry
                        </p>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Admin approval
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-filament-panels::page>


{{-- ===============================================================
     QR SCANNER LIBRARY
================================================================ --}}
<script src="{{ asset('js/html5-qrcode.min.js') }}"></script>


<script>
    function staffAttendance() {
        return {

            manualIdentifier: '',
            manualProcessing: false,

            showResult: false,
            resultType: '',
            resultTitle: '',
            resultMessage: '',

            attendanceType: '',
            staffName: '',
            staffCode: '',
            attendanceTime: '',

            attendanceSuccess: false,
            statusText: 'Scanner Ready',

            scannerMessage: 'Initializing camera...',

            qrScanner: null,
            scannerRunning: false,
            processingScan: false,

            manualUrl: @js(route('staff-attendance.manual')),
            scanUrl: @js(route('staff-attendance.scan')),
            csrfToken: @js(csrf_token()),


            /* =====================================================
               INIT
            ====================================================== */

            init() {
                this.$nextTick(() => {
                    setTimeout(() => {
                        this.startScanner();
                    }, 300);
                });
            },


            /* =====================================================
               START QR SCANNER
            ====================================================== */

            startScanner() {

                if (typeof Html5Qrcode === 'undefined') {

                    this.scannerMessage =
                        'QR scanner library could not be loaded.';

                    this.statusText =
                        'Scanner Error';

                    return;
                }


                if (this.scannerRunning) {
                    return;
                }


                try {

                    const readerElement =
                        document.getElementById('qr-reader');


                    if (!readerElement) {

                        this.scannerMessage =
                            'Scanner element not found.';

                        this.statusText =
                            'Scanner Error';

                        return;
                    }


                    readerElement.innerHTML = '';


                    this.qrScanner =
                        new Html5Qrcode('qr-reader');


                    const config = {
                        fps: 10,

                        qrbox: {
                            width: 170,
                            height: 170
                        },

                        aspectRatio: 1.0
                    };


                    this.qrScanner.start(

                        {
                            facingMode: 'environment'
                        },

                        config,

                        (decodedText) => {

                            if (this.processingScan) {
                                return;
                            }

                            this.handleQrScan(decodedText);

                        },

                        () => {}

                    )

                    .then(() => {

                        this.scannerRunning = true;

                        this.scannerMessage =
                            'Camera ready — scan Staff QR Code';

                        this.statusText =
                            'Scanner Ready';

                    })

                    .catch((error) => {

                        console.error(
                            'QR Scanner Start Error:',
                            error
                        );

                        this.scannerMessage =
                            'Camera could not be started. Please allow camera access.';

                        this.statusText =
                            'Camera Error';

                    });

                } catch (error) {

                    console.error(
                        'QR Scanner Error:',
                        error
                    );

                    this.scannerMessage =
                        'Unable to initialize QR scanner.';

                    this.statusText =
                        'Scanner Error';

                }

            },


            /* =====================================================
               QR SCAN REQUEST
            ====================================================== */

            async handleQrScan(qrToken) {

                if (this.processingScan) {
                    return;
                }


                this.processingScan = true;


                try {

                    const response = await fetch(
                        this.scanUrl,
                        {
                            method: 'POST',

                            headers: {
                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    this.csrfToken,

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            },

                            body: JSON.stringify({
                                token: qrToken
                            })
                        }
                    );


                    const data =
                        await response.json();


                    if (
                        !response.ok ||
                        !data.success
                    ) {

                        this.showError(
                            data.message ||
                            'Attendance scan failed.'
                        );

                        return;
                    }


                    this.showSuccess(data);


                } catch (error) {

                    console.error(
                        'QR Attendance Error:',
                        error
                    );

                    this.showError(
                        'Unable to connect to the attendance server.'
                    );

                } finally {

                    setTimeout(() => {

                        this.processingScan =
                            false;

                    }, 1500);

                }

            },


            /* =====================================================
               MANUAL ENTRY
            ====================================================== */

            async checkManualAttendance() {

                if (this.manualProcessing) {
                    return;
                }


                const identifier =
                    this.manualIdentifier.trim();


                if (!identifier) {

                    this.showError(
                        'Please enter NIC or Passport number.'
                    );

                    return;
                }


                this.manualProcessing = true;

                this.showResult = false;


                try {

                    const response = await fetch(
                        this.manualUrl,
                        {
                            method: 'POST',

                            headers: {
                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    this.csrfToken,

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            },

                            body: JSON.stringify({
                                identifier: identifier
                            })
                        }
                    );


                    let data;


                    try {

                        data =
                            await response.json();

                    } catch (jsonError) {

                        console.error(
                            'Invalid JSON response:',
                            jsonError
                        );

                        this.showError(
                            'Server returned an invalid response.'
                        );

                        return;
                    }


                    console.log(
                        'Manual Attendance Response:',
                        data
                    );


                    if (
                        !response.ok ||
                        !data.success
                    ) {

                        this.showError(
                            data.message ||
                            'Manual attendance request failed.'
                        );

                        return;
                    }


                    this.showManualSuccess(data);


                } catch (error) {

                    console.error(
                        'Manual Attendance Error:',
                        error
                    );

                    this.showError(
                        'Unable to connect to the attendance server.'
                    );

                } finally {

                    this.manualProcessing =
                        false;
                }

            },


            /* =====================================================
               QR SUCCESS
            ====================================================== */

            showSuccess(data) {

                this.showResult = true;

                this.resultType =
                    'success';


                this.resultTitle =
                    data.action === 'IN'
                        ? 'Attendance IN Recorded'
                        : 'Attendance OUT Recorded';


                this.resultMessage =
                    data.message ||
                    'Attendance recorded successfully.';


                this.attendanceType =
                    data.type ||
                    data.action ||
                    '';


                this.staffName =
                    data.staff_name ||
                    '';


                this.staffCode =
                    data.staff_code ||
                    '';


                this.attendanceTime =
                    data.time ||
                    '';


                this.attendanceSuccess =
                    true;


                this.statusText =
                    data.action === 'IN'
                        ? 'IN Recorded'
                        : 'OUT Recorded';


                this.scannerMessage =
                    'Attendance recorded successfully.';


                this.manualIdentifier =
                    '';

            },


            /* =====================================================
               MANUAL SUCCESS / PENDING
            ====================================================== */

            showManualSuccess(data) {

                this.showResult = true;

                this.resultType =
                    'pending';


                this.resultTitle =
                    'Attendance Request Submitted';


                this.resultMessage =
                    data.message ||
                    'Request submitted for Admin approval.';


                this.attendanceType =
                    'Pending';


                this.staffName =
                    data.staff_name ||
                    '';


                this.staffCode =
                    data.staff_code ||
                    '';


                this.attendanceTime =
                    data.requested_time ||
                    '';


                this.attendanceSuccess =
                    false;


                this.statusText =
                    'Pending Approval';


                this.scannerMessage =
                    'Manual request submitted successfully.';


                this.manualIdentifier =
                    '';

            },


            /* =====================================================
               ERROR
            ====================================================== */

            showError(message) {

                this.showResult = true;

                this.resultType =
                    'error';


                this.resultTitle =
                    'Attendance Error';


                this.resultMessage =
                    message;


                this.attendanceType =
                    '';


                this.staffName =
                    '';


                this.staffCode =
                    '';


                this.attendanceTime =
                    '';


                this.attendanceSuccess =
                    false;


                this.statusText =
                    'Action Required';

            },


            /* =====================================================
               STOP SCANNER
            ====================================================== */

            async stopScanner() {

                if (
                    !this.qrScanner ||
                    !this.scannerRunning
                ) {
                    return;
                }


                try {

                    await this.qrScanner.stop();

                    this.qrScanner.clear();


                    this.scannerRunning =
                        false;


                    this.scannerMessage =
                        'Scanner stopped.';


                    this.statusText =
                        'Scanner Stopped';

                } catch (error) {

                    console.error(
                        'Stop Scanner Error:',
                        error
                    );

                }

            },


            /* =====================================================
               RESET SCANNER
            ====================================================== */

            async resetScanner() {

                try {

                    if (
                        this.qrScanner &&
                        this.scannerRunning
                    ) {

                        await this.qrScanner.stop();

                        this.qrScanner.clear();
                    }

                } catch (error) {

                    console.error(
                        'Reset Scanner Error:',
                        error
                    );

                }


                this.qrScanner =
                    null;

                this.scannerRunning =
                    false;

                this.processingScan =
                    false;

                this.scannerMessage =
                    'Restarting camera...';

                this.statusText =
                    'Scanner Starting';


                setTimeout(() => {

                    this.startScanner();

                }, 300);

            }

        };
    }
</script>
