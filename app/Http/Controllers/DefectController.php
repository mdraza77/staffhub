<?php

namespace App\Http\Controllers;

use App\Models\Defect;
use App\Models\DefectAttachment;
use App\Models\DefectStatusHistory;
use App\Models\Task;
use App\Models\User;
use App\Services\ImageKitService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DefectController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Defect-Index', only: ['index']),
            new Middleware('permission:Defect-Create', only: ['create', 'store']),
            new Middleware('permission:Defect-Edit', only: ['edit', 'update']),
            new Middleware('permission:Defect-Delete', only: ['destroy']),
            new Middleware('permission:Defect-Restore', only: ['restore']),
            new Middleware('permission:Defect-View', only: ['show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin') || $user->hasRole('Admin') || $user->hasRole('HR Manager')) {
            $defects = Defect::withTrashed()->with(['reporter', 'assignee'])->latest()->get();
        } else {
            $defects = Defect::withTrashed()
                ->with(['reporter', 'assignee'])
                ->where(function ($q) use ($user) {
                    $q
                        ->where('reported_by', $user->id)
                        ->orWhere('assigned_to', $user->id);
                })
                ->latest()
                ->get();
        }

        return view('defects.index', compact('defects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = User::withTrashed()->where('status', 'active')->orderBy('name')->get();
        $projects = Task::whereNotNull('project_name')->distinct()->pluck('project_name');
        return view('defects.create', compact('employees', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'steps_to_reproduce' => 'nullable|string',
            'module' => 'required|string|max:255',
            'sub_module' => 'nullable|string|max:255',
            'environment' => 'required|string|max:255',
            'browser_os' => 'nullable|string|max:255',
            'severity' => 'required|in:low,medium,high,critical',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            $defect = Defect::create([
                'project_name' => $request->project_name,
                'title' => $request->title,
                'description' => $request->description,
                'steps_to_reproduce' => $request->steps_to_reproduce,
                'module' => $request->module,
                'sub_module' => $request->sub_module,
                'environment' => $request->environment,
                'browser_os' => $request->browser_os,
                'severity' => $request->severity,
                'priority' => $request->priority,
                'status' => 'open',
                'reported_by' => Auth::id(),
                'assigned_to' => $request->assigned_to,
            ]);

            // Create initial history log
            $defect->histories()->create([
                'changed_by' => Auth::id(),
                'old_status' => null,
                'new_status' => 'open',
                'remark' => 'Defect reported in the system.',
            ]);

            DB::commit();
            return redirect()->route('defects.index')->with('success', 'Defect reported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Defect Create Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong while reporting the defect.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Defect $defect)
    {
        $defect->load(['reporter', 'assignee', 'attachments.uploader', 'histories.user']);
        $employees = User::withTrashed()->where('status', 'active')->orderBy('name')->get();
        $projects = Task::whereNotNull('project_name')->distinct()->pluck('project_name');
        // dd($defect);
        return view('defects.show', compact('defect', 'employees', 'projects'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Defect $defect)
    {
        $employees = User::withTrashed()->where('status', 'active')->orderBy('name')->get();
        $projects = Task::whereNotNull('project_name')->distinct()->pluck('project_name');
        return view('defects.edit', compact('defect', 'employees', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Defect $defect)
    {
        $request->validate([
            'project_name' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'steps_to_reproduce' => 'nullable|string',
            'module' => 'required|string|max:255',
            'sub_module' => 'nullable|string|max:255',
            'environment' => 'required|string|max:255',
            'browser_os' => 'nullable|string|max:255',
            'severity' => 'required|in:low,medium,high,critical',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:open,in_progress,ready_for_testing,closed,reopened',
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $defect->status;
            $newStatus = $request->status;

            $defect->update([
                'project_name' => $request->project_name,
                'title' => $request->title,
                'description' => $request->description,
                'steps_to_reproduce' => $request->steps_to_reproduce,
                'module' => $request->module,
                'sub_module' => $request->sub_module,
                'environment' => $request->environment,
                'browser_os' => $request->browser_os,
                'severity' => $request->severity,
                'priority' => $request->priority,
                'status' => $newStatus,
                'assigned_to' => $request->assigned_to,
            ]);

            if ($oldStatus !== $newStatus) {
                $defect->histories()->create([
                    'changed_by' => Auth::id(),
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'remark' => 'Status updated via defect editor details.',
                ]);
            }

            DB::commit();
            return redirect()->route('defects.index')->with('success', 'Defect details updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Defect Update Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong while updating defect details.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Defect $defect)
    {
        try {
            $defect->delete();
            return redirect()->route('defects.index')->with('success', 'Defect archived successfully.');
        } catch (\Exception $e) {
            Log::error('Defect Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Could not archive the defect.');
        }
    }

    /**
     * Store attachment for the defect.
     */
    public function storeAttachment(Request $request, Defect $defect)
    {
        $imageKit = ImageKitService::getInstance();
        $request->validate([
            'file' => 'required|file|max:10240',  // Max 10MB file
        ]);

        $filePath = null;

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalName = $file->getClientOriginalName();
                $fileType = $file->getClientOriginalExtension();
                $fileSize = round($file->getSize() / 1024, 2);  // Size in KB

                $upload = $imageKit->uploadFile([
                    'file' => fopen($file->getRealPath(), 'r'),
                    'fileName' => time() . '_' . $originalName,
                    'folder' => '/StaffHub/defect_attachments'
                ]);

                if ($upload->error) {
                    throw new \Exception('ImageKit Upload Error: ' . json_encode($upload->error));
                }

                $filePath = $upload->result->url;

                $defect->attachments()->create([
                    'uploaded_by' => Auth::id(),
                    'file_name' => $originalName,
                    'file_path' => $filePath,
                    'file_type' => $fileType,
                    'file_size' => $fileSize,
                ]);

                return back()->with('success', 'Attachment uploaded successfully.');
            }

            return back()->with('error', 'No file was selected.');
        } catch (\Exception $e) {
            Log::error('Defect Attachment Store Error: ' . $e->getMessage());
            if ($filePath) {
                if (str_starts_with($filePath, 'http')) {
                    $this->deleteImageKitFileByUrl($filePath, $imageKit);
                } else {
                    Storage::disk('public')->delete($filePath);
                }
            }
            return back()->with('error', 'Something went wrong while uploading attachment.');
        }
    }

    /**
     * Update defect status directly.
     */
    public function updateStatus(Request $request, Defect $defect)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,ready_for_testing,closed,reopened',
            'remark' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $defect->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return back()->with('info', 'Status is already set to ' . ucwords(str_replace('_', ' ', $newStatus)));
        }

        try {
            DB::beginTransaction();

            $defect->update([
                'status' => $newStatus,
            ]);

            $defect->histories()->create([
                'changed_by' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'remark' => $request->remark ?? 'Status changed.',
            ]);

            DB::commit();
            return back()->with('success', 'Defect status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Defect Status Update Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while changing defect status.');
        }
    }

    /**
     * Restore the soft-deleted resource.
     */
    public function restore($id)
    {
        try {
            $defect = Defect::onlyTrashed()->findOrFail($id);
            $defect->restore();
            return redirect()->route('defects.index')->with('success', 'Defect restored successfully.');
        } catch (\Exception $e) {
            Log::error('Defect Restore Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while restoring the defect.');
        }
    }

    /**
     * Helper to delete a file from ImageKit by its URL.
     */
    private function deleteImageKitFileByUrl($url, $imageKit)
    {
        if (empty($url) || !str_starts_with($url, 'http')) {
            return;
        }

        try {
            $fileName = basename(parse_url($url, PHP_URL_PATH));
            $filesList = $imageKit->listFiles([
                'searchQuery' => 'name = "' . $fileName . '"'
            ]);

            if (!$filesList->error && !empty($filesList->result)) {
                $fileId = $filesList->result[0]->fileId;
                $imageKit->deleteFile($fileId);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to delete file from ImageKit: ' . $e->getMessage());
        }
    }
}
