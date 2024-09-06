<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProofOfWorkStoreRequest;
use App\Http\Responses\{
    Message,
    ResponseCode
};
use App\Models\ProofOfWork;
use Illuminate\Support\Facades\Storage;

class ProofOfWorkController extends Controller
{
    public function index()
    {
        try
        {
            $proofOfWork = ProofOfWork::where('user_id', auth()->id())->with("Employee")->get();
            if(count($proofOfWork) == 0)
                return makeResponse(ResponseCode::SUCCESS, Message::NO_PROOF_OF_WORK_YET, [], ResponseCode::SUCCESS);
            else
                return makeResponse(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $proofOfWork, ResponseCode::SUCCESS);
        }
        catch (\Exception $e) {
            return makeResponse(ResponseCode::UNEXPECTED_ERROR, ResponseCode::getMessage(ResponseCode::UNEXPECTED_ERROR), [], ResponseCode::UNEXPECTED_ERROR, $e->getMessage());
        }
    }

    public function show($id)
    {
        try
        {
            $proofOfWork = ProofOfWork::where('user_id', auth()->id())->with("Employee")->find($id);
            if(!$proofOfWork)
                return makeResponse(ResponseCode::FAIL, Message::NO_PROOF_OF_WORK, [], ResponseCode::FAIL);
            else
                return makeResponse(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $proofOfWork, ResponseCode::SUCCESS);
        }
        catch (\Exception $e) {
            return makeResponse(ResponseCode::UNEXPECTED_ERROR, ResponseCode::getMessage(ResponseCode::UNEXPECTED_ERROR), [], ResponseCode::UNEXPECTED_ERROR, $e->getMessage());
        }
    }

    public function store(ProofOfWorkStoreRequest $request) {
        try {
            $path = "";
            if(!$request->id)
            {
                if ($request->hasFile('document')) {
                    $file = $request->file('document');
                    $filename = time() . '_' . $file->getClientOriginalName();
            
                    // Save the file to the 'public' disk
                    $path = $file->storeAs('document/uploads/'.auth()->id() , $filename, 'public');
                }
                else
                    return makeResponse(ResponseCode::FAIL, Message::FILE_NOT_FOUND, [], ResponseCode::FAIL);

                $proofOfWork = ProofOfWork::create([
                    'document'      => $path,
                    'pow_id'        => $request['pow_id'],
                    'emp_id'        => $request['emp_id'],
                    'status'        => 1,
                    'expiry_date'   => $request['expiry_date'],
                    'user_id'       => auth()->id() ?? 1
                ]);
                if($proofOfWork)
                    return makeResponse(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $proofOfWork, true);
                else
                    return makeResponse(ResponseCode::FAIL, ResponseCode::getMessage(ResponseCode::FAIL), [], ResponseCode::FAIL);
            }
            else {
                $filePath = ProofOfWork::find($request->id);
                $proofOfWork = 0;
                if ($request->hasFile('document')) {
                    $file = $request->file('document');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('document/uploads/'.auth()->id(), $filename, 'public');

                    if (!Storage::disk('public')->delete($filePath['document']))
                        return makeResponse(ResponseCode::FAIL, Message::UNABLE_TO_UPLOAD_FILE, [], ResponseCode::FAIL);
                }
                else
                    $path = $filePath['document'];
                    // return makeResponse(ResponseCode::FAIL, Message::NO_SIGN_DOCUMENT_OR_FILE_NOT_FOUND, [], ResponseCode::FAIL);

                $proofOfWork = ProofOfWork::where('id', $request->id)->update([
                    'document'      => $path,
                    'pow_id'        => $request['pow_id'],
                    'emp_id'        => $request['emp_id'],
                    'status'        => 1,
                    'expiry_date'   => $request['expiry_date'],
                ]);
                if($proofOfWork)
                    return makeResponse(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $proofOfWork, true);
                else
                    return makeResponse(ResponseCode::FAIL, ResponseCode::getMessage(ResponseCode::FAIL), [], ResponseCode::FAIL);
            }
        } 
        catch (\Throwable $th) {
            return makeResponse(ResponseCode::UNEXPECTED_ERROR, ResponseCode::getMessage(ResponseCode::UNEXPECTED_ERROR), [], ResponseCode::UNEXPECTED_ERROR, $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try
        {
            $proofOfWork = ProofOfWork::where('user_id', auth()->id())->find($id);
            if(!$proofOfWork)
                return makeResponse(ResponseCode::FAIL, Message::NO_PROOF_OF_WORK, [], ResponseCode::FAIL);
            else{
                Storage::disk('public')->delete($proofOfWork['document']);
                $proofOfWork->delete();
                return makeResponse(ResponseCode::SUCCESS, Message::RECORD_DELETED, $proofOfWork, ResponseCode::SUCCESS);
            }
        }
        catch (\Exception $e) {
            return makeResponse(ResponseCode::UNEXPECTED_ERROR, ResponseCode::getMessage(ResponseCode::UNEXPECTED_ERROR), [], ResponseCode::UNEXPECTED_ERROR, $e->getMessage());
        }
    }
}