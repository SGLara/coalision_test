<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Task;
use Livewire\Component;

class TaskSortableTable extends Component
{
    public $newTaskName;
    public $editingTaskId;
    public $taskName = [];
    public $selectedProjectId = [];
    public $filteredProjectId;

    public function createTask()
    {
        $this->validate([
            'newTaskName' => ['required', 'regex:/[A-z\s]+/i'],
        ]);

        $totalTasks = Task::max('priority');

        Task::create([
            'name' => $this->newTaskName,
            'priority' => ++$totalTasks
        ]);

        $this->newTaskName = '';
    }

    public function editTaskName($taskId)
    {
        $this->editingTaskId = $taskId;
        $this->taskName[$taskId] = Task::find($taskId)->name;
    }

    public function updateTask($taskId)
    {
        $task = Task::find($taskId);
        $task->update(['name' => $this->taskName[$taskId]]);

        $this->editingTaskId = '';
    }

    public function updateTaskOrder($tasks)
    {
        foreach ($tasks as $task) {
            Task::whereId($task['value'])->update(['priority' => $task['order']]);
        }
    }

    public function deleteTask($taskId)
    {
        Task::whereId($taskId)->delete();
    }

    public function addProjectId($taskId)
    {
        $task = Task::find($taskId);
        $task->update(['project_id' => $this->selectedProjectId[$taskId]]);
    }

    public function render()
    {
        $taskQuery = Task::orderBy('priority');

        if ($this->filteredProjectId) {
            $taskQuery = $taskQuery->whereProjectId($this->filteredProjectId);
        }

        return view('livewire.task-sortable-table', [
            'tasks' => $taskQuery->get(),
            'projects' => Project::all()
        ]);
    }
}
