<div class="btn-group" role="group">
    <a href="{{ $showUrl }}" class="btn btn-info btn-sm" title="View">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ $editUrl }}" class="btn btn-warning btn-sm" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <a href="{{ $repayUrl }}" class="btn btn-success btn-sm" title="Repay">
        <i class="fas fa-dollar-sign"></i>
    </a>
    <button type="button" class="btn btn-danger btn-sm delete-btn" onclick="deleteData({{ $row->id }})"
        title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</div>
