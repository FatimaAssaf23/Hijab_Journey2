<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function showPhotoForm()
    {
        return view('profile.photo');
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();
        $file = $request->file('photo');
        $path = $file->store('profile-photos', 'public');

        // Delete old photo if exists
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->profile_photo_path = $path;
        $user->save();

        return redirect()->route('profile.photo.form')->with('success', 'Profile picture updated successfully.');
    }
    /**
     * Show the user's profile edit form.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        \Log::info('Profile update request', $request->all());
        $user = $request->user();
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'bio' => 'nullable|string|max:1000',
                'current_password' => 'nullable|string',
                'password' => 'nullable|string|min:8|confirmed',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', $e->errors());
            throw $e;
        }
        \Log::info('Validated data', $data);
        $user->fill($data);
        // Handle password change if requested
        if (!empty($data['password'])) {
            if (empty($data['current_password']) || !\Hash::check($data['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $user->password = \Hash::make($data['password']);
        }
        $user->save();
        \Auth::setUser($user->fresh()); // Force reload of user in session
        \Log::info('User after save', $user->toArray());
        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
