@extends('layouts.admin')

@section('content')
<h1 class="page-header">لیست اینستاگرام </h1>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12 color-dark">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            لیست کلیه اکانت های اینستاگرام ثبت شده در سامانه
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
                                            <th>تعداد فالور</th>
                                            <th>نوع دسته بندی</th>
                                            <th>آخرین بروزرسانی</th>
                                            <th> مشاهده </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($instagramLists as $instagramListItem)

                                        <tr class="odd gradeX">
                                            <td>{{ $loop->iteration }}</td>
                                            <td> {{ $instagramListItem->name }} </td>
                                            <td> {{ $instagramListItem->username }}</td>
                                            <td class="center"> {{ $instagramListItem->follower_count }}</td>
                                            <td class="center">{{ $instagramListItem->business_category_name }}</td>
                                            @php
                                                $faUpdatedAt = new Verta($instagramListItem->updated_at);
                                            @endphp
                                            <td class="center">{{ $faUpdatedAt }}</td>
                                        <td class="center"> <a href="{{ route('instagram-profile', $instagramListItem->id) }}"><button type="button" class="btn btn-success btn-circle"><i class="fa fa-link"></i>
                                        </button></a></td>
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