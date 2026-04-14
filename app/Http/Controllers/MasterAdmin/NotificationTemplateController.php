<?php

namespace App\Http\Controllers\MasterAdmin;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use App\Services\Notification\NotificationTemplateService;
use Illuminate\Http\Request;

class NotificationTemplateController extends Controller
{
    public function __construct(protected NotificationTemplateService $service)
    {
    }

    /* ── index ─────────────────────────────────────────────────── */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = NotificationTemplate::query()->with('school');

        if ($user->role === 'masteradmin') {
            // Masteradmin: lihat semua template
        } elseif ($user->role === 'admin') {
            $query->where(function ($q) use ($user) {
                $q->where(function ($inner) {
                    $inner->whereNull('school_id')
                        ->where('editable_by', 'admin_school');
                })->orWhere(function ($inner) use ($user) {
                    $inner->where('school_id', $user->school_id);
                });
            });
        } else {
            abort(403);
        }

        // ── Per-page: ambil dari request, validasi nilainya ──────
        $perPage = in_array((int) $request->per_page, [10, 25, 50, 100])
            ? (int) $request->per_page
            : 10;

        $templates = $query
            ->when($request->channel, fn($q) => $q->where('channel', $request->channel))
            ->when($request->event, fn($q) => $q->where('event', $request->event))
            ->when($request->role, fn($q) => $q->whereJsonContains('target_role', $request->role))
            ->orderByRaw('school_id IS NULL ASC')
            ->orderBy('event')
            ->paginate($perPage)
            ->withQueryString();

        $forkedKeys = collect();
        if ($user->role === 'admin') {
            $forkedKeys = NotificationTemplate::where('school_id', $user->school_id)
                ->get()
                ->map(fn($t) => $t->event . '_' . $t->channel . '_' . (is_array($t->target_role) ? implode(',', $t->target_role) : $t->target_role))
                ->flip();
        }

        return view('masteradmin.notification-templates.index', compact('templates', 'forkedKeys'));
    }

    /* ── create ─────────────────────────────────────────────────── */
    public function create()
    {
        $this->authorizeCreation();
        return view('masteradmin.notification-templates.create');
    }

    /* ── store ──────────────────────────────────────────────────── */
    public function store(Request $request)
    {
        $this->authorizeCreation();

        $data = $request->validate([
            'event' => 'required|string|max:100',
            'channel' => 'required|in:dashboard,email,whatsapp',
            'target_role' => 'required|array',
            'target_role.*' => 'in:siswa,guru,orangtua,admin,masteradmin,all',
            'message_template' => 'required|string',
            'editable_by' => 'required|in:masteradmin,admin_school',
            'is_active' => 'boolean',
            'school_id' => 'nullable|exists:schools,id',
        ]);

        if (auth()->user()->role !== 'masteradmin') {
            $data['school_id'] = auth()->user()->school_id;
            $data['editable_by'] = 'admin_school';
        }

        $this->service->create($data, auth()->user());

        return redirect()->route($this->indexRoute())
            ->with('success', 'Template notifikasi berhasil dibuat.');
    }

    /* ── show ───────────────────────────────────────────────────── */
    public function show(NotificationTemplate $notificationTemplate)
    {
        $this->authorizeAccess($notificationTemplate);
        return view('masteradmin.notification-templates.show', [
            'template' => $notificationTemplate,
        ]);
    }

    /* ── edit ───────────────────────────────────────────────────── */
    public function edit(NotificationTemplate $notificationTemplate)
    {
        $this->authorizeAccess($notificationTemplate);

        $user = auth()->user();

        $existingFork = null;
        if ($user->role === 'admin' && is_null($notificationTemplate->school_id)) {
            $existingFork = NotificationTemplate::where('school_id', $user->school_id)
                ->where('event', $notificationTemplate->event)
                ->where('channel', $notificationTemplate->channel)
                ->where('target_role', $notificationTemplate->target_role)
                ->first();
        }

        $templateToEdit = $existingFork ?? $notificationTemplate;

        return view('masteradmin.notification-templates.edit', [
            'template' => $templateToEdit,
            'globalTemplate' => $notificationTemplate,
            'isFork' => !is_null($existingFork),
        ]);
    }

    /* ── update ─────────────────────────────────────────────────── */
    public function update(Request $request, NotificationTemplate $notificationTemplate)
    {
        $this->authorizeAccess($notificationTemplate);

        $data = $request->validate([
            'message_template' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $user = auth()->user();

        if ($user->role === 'admin' && is_null($notificationTemplate->school_id)) {

            $fork = NotificationTemplate::firstOrCreate(
                [
                    'school_id' => $user->school_id,
                    'event' => $notificationTemplate->event,
                    'channel' => $notificationTemplate->channel,
                    'target_role' => $notificationTemplate->target_role,
                ],
                [
                    'editable_by' => 'admin_school',
                    'is_active' => true,
                    'message_template' => $data['message_template'],
                ]
            );

            $fork->update([
                'message_template' => $data['message_template'],
                'is_active' => $data['is_active'] ?? $fork->is_active,
            ]);

            return redirect()->route($this->indexRoute())
                ->with('success', 'Template berhasil disimpan sebagai versi khusus sekolah Anda.');
        }

        $this->service->update($notificationTemplate, $data, $user);

        return redirect()->route($this->indexRoute())
            ->with('success', 'Template notifikasi berhasil diperbarui.');
    }

    /* ── destroy ────────────────────────────────────────────────── */
    public function destroy(NotificationTemplate $notificationTemplate)
    {
        abort_unless(auth()->user()->role === 'masteradmin', 403);
        $notificationTemplate->delete();

        return redirect()->route('masteradmin.notification-templates.index')
            ->with('success', 'Template berhasil dihapus.');
    }

    /* ── destroy fork ───────────────────────────────────────────── */
    public function destroyFork(NotificationTemplate $notificationTemplate)
    {
        $user = auth()->user();

        abort_unless(
            $user->role === 'admin' && $notificationTemplate->school_id === $user->school_id,
            403,
            'Anda hanya dapat menghapus kustomisasi milik sekolah Anda sendiri.'
        );

        $notificationTemplate->delete();

        return redirect()->route('school-admin.notification-templates.index')
            ->with('success', 'Kustomisasi dihapus. Template kembali ke versi global.');
    }

    /* ── toggle active ──────────────────────────────────────────── */
    public function toggle(NotificationTemplate $notificationTemplate)
    {
        $this->authorizeAccess($notificationTemplate);
        $this->service->toggle($notificationTemplate, auth()->user());

        return back()->with('success', 'Status template berhasil diubah.');
    }

    /* ── helpers ────────────────────────────────────────────────── */

    private function indexRoute(): string
    {
        return auth()->user()->role === 'admin'
            ? 'school-admin.notification-templates.index'
            : 'masteradmin.notification-templates.index';
    }

    private function authorizeCreation(): void
    {
        $role = auth()->user()->role;
        abort_unless(in_array($role, ['masteradmin', 'admin']), 403);
    }

    private function authorizeAccess(NotificationTemplate $template): void
    {
        $user = auth()->user();

        if ($user->role === 'masteradmin')
            return;

        if ($user->role === 'admin' && is_null($template->school_id) && $template->editable_by === 'admin_school')
            return;

        if ($user->role === 'admin' && $template->school_id === $user->school_id)
            return;

        abort(403, 'Anda tidak memiliki akses ke template ini.');
    }
}