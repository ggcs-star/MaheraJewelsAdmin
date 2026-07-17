<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\S3Helper;

use App\Models\OrganizationNotificationEmail;
use Illuminate\Support\Facades\Mail;
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
    $organization->load('notificationEmails');

    return view('organizations.show', compact('organization'));
}
    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $this->uploadLogo($request, $data['name']);
        }
         if ($request->hasFile('invoice_logo')) {
        $data['invoice_logo'] = $this->uploadInvoiceLogo($request, $data['name']);
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
         if ($request->hasFile('invoice_logo')) {
        $this->deleteLogo($organization->invoice_logo);
        $data['invoice_logo'] = $this->uploadInvoiceLogo($request, $data['name']);
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
            'invoice_name' => 'nullable|string|max:255',
            'email'     => 'nullable|email|max:255',
            'mobile'    => 'nullable|string|max:20',
            'website'   => 'nullable|url|max:255',
            'invoice_email' => 'nullable|email|max:255', 
             'invoice_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'logo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'business_hours' => 'nullable|string|max:255',
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

        return S3Helper::storeAs(
            $request->file('logo'),
                $path,
                'logo.' . $request->file('logo')->getClientOriginalExtension()
            );
    }
    public function notificationSettings()
{
    $organizations = Organization::with('notificationEmails')->get();

    return view('admin.notification-settings.index', compact('organizations'));
}

public function storeNotificationEmail(Request $request, Organization $organization)
{
    $request->validate([
        'name' => 'nullable|string|max:255',
        'email' => 'required|email|max:255',
    ]);

    $organization->notificationEmails()->create([
        'name' => $request->name,
        'email' => $request->email,
        'receive_order_notification' => $request->boolean('receive_order_notification'),
        'receive_registration_notification' => $request->boolean('receive_registration_notification'),
        'receive_contact_notification' => $request->boolean('receive_contact_notification'),
        'is_active' => $request->boolean('is_active', true),
    ]);

    return back()->with('success', 'Notification email added successfully.');
}

public function destroyNotificationEmail($id)
{
    OrganizationNotificationEmail::findOrFail($id)->delete();

    return back()->with('success', 'Notification email deleted successfully.');
}

    private function deleteLogo(?string $path): void
    {
        if ($path) {
            S3Helper::delete($path);
        }
    }
    // ✅ Invoice Logo Upload Method
private function uploadInvoiceLogo(Request $request, string $name): ?string
{
    $folder = Str::slug($name);
    $path   = "admin/organization/{$folder}/invoice-logo";

    return S3Helper::storeAs(
        $request->file('invoice_logo'),
        $path,
        'invoice-logo.' . $request->file('invoice_logo')->getClientOriginalExtension()
    );
}

}
