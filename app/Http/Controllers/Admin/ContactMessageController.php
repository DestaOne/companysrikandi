<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MessageHelper;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\ContactMessageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class ContactMessageController extends Controller
{
    public function __construct(private ContactMessageService $contactMessageService) {}

    public function index(): View
    {
        return view('admin.messages.index', [
            'messages' => $this->contactMessageService->paginate(),
        ]);
    }

    public function show(ContactMessage $contactMessage): View
    {
        // Pesan dianggap dibaca begitu dibuka, jadi lonceng dan menu ikut berkurang
        $this->contactMessageService->markAsRead($contactMessage);

        return view('admin.messages.show', ['message' => $contactMessage]);
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        try {
            $this->contactMessageService->delete($contactMessage);
        } catch (Throwable $e) {
            report($e);

            return back()->with(MessageHelper::error('Gagal menghapus pesan.'));
        }

        return redirect()->route('admin.messages.index')->with(MessageHelper::success('Pesan berhasil dihapus.'));
    }
}
