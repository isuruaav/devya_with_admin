<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>Ayu Queue Display</title>

    @vite([
        'resources/css/filament/admin/theme.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-slate-950 font-sans text-slate-900">

    <div
        id="queue-app"
        data-room="{{ $room ?? '' }}"
        class="min-h-screen bg-slate-950"
    >

        {{-- =====================================================
             HEADER
        ====================================================== --}}
        <header class="border-b border-white/10 bg-slate-950 px-4 py-4 shadow-lg sm:px-6 lg:px-8">

            <div class="mx-auto flex max-w-[1800px] items-center justify-between gap-4">

                <div class="min-w-0">
                    <div class="flex items-center gap-3">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-600 text-xl font-black text-white shadow-lg">
                            A
                        </div>

                        <div class="min-w-0">

                            <h1 class="truncate text-xl font-black tracking-tight text-white sm:text-2xl">
                                AYU
                            </h1>

                            <p class="truncate text-[10px] font-bold uppercase tracking-[0.25em] text-emerald-400 sm:text-xs">
                                Ayurvedic Health & Wellness
                            </p>

                        </div>

                    </div>
                </div>


                <div class="shrink-0 text-right">

                    <div
                        id="live-clock"
                        class="text-lg font-black tabular-nums text-white sm:text-2xl"
                    >
                        --:--:--
                    </div>

                    <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        Queue Display
                    </div>

                </div>

            </div>

        </header>


        {{-- =====================================================
             MAIN
        ====================================================== --}}
        <main class="mx-auto max-w-[1800px] p-3 sm:p-5 lg:p-6">

            <div class="grid min-h-[calc(100vh-110px)] grid-cols-1 gap-5 lg:grid-cols-2">


                {{-- =================================================
                     LEFT SIDE - QUEUE
                ================================================== --}}
                <section class="flex min-h-[600px] flex-col gap-5">


                    {{-- DOCTOR CARD --}}
                    <div class="rounded-3xl border border-white/10 bg-white p-5 shadow-2xl sm:p-6">

                        <div class="flex items-center gap-4">

                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-2xl">
                                👨‍⚕️
                            </div>

                            <div class="min-w-0 flex-1">

                                <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                    Consulting Doctor
                                </p>

                                <h2
                                    id="doctor-name"
                                    class="mt-1 truncate text-2xl font-black text-slate-900 sm:text-3xl"
                                >
                                    Doctor
                                </h2>

                                <p class="mt-2 text-xs font-black uppercase tracking-[0.16em] text-emerald-700">
                                    Specialization
                                </p>

                                <p
                                    id="doctor-specialization"
                                    class="mt-0.5 text-sm font-semibold text-slate-600 sm:text-base"
                                >
                                    Loading...
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- NOT STARTED --}}
                    <div
                        id="not-started"
                        class="flex flex-1 items-center justify-center rounded-3xl border border-white/10 bg-white p-8 shadow-2xl"
                    >

                        <div class="max-w-md text-center">

                            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-emerald-100 text-5xl">
                                🕐
                            </div>

                            <h2 class="mt-6 text-3xl font-black text-slate-900 sm:text-4xl">
                                Queue Not Started
                            </h2>

                            <p class="mt-3 text-sm font-semibold leading-6 text-slate-500 sm:text-base">
                                Please wait. The consultation queue will appear here when service starts.
                            </p>

                        </div>

                    </div>


                    {{-- STARTED QUEUE --}}
                    <div
                        id="started-queue"
                        class="hidden flex-1 flex-col gap-5"
                    >

                        {{-- NOW + NEXT --}}
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">


                            {{-- NOW SERVING --}}
                            <div class="overflow-hidden rounded-3xl bg-emerald-700 shadow-2xl">

                                <div class="border-b border-white/20 bg-emerald-800 px-5 py-4">

                                    <div class="flex items-center justify-between gap-3">

                                        <span class="text-xs font-black uppercase tracking-[0.2em] text-emerald-100 sm:text-sm">
                                            Now Serving
                                        </span>

                                        <span class="rounded-full bg-white/20 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-white">
                                            Current
                                        </span>

                                    </div>

                                </div>


                                <div class="p-5 sm:p-6">

                                    <div class="rounded-3xl bg-white p-5 text-center shadow-xl sm:p-6">

                                        <div
                                            id="current-number"
                                            class="text-6xl font-black leading-none tracking-tight text-emerald-700 sm:text-7xl lg:text-8xl"
                                        >
                                            —
                                        </div>

                                    </div>


                                    <div
                                        id="current-name"
                                        class="mt-4 truncate rounded-2xl bg-emerald-800 px-4 py-4 text-center text-lg font-black text-white sm:text-xl"
                                    >
                                        Patient
                                    </div>

                                </div>

                            </div>


                            {{-- NEXT QUEUE --}}
                            <div class="overflow-hidden rounded-3xl bg-blue-700 shadow-2xl">

                                <div class="border-b border-white/20 bg-blue-800 px-5 py-4">

                                    <div class="flex items-center justify-between gap-3">

                                        <span class="text-xs font-black uppercase tracking-[0.2em] text-blue-100 sm:text-sm">
                                            Next Queue
                                        </span>

                                        <span class="rounded-full bg-white/20 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-white">
                                            Next
                                        </span>

                                    </div>

                                </div>


                                <div class="p-5 sm:p-6">

                                    <div class="rounded-3xl bg-white p-5 text-center shadow-xl sm:p-6">

                                        <div
                                            id="next-number"
                                            class="text-6xl font-black leading-none tracking-tight text-blue-700 sm:text-7xl lg:text-8xl"
                                        >
                                            —
                                        </div>

                                    </div>


                                    <div
                                        id="next-name"
                                        class="mt-4 truncate rounded-2xl bg-blue-800 px-4 py-4 text-center text-lg font-black text-white sm:text-xl"
                                    >
                                        Patient
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- WAITING QUEUE --}}
                        <div class="flex min-h-0 flex-1 flex-col overflow-hidden rounded-3xl bg-white shadow-2xl">

                            <div class="flex items-center justify-between gap-4 bg-amber-500 px-5 py-4 sm:px-6">

                                <div>

                                    <p class="text-xs font-black uppercase tracking-[0.2em] text-amber-50">
                                        Waiting Queue
                                    </p>

                                    <h3 class="mt-1 text-lg font-black text-white sm:text-xl">
                                        Patients Waiting
                                    </h3>

                                </div>


                                <div class="flex h-12 min-w-12 items-center justify-center rounded-2xl bg-white px-4 text-2xl font-black text-amber-600 shadow-lg">

                                    <span id="waiting-count">
                                        0
                                    </span>

                                </div>

                            </div>


                            <div
                                id="waiting-list"
                                class="min-h-0 flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-5"
                            >

                                <div class="flex min-h-[120px] items-center justify-center rounded-2xl border border-dashed border-amber-200 bg-white text-sm font-bold text-slate-500">
                                    No waiting patients
                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                {{-- =================================================
                     RIGHT SIDE - ADVERTISEMENT
                ================================================== --}}
                <section class="flex min-h-[600px] flex-col overflow-hidden rounded-3xl border border-white/10 bg-slate-900 shadow-2xl">

                    {{-- AD HEADER --}}
                    <div class="flex shrink-0 items-center justify-between gap-4 border-b border-white/10 bg-slate-900 px-5 py-4 sm:px-6">

                        <div>

                            <p class="text-xs font-black uppercase tracking-[0.2em] text-emerald-400">
                                AYU
                            </p>

                            <h2 class="mt-1 text-lg font-black text-white sm:text-xl">
                                Health & Wellness
                            </h2>

                        </div>


                        <div class="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1.5">

                            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400">
                                Advertisement
                            </span>

                        </div>

                    </div>


                    {{-- AD AREA --}}
                    <div
                        id="advertisement-slider"
                        class="min-h-[500px] flex-1 overflow-hidden bg-slate-950"
                    >

                        <div class="flex h-full min-h-[500px] items-center justify-center bg-slate-950">

                            <div class="text-center">

                                <div class="text-7xl font-black tracking-tight text-slate-700 sm:text-8xl">
                                    AYU
                                </div>

                                <div class="mt-3 text-xs font-black uppercase tracking-[0.3em] text-slate-600">
                                    Health & Wellness
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- AD FOOTER --}}
                    <div class="shrink-0 border-t border-white/10 bg-slate-900 px-5 py-3 text-center">

                        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-slate-500">
                            Your Health • Our Care
                        </p>

                    </div>

                </section>

            </div>

        </main>

    </div>


    {{-- =========================================================
         JAVASCRIPT
    ========================================================== --}}
    <script>

        const queueAppElement =
            document.getElementById('queue-app');

        const room =
            queueAppElement?.dataset.room || '';


        const liveClockElement =
            document.getElementById('live-clock');

        const doctorNameElement =
            document.getElementById('doctor-name');

        const doctorSpecializationElement =
            document.getElementById('doctor-specialization');

        const notStartedElement =
            document.getElementById('not-started');

        const startedQueueElement =
            document.getElementById('started-queue');

        const currentNumberElement =
            document.getElementById('current-number');

        const currentNameElement =
            document.getElementById('current-name');

        const nextNumberElement =
            document.getElementById('next-number');

        const nextNameElement =
            document.getElementById('next-name');

        const waitingCountElement =
            document.getElementById('waiting-count');

        const waitingListElement =
            document.getElementById('waiting-list');

        const advertisementSliderElement =
            document.getElementById('advertisement-slider');


        /* =====================================================
           CLOCK
        ====================================================== */

        function updateClock() {

            if (!liveClockElement) {
                return;
            }

            const now = new Date();

            liveClockElement.textContent =
                now.toLocaleTimeString(
                    'en-GB',
                    {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    }
                );
        }

        updateClock();

        setInterval(
            updateClock,
            1000
        );


        /* =====================================================
           HELPERS
        ====================================================== */

        function escapeHtml(value) {

            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');

        }


        function firstValue(...values) {

            for (const value of values) {

                if (
                    value !== undefined &&
                    value !== null &&
                    String(value).trim() !== ''
                ) {
                    return value;
                }

            }

            return '';

        }


        function getPatientNumber(item) {

            if (!item) {
                return '';
            }

            return firstValue(
                item.queue_number,
                item.queueNumber,
                item.token_number,
                item.tokenNumber,
                item.number,
                item.appointment_number,
                item.appointmentNumber,
                item.patient_number,
                item.patientNumber,
                item.token,
                item.queue
            );

        }


        function getPatientName(item) {

            if (!item) {
                return '';
            }

            return firstValue(
                item.patient_name,
                item.patientName,
                item.name,
                item.patient?.name,
                item.patient?.full_name,
                item.patient?.fullName,
                item.patient?.patient_name
            );

        }


        function getDoctorName(data) {

            if (!data) {
                return '';
            }

            return firstValue(
                data.doctor_name,
                data.doctorName,
                data.doctor,
                data.doctor?.name,
                data.doctor?.full_name,
                data.doctor?.fullName,
                data.doctor?.doctor_name,

                data.appointment?.doctor_name,
                data.appointment?.doctorName,
                data.appointment?.doctor?.name,

                data.current?.doctor_name,
                data.current?.doctorName,
                data.current?.doctor?.name,

                data.queue?.doctor_name,
                data.queue?.doctorName,
                data.queue?.doctor?.name,

                data.data?.doctor_name,
                data.data?.doctorName,
                data.data?.doctor
            );

        }


        function getDoctorSpecialization(data) {

            if (!data) {
                return '';
            }

            return firstValue(
                data.doctor_specialization,
                data.doctorSpecialization,
                data.doctor?.specialization,
                data.appointment?.doctor?.specialization,
                data.current?.doctor_specialization,
                data.current?.doctor?.specialization,
                data.next?.doctor_specialization,
                data.next?.doctor?.specialization
            );

        }


        function getQueueArray(data) {

            if (!data) {
                return [];
            }

            const candidates = [

                data.queue,
                data.queues,
                data.waiting,
                data.waiting_queue,
                data.waitingQueue,
                data.appointments,

                data.data?.queue,
                data.data?.queues,
                data.data?.waiting,
                data.data?.appointments

            ];


            for (const candidate of candidates) {

                if (Array.isArray(candidate)) {
                    return candidate;
                }

                if (
                    candidate &&
                    Array.isArray(candidate.data)
                ) {
                    return candidate.data;
                }

            }

            return [];

        }


        function getCurrentPatient(data, queue) {

            if (!data) {
                return null;
            }

            const directCurrent =
                data.current ??
                data.current_patient ??
                data.currentPatient ??
                data.now_serving ??
                data.nowServing ??
                data.serving ??
                data.current_appointment ??
                data.currentAppointment;


            if (
                directCurrent &&
                typeof directCurrent === 'object'
            ) {
                return directCurrent;
            }


            if (
                Array.isArray(queue) &&
                queue.length > 0
            ) {

                const current =
                    queue.find(function(item) {

                        const status =
                            String(
                                item.status ??
                                item.queue_status ??
                                item.queueStatus ??
                                ''
                            ).toLowerCase();

                        return [

                            'serving',
                            'called',
                            'calling',
                            'current',
                            'now_serving',
                            'now serving'

                        ].includes(status);

                    });


                if (current) {
                    return current;
                }

            }


            return null;

        }


        function getNextPatient(data, queue) {

            if (!data) {
                return null;
            }

            const directNext =
                data.next ??
                data.next_patient ??
                data.nextPatient ??
                data.next_queue ??
                data.nextQueue ??
                data.next_appointment ??
                data.nextAppointment;


            if (
                directNext &&
                typeof directNext === 'object'
            ) {
                return directNext;
            }


            if (
                Array.isArray(queue) &&
                queue.length > 0
            ) {

                const waiting =
                    queue.filter(function(item) {

                        const status =
                            String(
                                item.status ??
                                item.queue_status ??
                                item.queueStatus ??
                                ''
                            ).toLowerCase();

                        return ![

                            'completed',
                            'complete',
                            'cancelled',
                            'cancel',
                            'serving',
                            'called',
                            'calling',
                            'current',
                            'done'

                        ].includes(status);

                    });


                return waiting[0] ?? null;

            }


            return null;

        }


        function getWaitingPatients(
            data,
            queue,
            current,
            next
        ) {

            if (!Array.isArray(queue)) {
                return [];
            }


            const currentId =
                current?.id ?? null;

            const nextId =
                next?.id ?? null;


            return queue.filter(
                function(item) {

                    if (!item) {
                        return false;
                    }


                    if (
                        currentId !== null &&
                        item.id === currentId
                    ) {
                        return false;
                    }


                    if (
                        nextId !== null &&
                        item.id === nextId
                    ) {
                        return false;
                    }


                    const status =
                        String(
                            item.status ??
                            item.queue_status ??
                            item.queueStatus ??
                            ''
                        ).toLowerCase();


                    if ([

                        'completed',
                        'complete',
                        'cancelled',
                        'cancel',
                        'serving',
                        'called',
                        'calling',
                        'current',
                        'done'

                    ].includes(status)) {

                        return false;

                    }


                    return true;

                }
            );

        }


        /* =====================================================
           QUEUE RENDER
        ====================================================== */

        function renderQueue(data) {

            console.log(
                'QUEUE DATA:',
                data
            );


            const doctorName =
                getDoctorName(data);


            if (doctorNameElement) {

                doctorNameElement.textContent =
                    doctorName || 'Doctor';

            }

            if (doctorSpecializationElement) {

                doctorSpecializationElement.textContent =
                    getDoctorSpecialization(data) || 'Specialization not specified';

            }


            const queue =
                getQueueArray(data);


            const current =
                getCurrentPatient(
                    data,
                    queue
                );


            const next =
                getNextPatient(
                    data,
                    queue
                );


            const waiting =
                getWaitingPatients(
                    data,
                    queue,
                    current,
                    next
                );


            const hasQueueData =
                Boolean(
                    current ||
                    next ||
                    waiting.length > 0 ||
                    queue.length > 0
                );


            if (!hasQueueData) {

                if (notStartedElement) {

                    notStartedElement.classList.remove(
                        'hidden'
                    );

                }


                if (startedQueueElement) {

                    startedQueueElement.classList.add(
                        'hidden'
                    );

                    startedQueueElement.classList.remove(
                        'flex'
                    );

                }

                return;

            }


            if (notStartedElement) {

                notStartedElement.classList.add(
                    'hidden'
                );

            }


            if (startedQueueElement) {

                startedQueueElement.classList.remove(
                    'hidden'
                );

                startedQueueElement.classList.add(
                    'flex'
                );

            }


            if (currentNumberElement) {

                currentNumberElement.textContent =
                    getPatientNumber(current) || '—';

            }


            if (currentNameElement) {

                currentNameElement.textContent =
                    getPatientName(current) || 'Patient';

            }


            if (nextNumberElement) {

                nextNumberElement.textContent =
                    getPatientNumber(next) || '—';

            }


            if (nextNameElement) {

                nextNameElement.textContent =
                    getPatientName(next) || 'Patient';

            }


            if (waitingCountElement) {

                waitingCountElement.textContent =
                    String(waiting.length);

            }


            if (waitingListElement) {

                if (waiting.length === 0) {

                    waitingListElement.innerHTML = `

                        <div class="flex min-h-[120px] items-center justify-center rounded-2xl border border-dashed border-amber-200 bg-white text-sm font-bold text-slate-500">

                            No waiting patients

                        </div>

                    `;

                } else {

                    waitingListElement.innerHTML =

                        waiting.map(
                            function(item, index) {

                                const number =
                                    getPatientNumber(item) || '—';

                                const name =
                                    getPatientName(item) || 'Patient';


                                return `

                                    <div class="mb-3 flex items-center justify-between gap-3 rounded-2xl border border-amber-100 bg-white px-4 py-3 shadow-sm">

                                        <div class="flex min-w-0 items-center gap-3">

                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-sm font-black text-amber-800">

                                                ${index + 1}

                                            </div>


                                            <div class="min-w-0">

                                                <div class="truncate text-sm font-black text-slate-900">

                                                    ${escapeHtml(name)}

                                                </div>


                                                <div class="mt-0.5 text-[10px] font-bold text-slate-500">

                                                    Waiting for turn

                                                </div>

                                            </div>

                                        </div>


                                        <div class="shrink-0 rounded-xl bg-amber-500 px-4 py-2.5 text-base font-black text-white shadow-sm">

                                            ${escapeHtml(number)}

                                        </div>

                                    </div>

                                `;

                            }
                        ).join('');

                }

            }

        }


        /* =====================================================
           QUEUE REFRESH
        ====================================================== */

        let queueRequestInProgress =
            false;


        async function refreshQueue() {

            if (queueRequestInProgress) {
                return;
            }


            queueRequestInProgress =
                true;


            try {

                const url =
                    "{{ route('queue.public.data') }}";


                const separator =
                    url.includes('?')
                        ? '&'
                        : '?';


                const requestUrl =
                    url +
                    separator +
                    'room=' +
                    encodeURIComponent(room) +
                    '&_queue_refresh=' +
                    Date.now();


                const response =
                    await fetch(
                        requestUrl,
                        {
                            method: 'GET',

                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Cache-Control': 'no-cache'
                            },

                            cache: 'no-store'
                        }
                    );


                if (!response.ok) {

                    throw new Error(
                        'HTTP ' +
                        response.status
                    );

                }


                const data =
                    await response.json();


                renderQueue(data);

            } catch (error) {

                console.error(
                    'QUEUE REFRESH ERROR:',
                    error
                );

            } finally {

                queueRequestInProgress =
                    false;

            }

        }


        refreshQueue();


        setInterval(
            refreshQueue,
            3000
        );


        /* =====================================================
           ADVERTISEMENTS
        ====================================================== */

        let advertisementItems = [];

        let advertisementIndex = 0;

        let advertisementTimer = null;

        let advertisementRequestInProgress =
            false;

        let advertisementSignature = '';


        function renderAdvertisements(items) {

            const list =
                Array.isArray(items)
                    ? items
                    : [];


            const signature =
                JSON.stringify(
                    list.map(
                        function(item) {

                            return {

                                id: item.id,

                                url: item.url,

                                type: item.type,

                                display_seconds:
                                    item.display_seconds

                            };

                        }
                    )
                );


            if (
                signature ===
                advertisementSignature &&
                advertisementItems.length > 0
            ) {

                return;

            }


            advertisementSignature =
                signature;


            advertisementItems =
                list;


            if (advertisementTimer) {

                clearTimeout(
                    advertisementTimer
                );

                advertisementTimer =
                    null;

            }


            if (!advertisementSliderElement) {
                return;
            }


            if (list.length === 0) {

                advertisementIndex =
                    0;


                advertisementSliderElement.innerHTML = `

                    <div class="flex h-full min-h-[500px] items-center justify-center bg-slate-950">

                        <div class="text-center">

                            <div class="text-7xl font-black tracking-tight text-slate-700 sm:text-8xl">
                                AYU
                            </div>

                            <div class="mt-3 text-xs font-black uppercase tracking-[0.3em] text-slate-600">
                                Health & Wellness
                            </div>

                        </div>

                    </div>

                `;

                return;

            }


            if (
                advertisementIndex >=
                list.length
            ) {

                advertisementIndex =
                    0;

            }


            showAdvertisement(
                advertisementIndex
            );

        }


        function showAdvertisement(index) {

            if (
                !advertisementSliderElement ||
                advertisementItems.length === 0
            ) {

                return;

            }


            const item =
                advertisementItems[index];


            if (
                !item ||
                !item.url
            ) {

                return;

            }


            advertisementSliderElement.innerHTML =
                '';


            const type =
                item.type === 'video'
                    ? 'video'
                    : 'image';


            if (type === 'video') {

                const slide =
                    document.createElement('div');


                slide.className =
                    'flex h-full min-h-[500px] w-full items-center justify-center overflow-hidden bg-slate-950';


                const video =
                    document.createElement('video');


                video.src =
                    item.url;


                video.autoplay =
                    true;


                video.muted =
                    true;


                video.playsInline =
                    true;


                video.preload =
                    'auto';


                video.className =
                    'block h-full max-h-full w-full bg-slate-950 object-contain';


                video.setAttribute(
                    'aria-label',
                    item.title ||
                    'Advertisement'
                );


                slide.appendChild(
                    video
                );


                advertisementSliderElement.appendChild(
                    slide
                );


                video.addEventListener(
                    'ended',
                    function() {

                        goToNextAdvertisement();

                    },
                    {
                        once: true
                    }
                );


                const seconds =
                    Math.max(
                        3,
                        Number(
                            item.display_seconds ||
                            8
                        )
                    );


                advertisementTimer =
                    setTimeout(
                        function() {

                            goToNextAdvertisement();

                        },
                        seconds * 1000
                    );


                const playPromise =
                    video.play();


                if (
                    playPromise &&
                    typeof playPromise.catch ===
                        'function'
                ) {

                    playPromise.catch(
                        function(error) {

                            console.warn(
                                'Video autoplay blocked:',
                                error
                            );

                        }
                    );

                }


                return;

            }


            const slide =
                document.createElement('div');


            slide.className =
                'flex h-full min-h-[500px] w-full items-center justify-center overflow-hidden bg-slate-950';


            const image =
                document.createElement('img');


            image.src =
                item.url;


            image.alt =
                item.title ||
                'Advertisement';


            image.loading =
                'eager';


            image.className =
                'block h-full max-h-full w-full bg-slate-950 object-contain';


            image.onerror =
                function() {

                    console.error(
                        'Advertisement image failed:',
                        item.url
                    );

                };


            slide.appendChild(
                image
            );


            advertisementSliderElement.appendChild(
                slide
            );


            const seconds =
                Math.max(
                    3,
                    Number(
                        item.display_seconds ||
                        8
                    )
                );


            advertisementTimer =
                setTimeout(
                    function() {

                        goToNextAdvertisement();

                    },
                    seconds * 1000
                );

        }


        function goToNextAdvertisement() {

            if (
                advertisementItems.length === 0
            ) {

                return;

            }


            if (advertisementTimer) {

                clearTimeout(
                    advertisementTimer
                );

                advertisementTimer =
                    null;

            }


            advertisementIndex =
                (
                    advertisementIndex + 1
                ) %
                advertisementItems.length;


            showAdvertisement(
                advertisementIndex
            );

        }


        /* =====================================================
           ADVERTISEMENT API
        ====================================================== */

        async function refreshAdvertisements() {

            if (
                advertisementRequestInProgress
            ) {

                return;

            }


            advertisementRequestInProgress =
                true;


            try {

                const url =
                    "{{ route('queue.public.advertisements') }}";


                const separator =
                    url.includes('?')
                        ? '&'
                        : '?';


                const requestUrl =
                    url +
                    separator +
                    '_advertisement_refresh=' +
                    Date.now();


                const response =
                    await fetch(
                        requestUrl,
                        {
                            method: 'GET',

                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Cache-Control': 'no-cache'
                            },

                            cache: 'no-store'
                        }
                    );


                if (!response.ok) {

                    throw new Error(
                        'HTTP ' +
                        response.status
                    );

                }


                const data =
                    await response.json();


                console.log(
                    'ADVERTISEMENT DATA:',
                    data
                );


                renderAdvertisements(
                    data.advertisements || []
                );


            } catch (error) {

                console.error(
                    'ADVERTISEMENT REFRESH ERROR:',
                    error
                );

            } finally {

                advertisementRequestInProgress =
                    false;

            }

        }


        refreshAdvertisements();


        setInterval(
            refreshAdvertisements,
            15000
        );


        /* =====================================================
           TAB / SCREEN RETURN
        ====================================================== */

        document.addEventListener(
            'visibilitychange',
            function() {

                if (
                    document.visibilityState ===
                    'visible'
                ) {

                    refreshQueue();

                    refreshAdvertisements();

                }

            }
        );

    </script>

</body>

</html>
