@extends('layouts.admin')

@section('content')
<h1 class="page-header">لیست بروزرسانی نشده های اینستاگرام </h1>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-9 color-dark">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            لیست  اکانت های بروزرسانی نشده امروز اینستاگرام
                        <form action="{{ route('instagram-updatingProcess') }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg fl-left">بروزرسانی لیست</button>
                        </form>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>ردیف</th>
                                            <th>نام</th>
                                            <th> یوزرنیم </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($instagramLists as $instagramListItem)

                                        <tr class="odd gradeX">
                                            <td>{{ $loop->iteration }}</td>
                                            <td> {{ $instagramListItem->name }} </td>
                                            <td> {{ $instagramListItem->username }}</td>
                                        </tr>
                                            
                                        @endforeach
 
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
@endsection

@section('js')

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
        $(document).ready(function() {
          $('#dataTables-example').dataTable();
        });
    </script>
    
@endsection