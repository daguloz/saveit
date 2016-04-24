<!-- resources/views/links.blade.php -->

@extends('layouts.app')

@section('content')

    <!-- Bootstrap Boilerplate... -->

    <div class="panel-body">
        <!-- Display Validation Errors -->
        @include('common.errors')

        <!-- New Link Form -->
        <form action="{{ url('link') }}" method="POST" class="form-horizontal">
            {!! csrf_field() !!}

            <!-- Link Name -->
            <div class="form-group">
                <label for="link-name" class="col-sm-3 control-label">Link</label>

                <div class="col-sm-6">
                    <input type="text" name="name" id="link-name" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="link-url" class="col-sm-3 control-label">URL</label>
                <div class="col-sm-6">
                    <input type="text" name="url" id="link-url" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="link-description" class="col-sm-3 control-label">Description</label>
                <div class="col-sm-6">
                    <input type="text" name="description" id="link-description" class="form-control">
                </div>
            </div>

            <!-- Add Link Button -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-default">
                        <i class="fa fa-plus"></i> Add Link
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Current Links -->
    @if (count($links) > 0)
        <div class="panel panel-default">
            <div class="panel-heading">
                Current Links
            </div>

            <div class="panel-body">
                <table class="table table-striped link-table">

                    <!-- Table Headings -->
                    <thead>
                        <th>Link</th>
                        <th>URL</th>
                        <th>Description</th>
                        <th>&nbsp;</th>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                        @foreach ($links as $link)
                            <tr>
                                <!-- Task Name -->
                                <td class="table-text">
                                    <div>{{ $link->name }}</div>
                                </td>
                                <td>
                                    <div><a href="{{ $link->url }}" target="_blank">{{ $link->url }}</a></div>
                                </td>
                                <td>
                                    <div>{{ $link->description }}</div>
                                </td>
                                <!-- Delete Button -->
                                <td>
                                    <form action="{{ url('link/'.$link->id) }}" method="POST">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}

                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection