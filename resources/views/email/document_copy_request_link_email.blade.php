<h4>Hi {{$request->requestor_info->name}},</h4>

<p>
    Please be notified that your <strong> Documentation Copy Request </strong> has been <strong>{{$request->status}}</strong> with Remarks : {{$request->status_remarks}}.

    To view your Copy, Just click <a href="http://e-dms-v2.local/copy-request-view-document/{{$request->id}}">here</a>.
</p>

<p>
    Thank you,
</p>
<p>
    E-DMS
</p>
<p>
    ************ This is a system generated email. Please do not reply. ************
</p>
