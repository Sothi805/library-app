<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::with(['borrows' => function($query) {
            $query->where('status', 'borrowed');
        }])->latest()->get();
        return view('pages.member.index', compact('members'));
    }

    public function create()
    {
        return view('pages.member.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'     => ['required', 'string', 'max:255'],
            'middle_name'    => ['nullable', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'gender'         => ['required', Rule::in(['male', 'female'])],
            'email'          => ['nullable', 'email', 'unique:members,email'],
            'phone'          => ['nullable', 'string', 'unique:members,phone'],
        ]);

        // Generate member code
        $lastMember = Member::orderBy('member_code', 'desc')->first();
        $nextNumber = $lastMember ? ((int) substr($lastMember->member_code, 1)) + 1 : 1;
        $memberCode = 'M' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        Member::create([
            ...$validated,
            'member_code' => $memberCode,
            'status' => 'active',
            'added_by' => Auth::id(),
            'snapshot_added_by' => trim(
                collect([
                    Auth::user()->first_name ?? '',
                    Auth::user()->middle_name ?? '',
                    Auth::user()->last_name ?? '',
                ])->filter()->join(' ')
            ),
        ]);

        return redirect()->route('members.index')
            ->with('success', 'Member added successfully!');
    }

    public function show(Request $request, Member $member)
    {
        $perPage = $request->get('per_page', 10);

        $borrowHistory = $member->borrows()
            ->with('book')
            ->latest()
            ->paginate($perPage, ['*'], 'history_page')
            ->withQueryString();

        $activeBorrows = $member->borrows()
            ->where('status', 'borrowed')
            ->with('book')
            ->latest()
            ->paginate($perPage, ['*'], 'current_page')
            ->withQueryString();

        return view('pages.member.details', compact(
            'member',
            'borrowHistory',
            'activeBorrows',
            'perPage'
        ));
    }

    public function edit(Member $member)
    {
        return view('pages.member.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'first_name'     => ['required', 'string', 'max:255'],
            'middle_name'    => ['nullable', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'gender'         => ['required', Rule::in(['male', 'female'])],
            'email'          => ['nullable', 'email', Rule::unique('members', 'email')->ignore($member->id)],
            'phone'          => ['nullable', 'string', Rule::unique('members', 'phone')->ignore($member->id)],
            'status'         => ['required', Rule::in(['active', 'inactive'])],
        ]);

        // Set inactive_since if status changed to inactive
        if ($validated['status'] === 'inactive' && $member->status === 'active') {
            $validated['inactive_since'] = now();
        }
        // Clear inactive_since if status changed to active
        else if ($validated['status'] === 'active' && $member->status === 'inactive') {
            $validated['inactive_since'] = null;
        }

        $member->update([
            ...$validated,
            'updated_by' => Auth::id(),
            'snapshot_updated_by' => trim(
                collect([
                    Auth::user()->first_name ?? '',
                    Auth::user()->middle_name ?? '',
                    Auth::user()->last_name ?? '',
                ])->filter()->join(' ')
            ),
        ]);

        return redirect()->route('members.index')
            ->with('success', 'Member updated successfully!');
    }

    public function destroy(Member $member)
    {
        // Check if member has any active borrows
        if ($member->borrows()->where('status', 'borrowed')->exists()) {
            return back()->with('error', 'Cannot delete member with active borrows.');
        }

        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Member deleted successfully!');
    }
}
