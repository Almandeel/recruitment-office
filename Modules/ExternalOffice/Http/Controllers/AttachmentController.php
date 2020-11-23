<?php

namespace Modules\ExternalOffice\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Attachment;

class AttachmentController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:attachments-create')->only(['create', 'store']);
        $this->middleware('permission:attachments-read')->only(['index', 'show']);
        $this->middleware('permission:attachments-update')->only(['edit', 'update']);
        $this->middleware('permission:attachments-delete')->only('destroy');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'attachable_id' => 'required|numeric',
            'attachable_type' => 'required|string',
        ]);
        $data = $request->except(['_token']);
        if (auth()->guard('office')->check()) {
            $data['office'] = Attachment::OFFICE_EXTERNAL;
        }
        if ($request->name) {
            $data['name'] = $request->name;
        }

        if ($request->file) {
            $file = $request->file('file');
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $newFileName = Str::random(16) . date('Ymdhis') . '.' . $extension;
            $file->move(public_path("/attachments_files"), $newFileName);
            $data['file'] = $newFileName;
        }
        if (count($data)) {
            $attachment = Attachment::create($data);
            return back()->with('success', __('accounting::global.create_success'));
        }

        return back()->with('error', __('accounting::global.create_fail'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Attachment $attachment
     * @return \Illuminate\Http\Response
     */
    public function show(Attachment $attachment)
    {
        // $stream = $stream === true ? 'attachment' : 'inline';
        // response()->header('Content-type', $attachment->getFile()->getMimeType());
        // response()->header('Content-Disposition',  'filename=' . $attachment->getFile()->getFileName() . ';');
        // response()->header('Content-Length',  $attachment->getFile()->getSize());

        return response()->file($attachment->getFilePath());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Attachment $attachment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attachment $attachment)
    {
        $request->validate([
            'name' => 'string',
            'attachable_id' => 'numeric',
            'attachable_type' => 'string',
        ]);
        $data = [];
        if ($request->name) {
            $data['name'] = $request->name;
        }

        if ($request->file) {
            $file = $request->file('file');
            $attachment->deleteFile();
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $newFileName = Str::random(16) . date('Ymdhis') . '.' . $extension;
            $file->move(public_path("/attachments_files"), $newFileName);
            $data['file'] = $newFileName;
        }

        if (count($data)) {
            $attachment->update($data);
            return back()->with('success', __('accounting::global.update_success'));
        }

        return back()->with('error', __('accounting::global.update_fail'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Attachment $attachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attachment $attachment)
    {
        $attachment->delete();
        return back()->with('success', __('accounting::attachments.delete_success'));
    }
}
