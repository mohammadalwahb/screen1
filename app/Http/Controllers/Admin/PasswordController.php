<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function edit(): View
    {
        return view('admin.password.edit');
    }

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => $request->validated('password'),
        ]);

        Log::info('Admin password updated successfully.', [
            'user_id' => $request->user()->id,
            'ip' => $request->ip(),
        ]);

        return redirect()
            ->route('admin.password.edit')
            ->with('status', 'Password changed successfully.');
    }
}
