<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // Show the profile edit page
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    // Update the user's profile information (name, etc.)
    public function update(Request $request)
    {
        // Validate the input
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birth_month' => 'required|integer|min:1|max:12',
            'birth_day' => 'required|integer|min:1|max:31',
            'birth_year' => 'required|integer|min:1900|max:'.date('Y'),
            'gender' => 'required|string|in:female,male,custom',
            'pronouns' => 'nullable|string|in:she/her,he/his,they/them',
        ]);
    
        // Update the user profile
        $user = auth()->user();
        $user->update([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'suffix' => $request->input('suffix'),
            'birth_date' => Carbon::createFromDate($request->input('birth_year'), $request->input('birth_month'), $request->input('birth_day'))->format('Y-m-d'),
            'gender' => $request->input('gender'),
            'pronouns' => ($request->input('gender') === 'custom') ? $request->input('pronouns') : null,
        ]);
    
        // Redirect back or to another page
        return redirect()->route('profile.edit');
    }
    
    // Update the user's email address
    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->update([
            'email' => $request->email,
        ]);

        return redirect()->route('profile.edit')->with('status', 'email-updated');
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
