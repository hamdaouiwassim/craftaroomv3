<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TeamMember::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('position', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        $teamMembers = $query->orderBy('order')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.team-members.index', [
            'teamMembers' => $teamMembers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.team-members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo');
            $fileName = uniqid('team_') . "." . $filePath->getClientOriginalExtension();
            $filePath->storeAs('uploads/team', $fileName, 'public');
            $photoUrl = "/storage/uploads/team/" . $fileName;
        }

        $socialLinks = [];
        if ($request->facebook) $socialLinks['facebook'] = $request->facebook;
        if ($request->twitter) $socialLinks['twitter'] = $request->twitter;
        if ($request->instagram) $socialLinks['instagram'] = $request->instagram;
        if ($request->linkedin) $socialLinks['linkedin'] = $request->linkedin;

        TeamMember::create([
            'name' => $validated['name'],
            'position' => $validated['position'],
            'bio' => $validated['bio'] ?? null,
            'photo_url' => $photoUrl,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'social_links' => !empty($socialLinks) ? $socialLinks : null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.team-members.index')
            ->with('success', 'Membre de l\'équipe créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TeamMember $teamMember)
    {
        return view('admin.team-members.show', [
            'teamMember' => $teamMember
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeamMember $teamMember)
    {
        return view('admin.team-members.edit', [
            'teamMember' => $teamMember
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeamMember $teamMember)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($teamMember->photo_url) {
                $oldFilePath = public_path($teamMember->photo_url);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $filePath = $request->file('photo');
            $fileName = uniqid('team_') . "." . $filePath->getClientOriginalExtension();
            $filePath->storeAs('uploads/team', $fileName, 'public');
            $teamMember->photo_url = "/storage/uploads/team/" . $fileName;
        }

        $socialLinks = [];
        if ($request->facebook) $socialLinks['facebook'] = $request->facebook;
        if ($request->twitter) $socialLinks['twitter'] = $request->twitter;
        if ($request->instagram) $socialLinks['instagram'] = $request->instagram;
        if ($request->linkedin) $socialLinks['linkedin'] = $request->linkedin;

        $teamMember->name = $validated['name'];
        $teamMember->position = $validated['position'];
        $teamMember->bio = $validated['bio'] ?? null;
        $teamMember->email = $validated['email'] ?? null;
        $teamMember->phone = $validated['phone'] ?? null;
        $teamMember->social_links = !empty($socialLinks) ? $socialLinks : null;
        $teamMember->order = $validated['order'] ?? 0;
        $teamMember->is_active = $validated['is_active'] ?? true;
        $teamMember->save();

        return redirect()->route('admin.team-members.index')
            ->with('success', 'Membre de l\'équipe mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeamMember $teamMember)
    {
        // Delete photo if exists
        if ($teamMember->photo_url) {
            $filePath = public_path($teamMember->photo_url);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $teamMember->delete();

        return redirect()->route('admin.team-members.index')
            ->with('success', 'Membre de l\'équipe supprimé avec succès.');
    }
}
