@extends('themes.backend.default.layouts.app')

@section('pre-content')
    @php
        $data = [
            [
                'title' => __('Requests'),
                'url' => route('admin.requests.index')
            ],
            [
                'title' => __('Index'),
                'url' => ''
            ]
        ];
    @endphp
    <x-backend.breadcrumbs title="{{ __('Requests') }}" :links="$data" />
@endsection


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Repo</th>
                            <th scope="col">User</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Updated At</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests as $request)
                                <tr>
                                    <th scope="row">{{ $request->id }}</th>
                                    <td>{{ $request->repo->name ?? ''}}</td>
                                    <td>{{ $request->user->name }}</td>
                                    <td>
                                        @if ($request->status == 'pending')
                                            <span>{{ ucfirst($request->status) }}</span>
                                        @elseif ($request->status == 'approved')
                                            <span>{{ ucfirst($request->status) }}</span>
                                        @else
                                            <span>{{ ucfirst($request->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $request->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $request->updated_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.requests.show', $request->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" id="{{ $request->id }}" class="btn btn-warning btn-sm edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <td colspan="3">
                                <span class="text-danger">
                                    <strong>No Request Found!</strong>
                                </span>
                                </td>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('Edit Request Status') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label for="statusx" class="form-label">{{ __('Status') }}</label>
                            <select name="status" id="statusx" class="form-select">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="mb-3" style="display: none">
                            <label for="reason" class="form-label">{{ __('Reason') }}</label>
                            <textarea name="rejected_reason" id="reason" class="form-control" rows="3"></textarea>
                        </div>
                        <input type="hidden" name="id" id="requestId" value="" />
                    </form>
                    <div id="result" class="alert" style="display: none">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary save">{{ __('Save') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('.edit').on('click', function() {
                var id = $(this).attr('id');
                $('#staticBackdrop').modal('show');
                $('#requestId').val(id);
            });

            $('#statusx').on('change', function() {
                var status = $(this).val();

                if (status === 'rejected') {
                    $('#reason').parent().show();
                } else {
                    $('#reason').parent().hide();
                }
            });

            $('.save').on('click', function() {
                var form = $('#staticBackdrop form');

                $.post('{{ route('admin.requests.status-update') }}', form.serialize(), function(response) {
                    if ( response.status === 'success' ) {
                        $('#result').removeClass('alert-danger').addClass('alert-success').html(response.message).show();
                    } else {
                        $('#result').removeClass('alert-success').addClass('alert-danger').html(response.message).show();
                    }

                    setTimeout(() => {
                        $('#result').hide();
                        $('#staticBackdrop').modal('hide');
                        window.location.reload();
                    }, 2000);
                });
            });

        });
    </script>
@endpush
