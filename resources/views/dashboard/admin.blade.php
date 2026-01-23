<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Admin Dashboard</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger">Logout</button>
            </form>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="{{ admin_route('suppliers.index') }}" class="list-group-item list-group-item-action">
                                <h6 class="mb-1">📦 Manage Suppliers</h6>
                                <p class="mb-1 text-muted">View, add, edit, and delete suppliers</p>
                                <small>Route: /admin/suppliers</small>
                            </a>
                            <a href="{{ admin_route('categories.index') }}" class="list-group-item list-group-item-action">
                                <h6 class="mb-1">📁 Manage Categories</h6>
                                <p class="mb-1 text-muted">View, add, edit, and delete categories</p>
                                <small>Route: /admin/categories</small>
                            </a>
                            <a href="{{ admin_route('dashboard') }}" class="list-group-item list-group-item-action">
                                <h6 class="mb-1">🏠 Dashboard</h6>
                                <p class="mb-1 text-muted">Return to admin dashboard</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Available Routes</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Route Name</th>
                                    <th>URL</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>admin.dashboard</code></td>
                                    <td><code>/admin/dashboard</code></td>
                                    <td>Admin Dashboard</td>
                                </tr>
                                <tr>
                                    <td><code>admin.suppliers.index</code></td>
                                    <td><code>/admin/suppliers</code></td>
                                    <td>List all suppliers</td>
                                </tr>
                                <tr>
                                    <td><code>admin.suppliers.create</code></td>
                                    <td><code>/admin/suppliers/create</code></td>
                                    <td>Add new supplier</td>
                                </tr>
                                <tr>
                                    <td><code>admin.suppliers.edit</code></td>
                                    <td><code>/admin/suppliers/{id}/edit</code></td>
                                    <td>Edit supplier</td>
                                </tr>
                                <tr>
                                    <td><code>admin.categories.index</code></td>
                                    <td><code>/admin/categories</code></td>
                                    <td>List all categories</td>
                                </tr>
                                <tr>
                                    <td><code>admin.categories.create</code></td>
                                    <td><code>/admin/categories/create</code></td>
                                    <td>Add new category</td>
                                </tr>
                                <tr>
                                    <td><code>admin.categories.edit</code></td>
                                    <td><code>/admin/categories/{id}/edit</code></td>
                                    <td>Edit category</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
