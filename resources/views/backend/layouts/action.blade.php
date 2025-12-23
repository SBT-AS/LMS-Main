<div class="flex items-center justify-center gap-1">
    @php
        $module2 = $module2 ?? $module;
    @endphp

    @can($module . '.view')
    <a href="{{ route($module2.'.show', $data->id) }}"
       class="group relative p-2 rounded-lg bg-indigo-50 hover:bg-indigo-600 transition"
       title="View">
        <i class="bi bi-eye text-indigo-600 group-hover:text-white text-sm"></i>
    </a>
    @endcan

    @if($module == 'roles')
        @can($module . '.permissions')
        <a href="{{ route('admin.roles.permissions', $data->id) }}"
           class="group relative p-2 rounded-lg bg-purple-50 hover:bg-purple-600 transition"
           title="Permissions">
            <i class="bi bi-shield-lock text-purple-600 group-hover:text-white text-sm"></i>
        </a>
        @endcan
    @endif
    
    @if($module == 'courses')
        <a href="{{ route('admin.courses.quizzes.index', $data->id) }}"
           class="group relative p-2 rounded-lg bg-yellow-50 hover:bg-yellow-600 transition"
           title="Manage Quizzes">
            <i class="bi bi-patch-question text-yellow-600 group-hover:text-white text-sm"></i>
        </a>
    @endif

    @can($module . '.edit')
    <a href="{{ route($module2.'.edit', $data->id) }}"
       class="group relative p-2 rounded-lg bg-emerald-50 hover:bg-emerald-600 transition"
       title="Edit">
        <i class="bi bi-pencil-square text-emerald-600 group-hover:text-white text-sm"></i>
    </a>
    @endcan

    @can($module . '.delete')
    <button type="button"
            class="group relative p-2 rounded-lg bg-rose-50 hover:bg-rose-600 transition delete-btn"
            data-url="{{ route($module2.'.destroy', $data->id) }}"
            data-table="{{ $module }}-table"
            title="Delete">
        <i class="bi bi-trash text-rose-600 group-hover:text-white text-sm"></i>
    </button>
    @endcan
</div>
