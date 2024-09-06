<?php

namespace App\Http\Responses;

class Message
{
    const Error                 = "Something went wrong. Please try again later.";
    const SOMETHING_WENT_WRONG  = "Something went wrong. Please try again later.";
    const NO_PRODUCT_UPLOADED   = "No Product found.";
    const NO_JOBS               = "No Recruitment With given details.";
    const NO_JOBS_YET           = "No recruitment yet.";
    const NO_EMPLOYEES          = "No Employees With given details.";
    const NO_EMPLOYEES_YET      = "No Employees yet.";
    const NO_LEAVES             = "No Leave is applied With given details.";
    const NO_LEAVES_YET         = "No Leave is applied yet.";
    const NO_TIME_SHIFT         = "No Time shift With given details.";
    const NO_TIME_SHIFT_YET     = "No Time shift is set yet.";
    const NO_TASK               = "No Task With given details.";
    const NO_TASK_YET           = "No Task created yet.";
    const NO_LEVEL              = "No Level With given details.";
    const NO_LEVEL_YET          = "No level created yet.";
    const NO_DEPARTMENT         = "No Departments With given details.";
    const NO_DEPARTMENT_YET     = "No Department created yet.";
    const NO_SIGN_DOCUMENT      = "No Sign document With given details.";
    const NO_SIGN_DOCUMENT_YET  = "No Sign document created yet.";
    const NO_BILL               = "No Bill With given details.";
    const NO_BILL_YET           = "No Bill created yet.";
    const NOT_ENOUGH_PRODUCTS   = "Not enough products to purchase.";
    const PRODUCT_NOT_FOUND     = "Something went wrong!, Product not found.";
    const INVALID_TOKEN         = "Something went wrong!, Product not found.";
    const UNABLE_TO_UPLOAD_FILE = "Something went wrong!, unable to unload file please try again.";
    const NO_SIGN_DOCUMENT_OR_FILE_NOT_FOUND = "Something went wrong!, Document path not found or upload file is not in proper format.";
    const FILE_NOT_FOUND        = "Something went wrong!, Upload file is not in proper format.";
    const RECORD_DELETED = "Record Deleted Successfully.";
    const ORGANIZATION_NOT_DELETED = "Organization Not deleted";
    const INVALID_ORGANIZATION_ID = "Invalid Organization ID. Please check Organization ID again.";
    const NO_PROOF_OF_WORK      = "No Proof of work With given details.";
    const NO_PROOF_OF_WORK_YET  = "No Proof of work created yet.";
}