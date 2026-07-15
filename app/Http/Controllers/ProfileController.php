<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use ImageKit\ImageKit;

class ProfileController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Employee-Profile-Edit', only: ['edit', 'update', 'removePhoto']),
            new Middleware('permission:Employee-Profile-Index', only: ['index']),
        ];
    }

    public function index()
    {
        $user = auth()->user()->load('department');
        return view('profile.index', compact('user'));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, User $employee): RedirectResponse
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:255',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageKit = new ImageKit(
            env('IMAGEKIT_PUBLIC_KEY'),
            env('IMAGEKIT_PRIVATE_KEY'),
            env('IMAGEKIT_URL_ENDPOINT')
        );

        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'designation' => $request->designation,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // if ($request->hasFile('profile')) {
            //     if ($user->profile) {
            //         Storage::disk('public')->delete($user->profile);
            //     }
            //     $data['profile'] = $request->file('profile')->store('profiles', 'public');
            // }

            if ($request->hasFile('profile')) {
                // Delete old image if exists
                if ($employee->profile) {
                    if (str_starts_with($employee->profile, 'http')) {
                        $this->deleteImageKitFileByUrl($employee->profile, $imageKit);
                    } else {
                        Storage::disk('public')->delete($employee->profile);
                    }
                }

                $upload = $imageKit->uploadFile([
                    'file' => fopen($request->file('profile')->getRealPath(), 'r'),
                    'fileName' => time() . '_' . $request->file('profile')->getClientOriginalName(),
                    'folder' => '/StaffHub/profile_pictures'
                ]);

                if ($upload->error) {
                    throw new \Exception('ImageKit Profile Upload Error: ' . json_encode($upload->error));
                }

                $data['profile'] = $upload->result->url;
            }

            $user->update($data);

            DB::commit();

            return redirect()
                ->route('profile.index')
                ->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Profile Update Failed: ' . $e->getMessage());

            if (isset($data['profile'])) {
                Storage::disk('public')->delete($data['profile']);
            }

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function removePhoto()
    {
        $user = auth()->user();

        if ($user->profile) {
            Storage::disk('public')->delete($user->profile);
            $user->update(['profile' => null]);
        }

        return redirect()
            ->route('profile.index')
            ->with('success', 'Profile photo removed.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
