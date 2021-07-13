<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <h2 style="margin-top:0px">Tb_uang_masuk <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Id Kat Uang Masuk <?php echo form_error('id_kat_uang_masuk') ?></label>
            <input type="text" class="form-control" name="id_kat_uang_masuk" id="id_kat_uang_masuk" placeholder="Id Kat Uang Masuk" value="<?php echo $id_kat_uang_masuk; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">No Bukti <?php echo form_error('no_bukti') ?></label>
            <input type="text" class="form-control" name="no_bukti" id="no_bukti" placeholder="No Bukti" value="<?php echo $no_bukti; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Nama Uang Masuk <?php echo form_error('nama_uang_masuk') ?></label>
            <input type="text" class="form-control" name="nama_uang_masuk" id="nama_uang_masuk" placeholder="Nama Uang Masuk" value="<?php echo $nama_uang_masuk; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Terima Dari <?php echo form_error('terima_dari') ?></label>
            <input type="text" class="form-control" name="terima_dari" id="terima_dari" placeholder="Terima Dari" value="<?php echo $terima_dari; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Diterima Oleh <?php echo form_error('diterima_oleh') ?></label>
            <input type="text" class="form-control" name="diterima_oleh" id="diterima_oleh" placeholder="Diterima Oleh" value="<?php echo $diterima_oleh; ?>" />
        </div>
	    <div class="form-group">
            <label for="untuk">Untuk <?php echo form_error('untuk') ?></label>
            <textarea class="form-control" rows="3" name="untuk" id="untuk" placeholder="Untuk"><?php echo $untuk; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="int">Nominal <?php echo form_error('nominal') ?></label>
            <input type="text" class="form-control" name="nominal" id="nominal" placeholder="Nominal" value="<?php echo $nominal; ?>" />
        </div>
	    <div class="form-group">
            <label for="ket_uang_masuk">Ket Uang Masuk <?php echo form_error('ket_uang_masuk') ?></label>
            <textarea class="form-control" rows="3" name="ket_uang_masuk" id="ket_uang_masuk" placeholder="Ket Uang Masuk"><?php echo $ket_uang_masuk; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Uang Masuk <?php echo form_error('tgl_uang_masuk') ?></label>
            <input type="text" class="form-control" name="tgl_uang_masuk" id="tgl_uang_masuk" placeholder="Tgl Uang Masuk" value="<?php echo $tgl_uang_masuk; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Ins <?php echo form_error('tgl_ins') ?></label>
            <input type="text" class="form-control" name="tgl_ins" id="tgl_ins" placeholder="Tgl Ins" value="<?php echo $tgl_ins; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Updt <?php echo form_error('tgl_updt') ?></label>
            <input type="text" class="form-control" name="tgl_updt" id="tgl_updt" placeholder="Tgl Updt" value="<?php echo $tgl_updt; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">User Updt <?php echo form_error('user_updt') ?></label>
            <input type="text" class="form-control" name="user_updt" id="user_updt" placeholder="User Updt" value="<?php echo $user_updt; ?>" />
        </div>
	    <input type="hidden" name="id_uang_masuk" value="<?php echo $id_uang_masuk; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('c_uang_masuk') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>