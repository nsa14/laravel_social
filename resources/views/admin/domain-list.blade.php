@extends('layouts.admin')

@section('content')
<h1 class="page-header">لیست لایکی </h1>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12 color-dark">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            لیست کلیه دامنه های ثبت شده در سامانه
                            <?php

                                    // $data = json_decode(file_get_contents('https://www.namecheap.com/domains/contactlookup-api/whois/lookupraw/pooyatv.net'), 1);
                                    // // $data = " on http://www.nic.ir/ % % This server uses UTF-8 as the encoding for requests and responses. % NOTE: This output has been filtered. % Information related to 'irib.ir' domain: irib.ir ascii: irib.ir remarks: (Domain Holder) islamic republic of iran broadcasting remarks: (Domain Holder Address) Department Of IT-IRIB- P.O Box 19395-3333 Jaame Jam.St-Valiasr Ave-Tehran-Iran, tehran, tehran, IR holder-c: is547-irnic admin-c: is547-irnic tech-c: is547-irnic nserver: ns1.irib.ir nserver: ns2.irib.ir nserver: ns3.irib.ir last-updated: 2020-06-29 expire-date: 2021-07-07 source: IRNIC # Filtered nic-hdl: is547-irnic org: islamic republic of iran broadcasting e-mail: nic@irib.ir address: Department Of IT-IRIB- P.O Box 19395-3333 Jaame Jam.St-Valiasr Ave-Tehran-Iran, tehran, tehran, IR phone: +98 21 22164961 fax-no: +98 21 22164962 source: IRNIC # Filtered";
                                    // // preg_match('/(?<=expire-date:).*?(?=source)/', str_replace("\n","",$data), $matches);
                                    // preg_match('/(?<=Expiration Date:).*?(?=Registrar:)/', str_replace("\n","",$data), $matches);
                                    // var_dump($matches);

                            ?>
                        </div>

                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>ردیف</th>
                                            <th> url </th>
                                            <th> dot </th>
                                            <th> glabal rank </th>
                                            <th> local rank </th>
                                            <th> title </th>
                                            <th> howis </th>
                                            <th> Expires date </th>
                                            <th> redirect </th>
                                            <th> code </th>
                                            <th> اخرین بروزرسانی </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allDomains as $domain)

                                        <tr >
                                            <td>{{ $loop->iteration }}</td>
                                            <td> <a href="{{ 'https://'.$domain->url.'.'.$domain->dot.'/' }}" target="_blank"> {{ $domain->url }} </a></td>
                                            <td> {{ $domain->dot }} </td>
                                            <td class="center"> {{ $domain->globalrank }}</td>
                                            <td class="center">{{ $domain->localrank }}</td>
                                            <td style="font-size: 10px; word-wrap: break-word;min-width: 160px;max-width: 160px;">{{ $domain->title }}</td>
                                            <td class="center">{{ $domain->howis }}</td>
                                            <td class="text-nowrap">{{ substr($domain->expertion_date, 0, 11) }}</td>
                                            <td class="center text-sm">{{ $domain->redirect_to }}</td>
                                            <td class="center">{{ $domain->status_code }}</td>

                                            @php
                                                $faUpdatedAt = new Verta($domain->updated_at);
                                            @endphp
                                            <td class="center" style="font-size: 10px;">{{ $faUpdatedAt }}</td>
                                        {{-- <td class="center"> <a href="{{ route('instagram-profile', $domain->id) }}"><button type="button" class="btn btn-success btn-circle"><i class="fa fa-link"></i>
                                        </button></a></td> --}}
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
        //   $('#dataTables-example').dataTable();
          $('#dataTables-example').DataTable( {
            dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf'
                ]
            } );
        });
    </script>
    
@endsection