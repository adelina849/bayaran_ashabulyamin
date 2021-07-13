<!DOCTYPE html>
<html lang="en">

<head>
    <title>Aplikasi Bayaran Sekolah Ashabul Yamin</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url();?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?=base_url();?>assets/css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="<?=base_url();?>assets/css/colors/default.css" id="theme" rel="stylesheet">

</head>
<body class="fix-header">

    <div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-12">
                <div class="">
                    <div class="text-center">
                      <h2><b>YAYASAN ASHABUL YAMIN</b></h2>
                      <p>Jl. KH. Saleh No. 14 Pabuaran - Cianjur 43151
                        <br> Website: www.ashabulyamin.com,Email: ashabum@yamin, Telp: 0266251215
                      </p>
                    </div>
                    <hr>
                    <h4 class="text-center"><b>Bukti Pembayaran</b></h4>
                    <p class="text-center">No : <?=$no_bayar;?></p>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <table>
                                  <tr>
                                    <td width="100px">Tanggal</td><td>:</td><td><?=$tgl_bayar;?></td>
                                  </tr>
                                  <tr>
                                    <td>NIK Siswa</td><td>:</td><td><?=$kode_siswa;?></td>
                                  </tr>
                                  <tr>
                                    <td>Nama Siswa</td><td>:</td><td><?=$nama_siswa;?></td>
                                  </tr>
                                </table>
                            </div>
                            <!-- <div class="pull-right text-right">
                                <address>
                                    <h3>To,</h3>
                                    <h4 class="font-bold">Gaala &amp; Sons,</h4>
                                    <p class="text-muted m-l-30">E 104, Dharti-2,
                                        <br> Nr' Viswakarma Temple,
                                        <br> Talaja Road,
                                        <br> Bhavnagar - 364002</p>
                                    <p class="m-t-30"><b>Invoice Date :</b> <i class="fa fa-calendar"></i> 23rd Jan 2016</p>
                                    <p><b>Due Date :</b> <i class="fa fa-calendar"></i> 25th Jan 2016</p>
                                </address>
                            </div> -->
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive m-t-40" style="clear: both;">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Pembayaran</th>
                                            <th class="text-right">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      if(!empty($list_bayaran))
                                      {
                                        $list_result = $list_bayaran->result();
                                        $total_tunggakan=0;
                                        $no=1;
                                        foreach($list_result as $row)
                                        {
                                          echo '<tr>';
                                          echo '<td>'.$no.'</td>';
                                          echo '<td>'.$row->nama_bayaran.'</td>';
                                          //echo '<td>'.$row->periode.'</td>';
                                          echo '<td class="text-right">'.number_format($row->nominal).'</td>';
                                          echo '</tr>';

                                          $total_tunggakan += $row->nominal;
                                          $no++;
                                        }
                                      }

                                      ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="pull-right m-t-30 text-right">
                                <!-- <h4>Total Tagihan: Rp. <?=number_format($total_tunggakan - $biaya_pengurang);?></h4> -->
                                <!-- <p>vat (10%) : $138 </p> -->
                                <hr>
                                <h3><b>Jumlah Bayar :</b> Rp. <?=number_format($total_tunggakan - $biaya_pengurang);?></h3>
                                <p>Sisa Tagihan : Rp. <?=number_format($sisa_bayar);?></p>
                            </div>

                            <div class="clearfix"></div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- .row -->

        <!-- ============================================================== -->
    </div>




    <!-- jQuery -->
    <script src="<?=base_url();?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url();?>assets/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="<?=base_url();?>assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>

</body>

</html>
