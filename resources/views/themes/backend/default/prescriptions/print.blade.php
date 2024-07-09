@extends('themes.backend.default.layouts.print')

@push('styles')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .table-bordered {
            border-collapse: collapse;
        }

        table.first {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.first {
            border: 1px solid black;
        }

        table.first th:first-child {
            padding: 5px;
            text-align: center;
            background-color: #f2f2f2;
            border: 0px;
        }

        table.first th:last-child {
            padding: 5px;
            text-align: right;
            background-color: #f2f2f2;
            border: 0px;
        }


    </style>
@endpush


@section('content')

    <table class="first">
        <thead>
            <tr>
                <th>{{ __('PRESCRIPTION') }}</th>
                <th>{{ Str::upper($prescription->created_at->format('d F Y')) }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <tbody>
        <tr style="height: 100px">
            <td>{{ __('Name Surname:') }}</td>
            <td>{{ $prescription->patient_name }}</td>
            <td>{{ __('Date') }}</td>
            <td>{{ Str::upper($prescription->created_at->format('d F Y')) }}</td>
        </tr>
        </tbody>
    </table>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>{{ __('Product Name') }}</th>
            <th>{{ __('Box') }}</th>
            <th>{{ __('Dosage') }}</th>
            <th>{{ __('Note') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($prescription->medicines as $medicine)
            <tr>
                <td>{{ $medicine->product->name }}</td>
                <td>{{ $medicine->box }}</td>
                <td>{{ $medicine->dosage }}</td>
                <td>{{ $medicine->note }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
