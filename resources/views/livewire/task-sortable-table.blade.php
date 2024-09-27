<div class="shadow-xl rounded p-10 w-[40%] mx-auto flex flex-col gap-3">
    <form wire:submit.prevent="createTask" class="flex flex-col justify-between  gap-3 text-center mb-4">
        <p>Create Task</p>
        <input wire:model="newTaskName" type="text" placeholder="Task Name" class="w-full p-2 border rounded-md mb-2">
        @error('newTaskName') <span class="text-red-500">{{ $message }}</span> @enderror

        <button type="submit" class="bg-blue-500 px-4 py-2 rounded-md text-white">
            New task
        </button>
    </form>

    <p>Filter by project ID</p>
    <select wire:model="filteredProjectId" wire:change="$refresh" name="projects" id="projects">
        <option value="">All projects</option>

        @foreach($projects as $project)
        <option value="{{$project->id}}">{{$project->name}}</option>
        @endforeach
    </select>

    <ul wire:sortable="updateTaskOrder">
        @foreach ($tasks as $task)
        <li wire:sortable.item="{{ $task->id }}" wire:key="task-{{ $task->id }}"
            class="flex justify-between items-center">
            <div class="my-4 w-full p-2 flex flex-col justify-center items-center gap-5 bg-slate-200 rounded-md">
                @if($editingTaskId === $task->id)
                <input wire:model="taskName.{{$task->id}}" type="text" value="{{$task->name}}"
                    class="w-full p-2 border rounded-md" />
                <button class=" bg-green-500 hover:bg-green-400 active:bg-green-300 px-4 py-2 rounded-md text-white"
                    wire:click="updateTask({{ $task->id }})">
                    Save
                </button>
                @else
                <span wire:sortable.handle class="flex cursor-pointer" wire:click="editTaskName({{ $task->id }})">
                    {{ $task->name }}
                </span>
                @endif

                <select wire:model="selectedProjectId.{{$task->id}}" wire:change="addProjectId({{$task->id}})"
                    name="projects" id="projects" class="rounded-md">
                    @if($task->project_id)
                    <option value="{{$task->project->id}}">{{$task->project->name}}</option>
                    @else
                    <option value="">Assign a project</option>
                    @endif

                    @foreach($projects as $project)
                    <option value="{{$project->id}}">{{$project->name}}</option>
                    @endforeach
                </select>

                <button wire:click="deleteTask({{$task->id}})"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Remove
                </button>
            </div>
        </li>
        <hr>
        @endforeach
    </ul>

    <style>
        .draggable-mirror {
            width: 14%;
        }
    </style>
</div>
