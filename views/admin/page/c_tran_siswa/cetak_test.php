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


  <div class="text-left">
      <p><strong>No. Pembayaran : <?=$no_bayar;?></strong></p>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12">
      <div class="table-responsive">
          <table class="table table-hover">
            <thead>
                <tr>
                    <th width="10%">#</th>
                    <th width="40%">Deskripsi</th>
                    <th width="15%">Periode</th>
                    <th class="text-right">Subtotal</th>
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
                  echo '<td>'.$row->periode.'</td>';
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
           <h4>Total: Rp. <?=number_format($total_tunggakan);?></h4>
           <input type="hidden" name="biaya_pengurang" id="biaya_pengurang" value="<?=$biaya_pengurang;?>" />
           <p>Potongan Biaya : Rp. <?=number_format($biaya_pengurang);?></p>
           <hr>
           <h3><b>Total Pembayaran:</b> Rp. <?=number_format($total_tunggakan - $biaya_pengurang);?></h3>
       <div class="clearfix"></div>
       <hr>
     </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <textarea name="keterangan" id="keterangan" placeholder="Keterangan" class="form-control" rows="2"></textarea>
      </div>
    </div>
  </div>

</div>



    <!-- jQuery -->
    <script src="<?=base_url();?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url();?>assets/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="<?=base_url();?>assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>

</body>

</html>
