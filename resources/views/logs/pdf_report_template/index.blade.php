<!DOCTYPE html>
<html>
<head>
    <title>PDF Logs</title>
<link href="{{ asset('css/merge.css') }}" rel="stylesheet">
<style type="text/css">
body{
    font-family: arial, sans-serif;
}
table {
    border-collapse: collapse;
    width: 100%;
}
th{
    align-content: center;
    background-color:#18d3af;
    color:white;
}
td,th{
    font-size: 12px;
    border: 1px solid black;
    text-align: left;
    padding: 1px 1px 1px 3px;
}
.page-num:before{
    content:counter(page);
}
.float-right{
    float: right;
}
.float-left{
    float: left;
}
.h-50{
    height: 50px;
}
</style>
</head>
<body>
<div class="float-right">
    Date: {{$currentDate}}
</div>
@if(isset($systemLogs))
    <div class="float-left">
        System Logs
    </div>
    <div class="h-50">
    </div>
    <table class="tableStyle">
        <tr>
            <th align="center">ID</th>
            <th>Type</th>
            <th>Instruction Type</th>
            <th>Content</th>
            <th>IP</th>
            <th>Host</th>
            <th>Date</th>
        </tr>
        @foreach($systemLogs as $log)
        <tr>
            <td width="70px">{{$log->LOGS_ID}}</td>
            <td width="40px">{{$log->TYPE}}</td>
            <td width="70px">{{$log->INSTRUCTION_TYPE}}</td>
            <td>{{$log->CONTENT}}</td>
            <td width="90px">{{$log->IP}}</td>
            <td width="120px">{{$log->HOST}}</td>
            <td width="120px">{{$log->CREATED_AT}}</td>
        </tr>
        @endforeach
    </table>
@elseif(isset($auditLogs))
    <div class="float-left">
        Audit Logs
    </div>
    <div class="h-50">
    </div>
    <table class="tableStyle">
        <tr>
            <th align="center">ID</th>
            <th>IP</th>
            <th>Host</th>
            <th>Module</th>
            <th>Instruction</th>
            <th>Date</th>
        </tr>
        @foreach($auditLogs as $log)
        <tr>
            <td width="55px">{{$log->AUDIT_LOGS_ID}}</td>
            <td width="90px">{{$log->IP}}</td>
            <td width="90px">{{$log->HOST}}</td>
            <td width="100px">{{$log->MODULE}}</td>
            <td width="120px">{{$log->INSTRUCTION}}</td>
            <td width="120px">{{$log->CREATED_AT}}</td>
        </tr>
        @endforeach
    </table>
@endif
</body>
</html>