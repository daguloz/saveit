<!-- resources/views/links.blade.php -->

@extends('layouts.app')

@section('content')

    <!-- Current Link -->
    @if (isset($link))
        <div class="panel panel-default">
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
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection