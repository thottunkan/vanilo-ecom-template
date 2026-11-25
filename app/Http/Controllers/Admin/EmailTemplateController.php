<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Utils\TemplateParser;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Str;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $email_templates = EmailTemplate::all();
        return view('admin.email.emailtemplate.index', compact('email_templates'));
    }

    public function create()
    {
        return view('admin.email.emailtemplate.create');
    }

    public function edit($id)
    {
        $email_template = EmailTemplate::find($id);
        return view('admin.email.emailtemplate.edit', compact('email_template'));
    }


    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:email_templates,name',
                'subject' => 'required|string|max:255',
                'body' => 'required|string',
            ]);

            EmailTemplate::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'subject' => $validated['subject'],
                'body' => $validated['body'],
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Template created successfully',
            ], 201);

        } catch (ValidationException $e) {

            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $template = EmailTemplate::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:email_templates,name,' . $template->id,
                'subject' => 'required|string|max:255',
                'body' => 'required|string',
            ]);

            $template->update([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'subject' => $validated['subject'],
                'body' => $validated['body'],
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Template updated successfully',
            ], 200);

        } catch (ValidationException $e) {

            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ], 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'status' => false,
                'message' => 'Template not found.',
            ], 404);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    public function delete($id)
    {
        $template = EmailTemplate::findOrFail($id);
        $template->delete();
        return redirect()->route('admin.email-template.index');
    }

    public function preview($id)
    {
        $template = EmailTemplate::findOrFail($id);

        $dummy = [
            'customer_name' => 'John Doe',
            'website_name' => config('app.name'),
            'verification_url' => '#',
            'reset_url' => '#',
            'order_id' => 'ORD-1001',
            'order_date' => now()->format('d M Y'),
            'invoice_url' => '#',
            'tracking_url' => '#',
            'support_email' => 'support@example.com',
            'payment_method' => 'Credit Card',
            'amount' => '$250',
            'refund_amount' => '$50',
        ];

        $subject = TemplateParser::parse(
            $template->subject,
            $dummy
        );

        $body = TemplateParser::parse(
            $template->body,
            $dummy
        );

        return view(
            'admin.email-templates.preview',
            compact(
                'subject',
                'body'
            )
        );
    }

    /**
     * Enable / Disable Template
     */
    public function toggle($id)
    {
        $template = EmailTemplate::findOrFail($id);

        $template->status = !$template->status;

        $template->save();

        return back()->with(
            'success',
            'Status Updated Successfully.'
        );
    }

}
