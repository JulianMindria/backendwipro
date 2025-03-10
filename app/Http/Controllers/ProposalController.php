<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function index()
    {
        return response()->json(Proposal::with(['initiator', 'approver'])->get(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'proposal_name' => 'required|string|max:255',
            'proposal_objective' => 'required|string|max:255',
            'proposal_realization' => 'required|date',
            'proposal_budget' => 'required|string|max:255',
            'proposal_file' => 'required|file',  // File validation
            'status' => 'required|in:PENDING,APPROVED,REJECTED',
            'proposal_approver' => 'nullable|exists:users,id',
            'proposal_initiator' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        $filePath = null;
        if ($request->hasFile('proposal_file')) {
            $filePath = $request->file('proposal_file')->store('proposals', 'public');
        }

        $proposal = Proposal::create([
            'proposal_name' => $request->proposal_name,
            'proposal_objective' => $request->proposal_objective,
            'proposal_realization' => $request->proposal_realization,
            'proposal_budget' => $request->proposal_budget,
            'proposal_file' => $filePath,  
            'status' => $request->status,
            'proposal_approver' => $request->proposal_approver,
            'proposal_initiator' => $request->proposal_initiator,
            'content' => $request->content,
        ]);

        return response()->json($proposal, 201);
    }

    public function show($id)
    {
        $proposal = Proposal::with(['initiator', 'approver'])->find($id);
        if (!$proposal) return response()->json(['message' => 'Proposal not found'], 404);

        return response()->json($proposal, 200);
    }

    public function update(Request $request, $id)
    {
        $proposal = Proposal::find($id);
        if (!$proposal) return response()->json(['message' => 'Proposal not found'], 404);

        $request->validate([
            'proposal_name' => 'string|max:255',
            'proposal_objective' => 'string|max:255',
            'proposal_realization' => 'date',
            'proposal_budget' => 'string|max:255',
            'proposal_file' => 'nullable|file',  
            'status' => 'in:PENDING,APPROVED,REJECTED',
            'proposal_approver' => 'nullable|exists:users,id',
            'proposal_initiator' => 'exists:users,id',
            'content' => 'string',
        ]);

        if ($request->hasFile('proposal_file')) {
            $filePath = $request->file('proposal_file')->store('proposals', 'public');
            $proposal->proposal_file = $filePath;
        }

        $proposal->update($request->except('proposal_file')); 
        return response()->json($proposal, 200);
    }

    public function destroy($id)
    {
        $proposal = Proposal::find($id);
        if (!$proposal) return response()->json(['message' => 'Proposal not found'], 404);
        $proposal->delete();

        return response()->json(['message' => 'Proposal deleted'], 200);
    }
}
