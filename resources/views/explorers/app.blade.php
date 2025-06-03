@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col d-flex justify-content-between align-items-center">
            <h2 class="h3 mb-0">Explorer List</h2>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary d-flex align-items-center gap-2 ms-2">
                Back to Dashboard
            </a>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <input type="text" id="explorerSearch" class="form-control" placeholder="Search explorers...">
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody id="explorerTableBody">
                        @foreach($explorers as $explorer)
                        <tr>
                            <td>{{ $explorer->first_name }} {{ $explorer->last_name }}</td>
                            <td>{{ $explorer->email }}</td>
                            <td>{{ $explorer->phone }}</td>
                            <td>{{ $explorer->address }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="pagination d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination">
                        <!-- Previous Page Link -->
                        @if ($customers->onFirstPage())
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $customers->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        @endif

                        <!-- Pagination Elements -->
                            @for ($i = 1; $i <= $explorers->lastPage(); $i++)
                                <li class="page-item {{ ($explorers->currentPage() == $i) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $explorers->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            <!-- Next Page Link -->
                            @if ($explorers->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $explorers->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            @else
                            <li class="page-item disabled">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#customerSearch').on('keyup', function(e) {
           // if (e.which === 13) { // Enter key
                var searchTerm = $(this).val();

                if(searchTerm.length > 0) {
                    $.ajax({
                        url: '{{ route("explorers.search") }}',
                        type: 'GET',
                        data: { term: searchTerm },
                        success: function(response) {
                            var tableBody = $('#explorerTableBody');
                            tableBody.empty();

                            // Get the pagination container
                            var paginationContainer = $('.pagination');

                            if(response.customers.length > 0) {
                                // Populate table with search results
                                $.each(response.explorers, function(index, explorer) {
                                    var row = `<tr>
                                        <td>${explorer.first_name} ${explorer.last_name}</td>
                                        <td>${explorer.email}</td>
                                        <td>${explorer.phone}</td>
                                        <td>${explorer.address}</td>
                                        </tr>`;
                                    tableBody.append(row);
                                });

                        // Update pagination with links from response
                                updatePagination(response.pagination);
                            } else {
                                tableBody.append('<tr><td colspan="5" class="text-center">No customers found</td></tr>');
                                // Clear pagination if no results
                                paginationContainer.empty();
                            }
                        },
                        error: function(xhr) {
                            console.error('Error searching explorers:', xhr);
                        }
                    });
                } else {
                    // If search field is empty, reload the original data with pagination
                    window.location.href = '{{ route("explorers.index") }}';
                }
           // }
    });

    // Function to update pagination based on response data
    function updatePagination(pagination) {
        var paginationContainer = $('.pagination');
        paginationContainer.empty();

        // Create pagination element
        var paginationHtml = '<nav><ul class="pagination">';

        // Previous page link
        if (pagination.current_page > 1) {
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" data-page="${pagination.current_page - 1}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>`;
        } else {
            paginationHtml += `<li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>`;
        }

        // Page links
        for (var i = 1; i <= pagination.last_page; i++) {
            if (i === pagination.current_page) {
                paginationHtml += `<li class="page-item active"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            } else {
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }

        // Next page link
        if (pagination.current_page < pagination.last_page) {
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" data-page="${pagination.current_page + 1}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>`;
        } else {
            paginationHtml += `<li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>`;
        }

        paginationHtml += '</ul></nav>';
        paginationContainer.html(paginationHtml);

        // Add click event for pagination links
        $('.pagination .page-link').on('click', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            if (page) {
                var searchTerm = $('#explorerSearch').val();
                loadExplorers(searchTerm, page);
            }
        });
    }

    // Function to load customers with pagination
    function loadCustomers(searchTerm, page) {
        $.ajax({
            url: '{{ route("explorers.search") }}',
            type: 'GET',
            data: {
                term: searchTerm,
                page: page
            },
            success: function(response) {
                var tableBody = $('#explorerTableBody');
                tableBody.empty();

                if(response.explorers.length > 0) {
                    // Populate table with search results
                    $.each(response.explorers, function(index, explorer) {
                        var row = `<tr>
                            <td>${explorer.first_name} ${explorer.last_name}</td>
                            <td>${explorer.email}</td>
                            <td>${explorer.phone}</td>
                            <td>${explorer.address}</td>
                        </tr>`;
                        tableBody.append(row);
                    });

                    // Update pagination
                    updatePagination(response.pagination);
                } else {
                    tableBody.append('<tr><td colspan="5" class="text-center">No explorers found</td></tr>');
                    // Clear pagination if no results
                    $('.pagination').empty();
                }
            },
            error: function(xhr) {
                console.error('Error searching explorers:', xhr);
            }
        });
    }
});
</script>
@endpush
@endsection
