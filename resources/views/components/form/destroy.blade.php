<form action="{{ route($path, $deletingId) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn-common btn-danger"
    {{ $disabled ?? false ? ' disabled' :'' }}
    >Delete</button>
</form>
