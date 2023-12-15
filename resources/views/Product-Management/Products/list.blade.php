@extends('layouts.master')
@section('title')
    @lang('translation.list-js')
@endsection
@section('css')
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Products
        @endslot
        @slot('title')
            Index
        @endslot
    @endcomponent

    @include('partials.session')
    
    <!-- row starts -->
    <div class="row">
        
        <!-- Col starts -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"><strong>Products</strong></h4>
                    <div class="col-sm-auto">
                        <div>
                            @can('Product create')
                                <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal"
                                    id="create-btn" data-bs-target="#showModal">
                                    <i class="ri-add-line align-bottom me-1"></i> Add
                                </button>
                            @endcan
                        </div>
                   
                    </div>
                </div>

                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Details</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Currency</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Pay Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td class="text-center">{{ ++$i }}</td>
                                    <td class="text-center">{{ $product->name }}</td>
                                    <td class="text-center">{{ $product->detail }}</td>
                                    <td class="text-center">{{ $product->price }}
                                    </td>
                                    <td class="text-center">{{ $product->quantity }}</td>
                                    <td class="text-center">{{ $product->currency }}</td>
                                    <td class="text-center">{{\Carbon\Carbon::parse($product->date)->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        @if ($product->paid)
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-warning">Not Paid</span>
                                        @endif
                                    </td>
                                    <td>
                                        
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('checkout', $product->id) }}" class="btn btn-sm btn-primary"><i class="ri-bank-card-line"></i></a>
                                            <div class="edit">
                                                @can('Product edit')
                                                    <button class="btn btn-sm btn-success edit-item-btn"><a href="{{ route('product.edit', encrypt($product->id)) }}" class="text-white"><i class="ri-edit-line"></i></a></button>
                                                @endcan
                                            </div>
                                            <div class="remove">
                                                @can('Product delete')                                                    
                                                    <button class="btn btn-sm btn-danger remove-item-btn" data-bs-toggle="modal"
                                                    data-bs-target="#deleteRecordModal{{ $product->id }}"><i
                                                    class="ri-delete-bin-5-line"></i></button>
                                                @endcan
                                            </div>
                                        </div>

                                        {{-- Modal Start   --}}
                                        <div class="modal fade zoomIn" id="deleteRecordModal{{ $product->id }}"
                                            tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close" id="btn-close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mt-2 text-center">
                                                            <script src="https://cdn.lordicon.com/lordicon-1.4.1.js"></script>
                                                            <lord-icon
                                                                src="https://cdn.lordicon.com/wpyrrmcq.json"
                                                                trigger="hover"
                                                                style="width:250px;height:250px">
                                                            </lord-icon>
                                                            <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                                                <h4>Are you Sure ?</h4>
                                                                <p class="text-muted mx-4 mb-0">Are you Sure You want to
                                                                Remove this Record ?</p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                            <button type="button" class="btn w-sm btn-light"
                                                                data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('product.destroy',encrypt($product->id)) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn w-sm btn-danger">Delete it!</button>
                                                                </form>            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Modal End --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
    
    
    <!-- Modal for form store -->
    
    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Role Here...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
        
                <form action="{{ route('product.store') }}" method="POST">
                    @csrf
                    <div class="modal-body mx-4 my-2">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control rounded-pill" id="name" name="name" placeholder="Enter Product Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="detail" class="form-label">Details</label>
                            <textarea class="form-control rounded-pill" id="detail" name="detail" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" class="form-control rounded-pill" id="price" name="price" placeholder="Enter Price" value="{{ old('price') }}">
                        </div>
                        <div class="mb-3">
                            <label for="quantity">Quantity</label>
                            <br>
                            <input type="number" id="quantity" name="quantity" min="1" max="1000">
                        </div>
                        <div class="mb-3">
                            <label for="currency" class="form-label">Currency</label>
                            <input type="text" class="form-control rounded-pill" id="currency" name="currency" placeholder="USD|GBP|PKR|AED|" required>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control rounded-pill" id="date" name="date" placeholder="Select Date" value="{{ old('date') }}">
                        </div>                        
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end"> 
                            <button type="submit" class="btn btn-success" id="add-btn">Add Product</button> 
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal for form store ends here .. -->
    <div class="mt-3 float-left">
        @can('Product create')
            <button type="button" class="btn btn-success add-btn">
                <a href="{{ route('show-product-excel') }}" style="color: white">Generate Excel</a>
            </button>
        @endcan
    </div>
    
    <div class="mt-3 float-right">
        @can('Product create')
            <button type="button" class="btn btn-success add-btn">
                <a href="{{ route('generate-pdf') }}" style="color: white">Generate PDF</a>
            </button>
        @endcan
    </div>
        

@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/list.js/list.js.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/list.pagination.js/list.pagination.js.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/listjs.init.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>




    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="{{ URL::asset('assets/js/pages/datatables.init.js') }}"></script>

    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
