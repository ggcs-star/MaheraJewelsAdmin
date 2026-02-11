<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public function index(Request $request)
{
    $organizations = Organization::query()
        ->when($request->filled('search'), function ($q) use ($request) {
            $search = trim($request->search);

            $q->where(function ($qq) use ($search) {
                $qq->where('name', 'LIKE', "%{$search}%")
                   ->orWhere('email', 'LIKE', "%{$search}%")
                   ->orWhere('mobile', 'LIKE', "%{$search}%");
            });
        })

        ->when($request->filled('status'), function ($q) use ($request) {
            $q->where('is_active', $request->status);
        })

     
        ->when(
            $request->filled('adv_field') &&
            $request->filled('adv_condition') &&
            $request->filled('adv_value'),
            function ($q) use ($request) {

        
                $allowedFields = [
                    'name',
                    'email',
                    'mobile',
                    'city',
                    'address',
                ];

                $field = $request->adv_field;
                $cond  = $request->adv_condition;
                $value = trim($request->adv_value);

                if (!in_array($field, $allowedFields) || $value === '') {
                    return;
                }

                switch ($cond) {
                    case 'like':
                        $q->where($field, 'LIKE', "%{$value}%");
                        break;

                    case 'starts_with':
                        $q->where($field, 'LIKE', "{$value}%");
                        break;

                    case 'ends_with':
                        $q->where($field, 'LIKE', "%{$value}");
                        break;

                    case '=':
                        $q->where($field, '=', $value);
                        break;

                    case '!=':
                        $q->where($field, '!=', $value);
                        break;
                }
            }
        )
        ->orderBy('name')
        ->paginate(10)
        ->appends($request->query());

    return view('organizations.index', compact('organizations'));
}

    public function create()
    {
        return view('organizations.create');
    }
public function show(Organization $organization)
{
    return view('organizations.show', compact('organization'));
}
    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $this->uploadLogo($request, $data['name']);
        }

        Organization::create($data);

        return redirect()
            ->to(admin_route('organizations.index'))
            ->with('success', 'Organization created successfully.');
    }

    public function edit(Organization $organization)
    {
        return view('organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $data = $this->validated($request);

        if ($request->hasFile('logo')) {
            $this->deleteLogo($organization->logo_path);
            $data['logo_path'] = $this->uploadLogo($request, $data['name']);
        }

        $organization->update($data);

        return redirect()
            ->to(admin_route('organizations.index'))
            ->with('success', 'Organization updated successfully.');
    }
    public function destroy(Organization $organization)
    {
        $this->deleteLogo($organization->logo_path);
        $organization->delete();

        return back()->with('success', 'Organization deleted.');
    }
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids ?? [];

        if (!$ids) {
            return back()->with('error', 'No organizations selected.');
        }

        $orgs = Organization::whereIn('id', $ids)->get();

        foreach ($orgs as $org) {
            $this->deleteLogo($org->logo_path);
            $org->delete();
        }

        return redirect()
            ->to(admin_route('organizations.index'))
            ->with('success', 'Selected organizations deleted.');
    }
    private function validated(Request $request): array
    {
        return $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
            'mobile'    => 'nullable|string|max:20',
            'website'   => 'nullable|url|max:255',

            'logo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'address'   => 'nullable|string',
            'city'      => 'nullable|string|max:100',
            'state'     => 'nullable|string|max:100',
            'country'   => 'nullable|string|max:100',
            'pincode'   => 'nullable|string|max:20',

            'is_active' => 'required|boolean',
        ]);
    }
    private function uploadLogo(Request $request, string $name): ?string
    {
        $folder = Str::slug($name);
        $path   = "admin/organization/{$folder}/logo";

        return $request->file('logo')->storeAs(
            $path,
            'logo.' . $request->file('logo')->getClientOriginalExtension(),
            's3'
        );
    }

    private function deleteLogo(?string $path): void
    {
        if ($path && Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->delete($path);
        }
    }
}
