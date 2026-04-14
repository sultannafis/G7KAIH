<div class="space-y-8" x-data="signaturePad()">
    <!-- Profile Info Section -->
    <div id="info" class="scroll-mt-10">
        @include('profile._partials.info-form', [
            'user' => $user,
            'action' => route('profile.parent.update'),
            'extraFields' => view('profile.parent._extra_fields', ['user' => $user])
        ])
    </div>

    <!-- Data Anak (Display Only) -->
    @if($user->parent && $user->parent->student)
    <div id="children" class="scroll-mt-10">
        @include('profile._partials.role-data', [
            'title' => 'Data Anak',
            'subtitle' => 'Informasi akademik anak yang terhubung',
            'content' => '
                <div>
                  <p class=\"text-slate-400 text-[0.65rem] font-bold uppercase tracking-widest mb-1\">Nama Siswa</p>
                  <p class=\"text-slate-700 font-bold text-sm\">' . ($user->parent->student->user->name ?? '-') . '</p>
                </div>
                <div>
                  <p class=\"text-slate-400 text-[0.65rem] font-bold uppercase tracking-widest mb-1\">NISN / NIS</p>
                  <p class=\"text-slate-700 font-bold text-sm\">' . $user->parent->student->nisn . ' / ' . $user->parent->student->nis . '</p>
                </div>
                <div>
                  <p class=\"text-slate-400 text-[0.65rem] font-bold uppercase tracking-widest mb-1\">Kelas</p>
                  <p class=\"text-slate-700 font-bold text-sm\">' . ($user->parent->student->class_name ?? '-') . '</p>
                </div>
            '
        ])
    </div>
    @endif

    <!-- Security Section -->
    <div id="security" class="scroll-mt-10">
        @include('profile._partials.password-form', [
            'action' => route('profile.parent.password')
        ])
    </div>

    @push('scripts')
    <script>
    function signaturePad() {
        return {
            mode: 'canvas',
            hasSignature: false,
            penColor: '#1e293b',
            drawing: false,
            strokes: [],
            _current: [],

            init() {
                this.$nextTick(() => this._initCanvas());
            },
            _initCanvas() {
                const c = this.$refs.sigCanvas;
                if (!c) return;
                const dpr = window.devicePixelRatio || 1;
                const rect = c.getBoundingClientRect();
                c.width  = (rect.width  || 560) * dpr;
                c.height = (rect.height || 180) * dpr;
                const ctx = c.getContext('2d');
                ctx.scale(dpr, dpr);
            },
            _ctx() { return this.$refs.sigCanvas?.getContext('2d'); },
            _pos(e) {
                const c = this.$refs.sigCanvas;
                const rect = c.getBoundingClientRect();
                const src = e.touches ? e.touches[0] : e;
                return { x: src.clientX - rect.left, y: src.clientY - rect.top };
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
                if (this._current.length > 0) {
                    this.strokes.push([...this._current]);
                    this.hasSignature = true;
                    this._current = [];
                }
            },
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
                const c = this.$refs.sigCanvas;
                this._ctx().clearRect(0, 0, c.offsetWidth, c.offsetHeight);
                const input = document.getElementById('signature_data_input');
                if(input) input.value = '';
            },
            _redrawAll() {
                const c = this.$refs.sigCanvas;
                const ctx = this._ctx();
                ctx.clearRect(0, 0, c.offsetWidth, c.offsetHeight);
                this.strokes.forEach(stroke => {
                    if (stroke.length < 1) return;
                    ctx.beginPath();
                    ctx.moveTo(stroke[0].x, stroke[0].y);
                    ctx.lineWidth = 2.5; ctx.lineCap = 'round'; ctx.lineJoin = 'round';
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
                if (this.mode === 'canvas' && this.hasSignature) {
                    const input = document.getElementById('signature_data_input');
                    if(input) input.value = this.$refs.sigCanvas.toDataURL('image/png');
                }
            }
        };
    }
    </script>
    @endpush
</div>
