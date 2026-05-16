<div class="space-y-8" x-data="signaturePad()">

    {{-- ==================== PROFILE INFO ==================== --}}
    <div id="info" class="scroll-mt-10">
        @include('profile._partials.info-form', [
            'user'       => $user,
            'action'     => route('profile.parent.update'),
            'extraFields'=> view('profile.parent._extra_fields', ['user' => $user])
        ])
    </div>

    {{-- ==================== DATA ANAK ==================== --}}
    @if($user->parent && $user->parent->student)
    <div id="children" class="scroll-mt-10">
        @include('profile._partials.role-data', [
            'title'    => 'Data Anak',
            'subtitle' => 'Informasi akademik anak yang terhubung',
            'content'  => '
                <div>
                    <p class="text-slate-400 text-[0.65rem] font-bold uppercase tracking-widest mb-1">
                        Nama Siswa
                    </p>
                    <p class="text-slate-700 font-bold text-sm">
                        ' . ($user->parent->student->user->name ?? '-') . '
                    </p>
                </div>

                <div>
                    <p class="text-slate-400 text-[0.65rem] font-bold uppercase tracking-widest mb-1">
                        NISN / NIS
                    </p>
                    <p class="text-slate-700 font-bold text-sm">
                        ' . $user->parent->student->nisn . ' / ' . $user->parent->student->nis . '
                    </p>
                </div>

                <div>
                    <p class="text-slate-400 text-[0.65rem] font-bold uppercase tracking-widest mb-1">
                        Kelas
                    </p>
                    <p class="text-slate-700 font-bold text-sm">
                        ' . ($user->parent->student->class_name ?? '-') . '
                    </p>
                </div>
            '
        ])
    </div>
    @endif

    {{-- ==================== SECURITY ==================== --}}
    <div id="security" class="scroll-mt-10">
        @include('profile._partials.password-form', [
            'action' => route('profile.parent.password')
        ])
    </div>

    {{-- ==================== SIGNATURE PAD ALPINE COMPONENT ==================== --}}
    <script>
    function signaturePad() {
        return {

            // State
            showSignatureModal : false,
            hasSignature       : false,
            penColor           : '#0f172a',
            drawing            : false,

            // Drawing data
            strokes  : [],
            _current : [],

            // ==================== LIFECYCLE ====================
            init() {
                window.addEventListener('resize', () => {
                    if (this.showSignatureModal) {
                        this.$nextTick(() => this._initCanvas());
                    }
                });
            },

            // ==================== MODAL CONTROL ====================
            openSignatureModal() {
                this.showSignatureModal = true;
                document.body.classList.add('overflow-hidden');

                this.$nextTick(() => this._initCanvas());
            },

            closeSignatureModal() {
                this.showSignatureModal = false;
                document.body.classList.remove('overflow-hidden');
            },

            // ==================== CANVAS DRAWING ====================
            _initCanvas() {
                const canvas = this.$refs.sigCanvas;
                if (!canvas) return;

                const rect = canvas.getBoundingClientRect();
                const dpr  = window.devicePixelRatio || 1;

                canvas.width  = rect.width * dpr;
                canvas.height = rect.height * dpr;

                const ctx = canvas.getContext('2d');
                ctx.scale(dpr, dpr);
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';

                this._redrawAll();
            },

            _ctx() {
                return this.$refs.sigCanvas?.getContext('2d');
            },

            _pos(e) {
                const canvas = this.$refs.sigCanvas;
                const rect   = canvas.getBoundingClientRect();
                const src    = e.touches ? e.touches[0] : e;

                return {
                    x: src.clientX - rect.left,
                    y: src.clientY - rect.top
                };
            },

            startDraw(e) {
                e.preventDefault();
                this.drawing = true;
                this._current = [];

                const ctx = this._ctx();
                const pos = this._pos(e);

                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);

                this._current.push({ x: pos.x, y: pos.y, color: this.penColor });
            },

            draw(e) {
                if (!this.drawing) return;
                e.preventDefault();

                const ctx = this._ctx();
                const pos = this._pos(e);

                ctx.lineWidth = 2.5;
                ctx.strokeStyle = this.penColor;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';

                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();

                this._current.push({ x: pos.x, y: pos.y, color: this.penColor });
            },

            stopDraw() {
                if (!this.drawing) return;

                this.drawing = false;

                if (this._current.length > 1) {
                    this.strokes.push([...this._current]);
                    this.hasSignature = true;
                }
                this._current = [];
            },

            // ==================== ACTIONS ====================
            undoStroke() {
                if (this.strokes.length === 0) return;

                this.strokes.pop();
                this._redrawAll();
                this.hasSignature = this.strokes.length > 0;
            },

            clearAll() {
                this.strokes = [];
                this._current = [];
                this.hasSignature = false;

                const canvas = this.$refs.sigCanvas;
                const ctx = this._ctx();

                if (ctx && canvas) {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                }

                document.getElementById('signature_data_input').value = '';
            },

            _redrawAll() {
                const canvas = this.$refs.sigCanvas;
                const ctx = this._ctx();
                if (!ctx || !canvas) return;

                ctx.clearRect(0, 0, canvas.width, canvas.height);

                this.strokes.forEach(stroke => {
                    if (stroke.length < 1) return;

                    ctx.beginPath();
                    ctx.moveTo(stroke[0].x, stroke[0].y);
                    ctx.lineWidth = 2.5;
                    ctx.lineCap = 'round';
                    ctx.lineJoin = 'round';

                    stroke.forEach((pt, i) => {
                        if (i === 0) return;
                        ctx.strokeStyle = pt.color;
                        ctx.lineTo(pt.x, pt.y);
                        ctx.stroke();
                        ctx.beginPath();
                        ctx.moveTo(pt.x, pt.y);
                    });
                });
            },

            prepareSubmit() {
                if (!this.hasSignature) return;

                const input = document.getElementById('signature_data_input');
                if (input) {
                    input.value = this.$refs.sigCanvas.toDataURL('image/png');
                }
            },

            saveSignature() {
                this.prepareSubmit();
                this.closeSignatureModal();
            }
        };
    }
    </script>
</div>