<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CategoryController extends Controller
{
    /**
     * Display category listing.
     */
    public function index(Request $request)
    {
        $query = Category::query();

        /*
        |--------------------------------------------------------------------------
        | Show Trash
        |--------------------------------------------------------------------------
        */

        if ($request->get('view') === 'trash') {
            $query = Category::onlyTrashed();
        }

        /*
        |--------------------------------------------------------------------------
        | Search by name
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $query->where(
                'name',
                'like',
                '%' . $request->search . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('status') &&
            $request->get('view') !== 'trash'
        ) {
            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->to_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $sortable = [
            'id',
            'name',
            'status',
            'created_at',
        ];

        $sortColumn = in_array(
            $request->sort,
            $sortable
        )
            ? $request->sort
            : 'id';

        $sortDirection = $request->direction === 'desc'
            ? 'desc'
            : 'asc';

        $query->orderBy(
            $sortColumn,
            $sortDirection
        );

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $categories = $query
            ->paginate(5)
            ->appends($request->all());

        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        $totalCategories = Category::count();

        $activeCategories = Category::where(
            'status',
            'active'
        )->count();

        $inactiveCategories = Category::where(
            'status',
            'inactive'
        )->count();

        $trashedCategories = Category::onlyTrashed()->count();

        return view(
            'categories.index',
            compact(
                'categories',
                'totalCategories',
                'activeCategories',
                'inactiveCategories',
                'trashedCategories'
            )
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],
        ]);

        Category::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('categories.index')
            ->with(
                'success',
                'Category added successfully.'
            );
    }

    /**
     * Display category.
     */
    public function show(Category $category)
    {
        return view(
            'categories.show',
            compact('category')
        );
    }

    /**
     * Edit category.
     */
    public function edit(Category $category)
    {
        return view(
            'categories.edit',
            compact('category')
        );
    }

    /**
     * Update category.
     */
    public function update(
        Request $request,
        Category $category
    ) {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name,' . $category->id,
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],
        ]);

        $category->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('categories.index')
            ->with(
                'success',
                'Category updated successfully.'
            );
    }

    /**
     * Soft delete category.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with(
                'success',
                'Category moved to trash successfully.'
            );
    }

    /**
     * Bulk soft delete.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'category_ids' => [
                'required',
                'array',
            ],

            'category_ids.*' => [
                'exists:categories,id',
            ],
        ]);

        $deletedCount = Category::whereIn(
            'id',
            $request->category_ids
        )->delete();

        return redirect()
            ->route('categories.index')
            ->with(
                'success',
                "{$deletedCount} categories moved to trash."
            );
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(Category $category)
    {
        $category->update([
            'status' => $category->status === 'active'
                ? 'inactive'
                : 'active',
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Category status updated successfully.'
            );
    }

    /**
     * Restore category from trash.
     */
    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);

        $category->restore();

        return redirect()
            ->route(
                'categories.index',
                ['view' => 'trash']
            )
            ->with(
                'success',
                'Category restored successfully.'
            );
    }

    /**
     * Permanently delete category.
     */
    public function forceDestroy($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);

        $category->forceDelete();

        return redirect()
            ->route(
                'categories.index',
                ['view' => 'trash']
            )
            ->with(
                'success',
                'Category permanently deleted.'
            );
    }

    /**
     * Export categories to CSV.
     */
    public function exportCsv(Request $request)
    {
        $query = Category::query();

        if ($request->filled('search')) {
            $query->where(
                'name',
                'like',
                '%' . $request->search . '%'
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('from_date')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->to_date
            );
        }

        $categories = $query
            ->orderBy('id', 'asc')
            ->get();

        $fileName =
            'categories_' .
            now()->format('Y_m_d_H_i_s') .
            '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' =>
                'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($categories) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID',
                'Name',
                'Status',
                'Created At',
            ]);

            foreach ($categories as $category) {
                fputcsv($file, [
                    $category->id,
                    $category->name,
                    ucfirst($category->status),
                    optional($category->created_at)
                        ->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream(
            $callback,
            200,
            $headers
        );
    }
}