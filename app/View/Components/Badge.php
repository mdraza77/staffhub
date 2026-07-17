<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Closure;

class Badge extends Component
{
    /**
     * Create a new component instance.
     */
    public string $value;

    public string $label;
    public string $classes;

    public function __construct(string $value)
    {
        $this->value = $value;
        $this->label = str($value)
            ->replace('_', ' ')
            ->title();

        $this->classes = match (strtolower($value)) {
            // Project / Task
            'planning' => 'blue',
            'in_progress' => 'amber',
            'on_hold' => 'gray',
            'completed' => 'emerald',
            'cancelled' => 'rose',

            // Employee
            'active' => 'emerald',
            'inactive' => 'gray',
            'terminated' => 'rose',

            // Leave
            'approved' => 'emerald',
            'rejected' => 'rose',
            'pending' => 'amber',

            // Defects / Tasks
            'open' => 'blue',
            'testing' => 'violet',
            'ready_for_testing' => 'violet',
            'closed' => 'emerald',
            'reopened' => 'amber',

            // Employee Breaks
            'ongoing' => 'blue',
            'auto_completed' => 'gray',

            // Announcements
            'published' => 'emerald',
            'draft' => 'gray',

            // Payslips
            'paid' => 'emerald',
            'unpaid' => 'rose',

            // Attendance
            'present' => 'emerald',
            'absent' => 'rose',
            'half_day' => 'amber',
            'on_leave' => 'blue',
            'late' => 'orange',

            // Priority / Severity
            'low' => 'blue',
            'medium' => 'amber',
            'high' => 'orange',
            'critical' => 'rose',
            'urgent' => 'rose',

            default => 'gray',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.badge');
    }
}
