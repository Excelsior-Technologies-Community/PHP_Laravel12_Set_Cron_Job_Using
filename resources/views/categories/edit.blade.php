@extends('layouts.app')

@section('content')

<div class="container mt-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Edit Category</h2>
            <p class="text-muted mb-0">Update category information</p>
        </div>

        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
            ← Back
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Please fix the following errors:</strong>

        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Edit Form --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form method="POST"
                action="{{ route('categories.update', $category->id) }}">

                @csrf
                @method('PUT')

                {{-- Category Name --}}
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">
                        Category Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $category->name) }}"
                        placeholder="Enter category name"
                        required>

                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">
                        Status
                    </label>

                    <select
                        name="status"
                        id="status"
                        class="form-select @error('status') is-invalid @enderror"
                        required>
                        <option value="active"
                            {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>

                    @error('status')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-primary">
                        Update Category
                    </button>

                    <a href="{{ route('categories.index') }}"
                        class="btn btn-outline-secondary">
                        Cancel
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection